<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

use Carbon\Carbon;
use App\Models\Boletos;
use App\Models\Empresa;
use App\Models\Pedidos;
use App\Models\Remessa;
use App\Models\ContasEmpresa;
use App\Saude\Domains\Mensalidade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Eduardokum\LaravelBoleto\Pessoa;
use Eduardokum\LaravelBoleto\Cnab\Remessa\Cnab400\Banco\Sicredi as RemessaSicredi;

class RemessaService
{
    private $banco;
    private $empresa;
    private $beneficiario;
    private $count = 1;
    private $lastRemessa;

    /**
     * @return mixed
     */
    public function getLastRemessa()
    {
        return $this->lastRemessa;
    }

    /**
     * @param mixed $lastRemessa
     */
    public function setLastRemessa($lastRemessa)
    {
        $this->lastRemessa = $lastRemessa;

        if ($this->lastRemessa) {
            if ($this->lastRemessa->created_at->isToday() && $this->lastRemessa->numero >= 10) {
                $this->limiteDiario = true;
                Log::alert('Limite de remessas diaria alcançada!');
            } elseif ($this->lastRemessa->created_at->isToday()) {
                $this->count = $this->lastRemessa->numero + 1;
            }
        }
    }

    private $limiteDiario = false; //flag para saber se limite diario de remessas foi atingido

    /**
     * @return mixed
     */
    private function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * @param mixed $empresa
     */
    private function setEmpresa($empresa)
    {
        $this->empresa = $empresa;
    }

    /**
     * @return mixed
     */
    private function getBanco()
    {
        return $this->banco;
    }

    /**
     * @param mixed $banco
     */
    private function setBanco($banco)
    {
        $this->banco = $banco;
    }

    public function __construct()
    {
        Log::info('\n ****************Iniciado Remessa!****************');
        $this->setLastRemessa(Remessa::orderBy('id', 'desc')->first());

        $this->setBanco(ContasEmpresa::with('banco')->whereUsarBoleto(1)->first());
        $this->setEmpresa(Empresa::select(['id', 'logradouro', 'numero', 'cep', 'uf', 'cidade', 'cnpj', 'razao_social'])->findOrFail(1));

        $this->beneficiario = new Pessoa([
                'nome' => $this->getEmpresa()->razao_social,
                'endereco' => $this->getEmpresa()->logradouro.' ,'.$this->getEmpresa()->numero,
                'cep' => $this->getEmpresa()->cep,
                'uf' => $this->getEmpresa()->uf,
                'cidade' => $this->getEmpresa()->cidade,
                'documento' => $this->getEmpresa()->cnpj,
            ]);
    }

    private function getBoletos($take = 100)
    {
        return Boletos::where('pago', 0)
                ->where(function ($query) {
                    $query->whereHas('mensalidade', function ($query) {
                        $query->where('status', '<>', 4)
                            ->whereHas('contrato', function ($query) {
                                $query->whereIn('status', [2, 3])
                                    ->WhereHas('usuario', function ($query) {
                                        $query->whereIn('status', [1, 2, 3]);
                                    });
                            });
                    })->orWhereHas('pedido', function ($query) {
                        $query->whereNotIn('status', [3, 4])
                            ->whereNull('boleto_id')
                            ->WhereHas('usuario', function ($query) {
                                $query->whereIn('status', [1, 2]);
                            });
                    });
                })
                ->whereNull('remessa_id')
                ->select('id', 'nosso_numero', 'numero_documento', 'vencimento', 'remessa_id', DB::raw('COUNT(nosso_numero) as qtd'))
                ->groupBy('nosso_numero')
                ->having('qtd', '=', 1)
                ->take($take)
                ->orderBy('id')
                ->get();
    }

    public function gerar($qtd = 500)
    {
        $boletos = $this->getBoletos($qtd);

        Log::info("\n {$boletos->count()} boletos encontrados!");

        if ($boletos->count() > 0 && ! $this->limiteDiario) {
            do {
                $boletosArray = [];

                try {
                    DB::beginTransaction();

                    $nomeArquivo = $this->getBanco()->conta.config('constants.mes_remessa')[date('n')].date('d');

                    $cadRemessa = Remessa::create([
                            'id' => $this->getLastRemessa() ? $this->getLastRemessa()->id + 1 : 1,
                            'numero' => $this->count,
                            'arquivo' => $nomeArquivo,
                        ]);

                    $remessa = new RemessaSicredi(
                            [
                                'agencia' => $this->getBanco()->agencia,
                                'carteira' => $this->getBanco()->carteira,
                                'conta' => $this->getBanco()->conta,
                                'idremessa' => $cadRemessa->id,
                                'beneficiario' => $this->beneficiario,
                            ]
                        );

                    foreach ($boletos as $boleto) {

                            //$boleto->update(['remessa_id' => $cadRemessa->id]);
                        $boleto->remessa_id = $cadRemessa->id;
                        $boleto->save();

                        $dados = $boleto->mensalidade;

                        if (! $dados) {
                            $dados = $boleto->pedido;
                        }

                        Log::info($dados->user_id);

                        $serviceBoleto = new BoletoService($dados->user_id);

                        if ($dados instanceof Mensalidade) {
                            $dadosPagamento = [
                                    'vencimento' => Carbon::parse($dados->getOriginal()['dt_pagamento']),
                                    'valor' => $dados->valor,
                                    'boleto_id' => $dados->id <= 3134 ? $dados->contrato_id : $dados->boleto_id,
                                    'contrato_id' => $dados->contrato_id.'/'.explode('/', $dados->parcela)[0],
                                    'parcela' => $dados->parcela,
                                ];
                        } elseif ($dados instanceof Pedidos) {
                            $dadosPagamento = [
                                    'vencimento' => Carbon::parse($boleto->vencimento),
                                    'valor' => $dados->valor_total,
                                    'boleto_id' => $boleto->id,
                                    'contrato_id' => $boleto->id,
                                    'parcela' => '1/1',
                                ];
                        }

                        $boletosArray[] = $serviceBoleto->montarBoleto($dadosPagamento);
                    }

                    $remessa->addBoletos($boletosArray);

                    //$cadRemessa->update(['arquivo' => $nomeArquivo]);

                    DB::commit();

                    if ($this->count == 1) {
                        $extensao = '.CRM';
                    } else {
                        if ($this->count < 10) {
                            $extensao = '.RM'.$this->count;
                        } else {
                            $extensao = '.RM0';
                        }
                    }

                    $remessa->save(public_path('remessas/'.date('Y').'/'.$nomeArquivo.$extensao));

                    $this->count++;

                    $this->setLastRemessa(Remessa::orderBy('id', 'desc')->first());
                } catch (\Exception $e) {
                    DB::rollback();
                    dd($e);

                    return abort(500, $e->getMessage());
                }

                //verifica se há mais moletos
                $boletos = $this->getBoletos($qtd);
                Log::info("\n  +{$boletos->count()} boletos encontrados!");
            } while ($boletos->count() > 0 && $this->count <= 10 && ! $this->limiteDiario);

            //dd('deu certo mano');

            $this->count--;

            flash()->success("{$this->count} remessas geradas");
            Log::info("{$this->count} remessas geradas!");

            return redirect()->route('remessa.index');
        } else {
            if ($this->limiteDiario) {
                //dd('O limite de 10 remessas diárias foi atingido!');
                Log::info('O limite de 10 remessas diárias foi atingido!');
                flash()->error('O limite de 10 remessas diárias foi atingido!');
            } else {
                //dd('Não há boletos novos para gerar remessas!');
                Log::info('Não há boletos novos para gerar remessas!');
                flash()->error('Não há boletos novos para gerar remessas!');
            }

            return redirect()->back();
        }
    }
}
