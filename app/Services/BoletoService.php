<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

use Log;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Boletos;
use App\Models\Empresa;
use App\Models\Contrato;
use App\Models\ContasEmpresa;
use App\Saude\Domains\Mensalidade;
use Eduardokum\LaravelBoleto\Pessoa;
use Illuminate\Support\Facades\Auth;
use Eduardokum\LaravelBoleto\Boleto\Banco\Bb;
use Eduardokum\LaravelBoleto\Contracts\Boleto;
use Eduardokum\LaravelBoleto\Boleto\Banco\Hsbc;
use Eduardokum\LaravelBoleto\Boleto\Banco\Itau;
use Eduardokum\LaravelBoleto\Boleto\Banco\Caixa;
use Eduardokum\LaravelBoleto\Boleto\Banco\Sicredi;
use Eduardokum\LaravelBoleto\Boleto\Banco\Bradesco;
use Eduardokum\LaravelBoleto\Boleto\Banco\Santander;

class BoletoService
{
    private $banco;
    private $empresa;
    private $usuario;
    private $endereco;

    /**
     * @return mixed
     */
    public function getEndereco()
    {
        return $this->endereco;
    }

    /**
     * @param mixed $endereco
     */
    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * @return mixed
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * @param mixed $empresa
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;
    }

    /**
     * @return mixed
     */
    public function getBanco()
    {
        return $this->banco;
    }

    public function haveBanco()
    {
        return $this->getBanco() instanceof ContasEmpresa ? true : false;
    }

    /**
     * @param mixed $banco
     */
    public function setBanco($banco)
    {
        $this->banco = $banco;
    }

    public function __construct($userId)
    {
        $this->setBanco(ContasEmpresa::with('banco')->whereUsarBoleto(1)->first());
        $this->setEmpresa(Empresa::select(['id', 'logradouro', 'numero', 'cep', 'uf', 'cidade', 'cnpj', 'razao_social'])->findOrFail(1));
        $this->setUsuario(User::select(['id', 'name', 'cpf', 'tipo', 'cnpj'])->findOrFail($userId));

        $endereco = $this->getUsuario()->load([
                'endereco' => function ($query) {
                    $query->select(['id', 'logradouro', 'numero', 'bairro', 'cep', 'estado', 'cidade', 'user_id'])
                        ->with([
                            'enderecoEditor' => function ($query) {
                                $query->select(['id', 'logradouro', 'numero', 'bairro', 'cep', 'estado', 'cidade', 'enderecos_usuario_id'])->latest('id');
                            },
                        ]);
                },
            ])->getRelation('endereco');

        if ($endereco->getRelation('enderecoEditor')->first()) {
            $endereco = $endereco->getRelation('enderecoEditor')->first();
        }

        $this->setEndereco($endereco);
    }

    public function mensalidadeEmpresa(Contrato $contrato)
    {
        $lastIdMensalidade = null;

        //dados mensalidades
        $dadoPagamento = [
                'user_id'   => $contrato->user_id,
                'pedido_id' => $contrato->pedido_id,
                'banco'     => $this->getBanco(),
                'valor'     => $contrato->getRelation('item')->vl_parcelas,
            ];

        $mensalidades = $contrato->mensalidades()
                ->select([
                    'id',
                    'parcela',
                    'dt_pagamento',
                    'dt_pagamento',
                    'valor',
                    'contrato_id',
                ])
                ->whereIn('status', [1, 2, 3])
                ->orderBy('ano_referencia')
                ->orderBy('mes_referencia')
                ->get();

        if ($mensalidades->count() == 0) {
            $vencimento = $dtContrado = Carbon::parse($contrato->getOriginal()['dt_parcela']);
        }
    }

    /**
     * Cria um carne de boletos.
     *
     * @param Contrato $contrato
     *
     * @return array
     */
    public function carne(Contrato $contrato)
    {
        $carne = [];

        if (2 == $this->getUsuario()->tipo) {
            $multiplicador = $this->getUsuario()->funcionarios()->count();
            $multiplicador = $multiplicador == 0 ? 1 : $multiplicador;
        } else {
            $multiplicador = 1;
        }

        //dados mensalidades
        $dadoPagamento = [
                'user_id'   => $contrato->user_id,
                'pedido_id' => $contrato->pedido_id,
                'banco'     => $this->getBanco(),
                'valor'     => $multiplicador * $contrato->getRelation('item')->vl_parcelas,
            ];

        $mensalidades = $contrato->mensalidades()
                ->select([
                    'id',
                    'parcela',
                    'dt_pagamento',
                    'dt_pagamento',
                    'valor',
                    'contrato_id',
                    'boleto_id',
                    'status',
                ])
                ->whereIn('status', [1, 2, 3])
                ->orderBy('ano_referencia')
                ->orderBy('mes_referencia')
                ->get();

        if ($contrato->status == 1 || $mensalidades->count() == 0) {
            $this->novoBoleto($contrato, $dadoPagamento, $carne);
        } else {
            if (2 == $this->getUsuario()->tipo) {
                $ultimaMensalidade = $mensalidades->last();

                if ($ultimaMensalidade->getOriginal()['status'] < 4) {
                    flash()->warning('Só é permitido gera uma nova mensalidade se a anterior estiver paga!');

                    return redirect()->back();
                } else {
                    $this->novoBoleto($contrato, $dadoPagamento, $carne, $ultimaMensalidade);
                }
            } else {
                foreach ($mensalidades as $mes) {

                        //mais dados mensalidades
                    if ($mes->id <= 3134) {
                        $dadoPagamento['boleto_id'] = $mes->contrato_id;
                    } else {
                        $dadoPagamento['boleto_id'] = $mes->boleto_id;
                    }

                    $dadoPagamento['parcela'] = $mes->parcela;
                    $dadoPagamento['vencimento'] = Carbon::parse($mes->getOriginal()['dt_pagamento']);
                    $dadoPagamento['valor'] = $mes->valor;
                    $dadoPagamento['contrato_id'] = $mes->contrato_id.'/'.explode('/', $mes->parcela)[0];

                    $mensalidade = $this->montarBoleto($dadoPagamento);

                    $carne[] = $mensalidade;
                }
            }
        }

        //tira variaveis da memoria
        unset($mensalidade);
        unset($dadoPagamento);

        return $carne;
    }

    /**
     * Gerar novo boleto.
     *
     * @param      $contrato
     * @param      $dadoPagamento
     * @param      $carne
     * @param bool $lastMensalidade
     */
    private function novoBoleto($contrato, $dadoPagamento, &$carne, $lastMensalidade = false)
    {
        $lastIdMensalidade = null;

        if ($lastMensalidade) {
            $vencimento = $dtContrado = Carbon::parse($lastMensalidade->getOriginal()['dt_pagamento'])->addMonth();
        } else {
            $vencimento = $dtContrado = Carbon::parse($contrato->getOriginal()['dt_parcela']);
        }

        $qtdMensalidades = 1;

        if (1 == $this->getUsuario()->tipo) {
            $qtdMensalidades = $contrato->getRelation('item')->qtd_parcelas;
        }

        for ($i = 1; $i <= $qtdMensalidades; $i++) {

                //gera boleto para ser salvo com a mensalidade
            $boletoCreated = Boletos::create(['vencimento' => $vencimento]);

            if (! $lastMensalidade) {
                $dadoPagamento['parcela'] = $i.'/'.$contrato->getRelation('item')->qtd_parcelas;
                $dadoPagamento['nParcela'] = $i;
            } else {
                $dadoPagamento['parcela'] = (int) (explode('/', $lastMensalidade->parcela)[0]) + 1 .'/'.$contrato->getRelation('item')->qtd_parcelas;
                $dadoPagamento['nParcela'] = (int) (explode('/', $lastMensalidade->parcela)[0]) + 1;
            }

            $dadoPagamento['boleto_id'] = $boletoCreated->id;
            $dadoPagamento['contrato_id'] = $contrato->id.'/'.$i;
            $dadoPagamento['vencimento'] = Carbon::createFromTimestamp($vencimento->getTimestamp());

            //gera demais dados do boleto
            $mensalidade = $this->montarBoleto($dadoPagamento);

            $boletoCreated->update([
                    'codigo_de_barras' => $mensalidade->getCodigoBarras(),
                    'nosso_numero'     => $mensalidade->getNossoNumero(),
                    'numero_documento' => $mensalidade->getNumeroDocumento(),
                ]);

            //dados para persistir a mensalidade
            $dadosMensalidade = [
                    'valor'            => $dadoPagamento['valor'],
                    'user_id'          => $dadoPagamento['user_id'],
                    'mes_referencia'   => $vencimento->month,
                    'ano_referencia'   => $vencimento->year,
                    'contrato_id'      => $contrato->id,
                    'codigo_de_barras' => $mensalidade->getCodigoBarras(),
                    'nosso_numero'     => $mensalidade->getNossoNumero(),
                    'numero_documento' => $mensalidade->getNumeroDocumento(),
                    'dt_pagamento'     => $vencimento,
                    'parcela'          => $dadoPagamento['parcela'],
                    'boleto_id'        => $boletoCreated->id,
                    'proxima'          => is_null($lastIdMensalidade) ? null : $lastIdMensalidade,
                    'status'           => $i == 1 ? 2 : 1,
                ];

            $mensalidadeCad = Mensalidade::create($dadosMensalidade);

            //Verifica se há um parcela anterior, e seta ela como proxima parcela
            if (! is_null($lastIdMensalidade) && $i < 12) {
                Mensalidade::find($lastIdMensalidade)->update(['proxima' => $mensalidadeCad->id]);
            }

            if ($lastMensalidade) {
                $lastMensalidade->update([['proxima' => $mensalidadeCad->id]]);
            }

            //armazena ID da parcela atual
            $lastIdMensalidade = $mensalidadeCad->id;

            //guarda qual mensalidade o contrato deve esperar para bloquear associado ou não
            if ($i == 1) {
                $contrato->update(['status' => 2, 'aguarda_mensalidade' => $mensalidadeCad->id]);
            }

            \Log::info('Gerado mensalidade:', ['codigo_de_barras' => $mensalidade->getCodigoBarras(), 'user ação' => Auth::user()->id]);

            $carne[] = $mensalidade;

            $vencimento->addMonth(1);
        }
    }

    /**
     * Montar o boleto.
     *
     * @param array $dadosPagamento
     *
     * @return Bb|Bradesco|Caixa|Hsbc|Itau|Santander
     */
    public function montarBoleto(array $dadosPagamento)
    {
        Log::info('Montando boleto...');

        //separa os dados dos beneficiarios
        $beneficiario = new Pessoa([
                'nome'      => $this->getEmpresa()->razao_social,
                'endereco'  => $this->getEmpresa()->logradouro.' ,'.$this->getEmpresa()->numero,
                'cep'       => $this->getEmpresa()->cep,
                'uf'        => $this->getEmpresa()->uf,
                'cidade'    => $this->getEmpresa()->cidade,
                'documento' => $this->getEmpresa()->cnpj,
            ]);

        //separa os dados do pagador
        $pagador = new Pessoa([
                'nome'      => $this->retirarEspacos($this->getUsuario()->name),
                'endereco'  => $this->retirarEspacos($this->getEndereco()->logradouro).' ,'.$this->retirarEspacos($this->getEndereco()->numero),
                'bairro'    => $this->retirarEspacos($this->getEndereco()->bairro),
                'cep'       => trim($this->getEndereco()->cep),
                'uf'        => $this->getEndereco()->estado,
                'cidade'    => $this->retirarEspacos($this->getEndereco()->cidade),
                'documento' => $this->getUsuario()->tipo == 1 ? $this->getUsuario()->cpf : $this->getUsuario()->cnpj,
            ]);

        $boleto = $this->gerarBoleto($beneficiario, $pagador, $dadosPagamento);

        return $boleto;
    }

    /**
     * Gera os dados do boleto.
     *
     * @param $beneficiario
     * @param $pagador
     * @param $dadosPagamento
     *
     * @return Bb|Bradesco|Caixa|Hsbc|Itau|Santander
     */
    private function gerarBoleto($beneficiario, $pagador, $dadosPagamento)
    {
        $boleto = [
                'logo'                   => public_path('logos/logo-saude.png'),
                'dataVencimento'         => $dadosPagamento['vencimento'],
                'valor'                  => $dadosPagamento['valor'],
                'multa'                  => $this->getBanco()->multa == 0 ? false : $this->getBanco()->multa,
                'juros'                  => $this->getBanco()->juros == 0 ? false : $this->getBanco()->juros,
                'numero'                 => $dadosPagamento['boleto_id'],
                'numeroDocumento'        => $dadosPagamento['contrato_id'],
                'pagador'                => $pagador,
                'beneficiario'           => $beneficiario,
                'agencia'                => $this->getBanco()->agencia,
                'conta'                  => $this->getBanco()->conta,
                'descricaoDemonstrativo' => [$this->getBanco()->msg1, $this->getBanco()->msg2, $this->getBanco()->msg3, $this->getBanco()->msg4, $this->getBanco()->msg5],
                'instrucoes'             => [$this->getBanco()->inst1, $this->getBanco()->inst2, $this->getBanco()->inst3, $this->getBanco()->inst4, $this->getBanco()->inst5],
                'aceite'                 => $this->getBanco()->aceite ? 'S' : 'N',
                'especieDoc'             => $this->getBanco()->especieDoc,
                'numero_controle'        => $dadosPagamento['parcela'],
            ];

        switch ($this->getBanco()->getRelation('banco')->codigo) {
                case Boleto\Boleto::COD_BANCO_CEF:
                    Log::info('boleto caixa');

                    return $this->boletoCaixa($boleto, $this->getBanco());
                    break;
                case Boleto\Boleto::COD_BANCO_BB:
                    Log::info('boleto bb');

                    return $this->boletoBb($boleto, $this->getBanco());
                    break;
                case Boleto\Boleto::COD_BANCO_BRADESCO:
                    Log::info('boleto bradesco');

                    return $this->boletoBradesco($boleto, $this->getBanco());
                    break;
                case Boleto\Boleto::COD_BANCO_ITAU:
                    Log::info('boleto itau');

                    return $this->boletoItau($boleto, $this->getBanco());
                    break;
                case Boleto\Boleto::COD_BANCO_BRADESCO:
                    Log::info('boleto santander');

                    return $this->boletoSantander($boleto, $this->getBanco());
                    break;
                case Boleto\Boleto::COD_BANCO_HSBC:
                    Log::info('boleto hsbc');

                    return $this->boletoHsbc($boleto, $this->getBanco());
                    break;
                case Boleto\Boleto::COD_BANCO_SICREDI:
                    Log::info('boleto Sicred');

                    return $this->boletoSicred($boleto, $this->getBanco());
                    break;
            }
    }

    private function retirarEspacos($str)
    {
        $palavras = explode(' ', $str);

        foreach ($palavras as $index => $palavra) {
            $palavras[$index] = trim($palavra);
        }

        return implode(' ', $palavras);
    }

    /**
     * Acrecenta campos especificos.
     *
     * @param $boleto
     * @param $conta
     *
     * @return Caixa
     */
    private function boletoCaixa($boleto, $conta)
    {
        $boleto['carteira'] = 'RG';
        $boleto['codigoCliente'] = $conta->codigoCliente;

        return new Caixa($boleto);
    }

    private function boletoBb($boleto, $conta)
    {
        $boleto['carteira'] = $conta->carteira;
        $boleto['convenio'] = $conta->convenio;

        return new Bb($boleto);
    }

    private function boletoBradesco($boleto, $conta)
    {
        $boleto['carteira'] = $conta->carteira;

        return new Bradesco($boleto);
    }

    private function boletoItau($boleto, $conta)
    {
        $boleto['carteira'] = $conta->carteira;

        return new Itau($boleto);
    }

    private function boletoSantander($boleto, $conta)
    {
        $boleto['carteira'] = $conta->carteira;

        return new Santander($boleto);
    }

    private function boletoHsbc($boleto, $conta)
    {
        $boleto['carteira'] = 'CSB';
        $boleto['range'] = $conta->range;
        $boleto['contaDv'] = $conta->contaDv;

        return new Hsbc($boleto);
    }

    private function boletoSicred($boleto, $conta)
    {
        $boleto['carteira'] = $conta->carteira;
        $boleto['posto'] = 03;
        //$boleto['registro'] = 1;

        return new Sicredi($boleto);
    }
}
