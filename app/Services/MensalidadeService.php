<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

use Carbon\Carbon;
use App\Models\Contrato;
use App\Saude\Domains\Mensalidade;

class MensalidadeService
{
    public function mensalidadeEmpresa(Contrato $contrato)
    {
        $lastIdMensalidade = null;

        // dados mensalidades
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
    public function gerar(Contrato $contrato)
    {
        // dados mensalidades
        $dadoPagamento = [
                'user_id'   => $contrato->user_id,
                'pedido_id' => $contrato->pedido_id,
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
                    'boleto_id',
                    'status',
                ])
                ->whereIn('status', [1, 2, 3])
                ->orderBy('ano_referencia')
                ->orderBy('mes_referencia')
                ->get();

        if ($contrato->status == 1 || $mensalidades->count() == 0) {
            self::novaMensalidade($contrato, $dadoPagamento);
        } else {
            flash()->warning('Ja existem mensalidades geradas!');
        } /*else {
                if (2 == $this->getUsuario()->tipo) {
                    $ultimaMensalidade = $mensalidades->last();

                    if ($ultimaMensalidade->getOriginal()['status'] < 4) {
                        flash()->warning('Só é permitido gera uma nova mensalidade se a anterior estiver paga!');

                        return redirect()->back();
                    } else {
                        self::novaMensalidade($contrato, $dadoPagamento, $ultimaMensalidade);
                    }
                } else {
                    foreach ($mensalidades as $mes) {

                        $dadoPagamento['parcela'] = $mes->parcela;
                        $dadoPagamento['vencimento'] = Carbon::parse($mes->getOriginal()['dt_pagamento']);
                        $dadoPagamento['valor'] = $mes->valor;
                        $dadoPagamento['contrato_id'] = $mes->contrato_id.'/'.explode('/', $mes->parcela)[0];

                        $mensalidade = $this->gerar($dadoPagamento);

                    }
                }
            }*/

        return true;
    }

    public function novaMensalidade($contrato, $dadoPagamento, $lastMensalidade = false)
    {
        $lastIdMensalidade = null;

        $vencimento = $dtContrado = Carbon::parse($contrato->getOriginal()['dt_parcela']);

        if ($lastMensalidade) {
            $vencimento = $dtContrado = Carbon::parse($lastMensalidade->getOriginal()['dt_pagamento'])->addMonth();
        }

        $qtdMensalidades = $contrato->getRelation('item')->qtd_parcelas;

        self::geraMensalidades($qtdMensalidades, $lastIdMensalidade, $vencimento, $contrato, $dadoPagamento);
    }

    private function geraMensalidades($qtdMensalidades, $lastMensalidade, $vencimento, $contrato, $dadoPagamento, $lastIdMensalidade = null)
    {
        for ($i = 1; $i <= $qtdMensalidades; $i++) {
            $mensalidadeCad = self::salvaMensalidade($lastMensalidade, $i, $contrato, $vencimento, $dadoPagamento, $lastIdMensalidade);

            //Verifica se há um parcela anterior, e seta ela como proxima parcela
            if (! is_null($lastIdMensalidade) && $i < $qtdMensalidades) {
                Mensalidade::find($lastIdMensalidade)->update(['proxima' => $mensalidadeCad->id]);
            }

            if ($lastMensalidade) {
                $lastMensalidade->update([['proxima' => $mensalidadeCad->id]]);
            }

            //armazena ID da parcela atual
            $lastIdMensalidade = $mensalidadeCad->id;

            // guarda qual mensalidade o contrato deve esperar para bloquear associado ou não
            if ($i == 1) {
                $contrato->update(['status' => 2, 'aguarda_mensalidade' => $mensalidadeCad->id]);
            }

            \Log::info('Gerado mensalidade:', ['codigo_de_barras' => $mensalidadeCad->id, 'user ação' => \Auth::user()->id]);

            $vencimento->addMonth(1);
        }
    }

    private function salvaMensalidade($lastMensalidade, $i, $contrato, $vencimento, $dadoPagamento, $lastIdMensalidade)
    {
        $dadoPagamento['parcela'] = $i.'/'.$contrato->getRelation('item')->qtd_parcelas;
        $dadoPagamento['nParcela'] = $i;

        if ($lastMensalidade) {
            $dadoPagamento['parcela'] = (int) (explode('/', $lastMensalidade->parcela)[0]) + 1 .'/'.$contrato->getRelation('item')->qtd_parcelas;
            $dadoPagamento['nParcela'] = (int) (explode('/', $lastMensalidade->parcela)[0]) + 1;
        }

        $dadoPagamento['contrato_id'] = $contrato->id.'/'.$i;
        $dadoPagamento['vencimento'] = Carbon::createFromTimestamp($vencimento->getTimestamp());

        // gera demais dados do boleto
        //$mensalidade = $this->montarBoleto($dadoPagamento);

        // dados para persistir a mensalidade
        $dadosMensalidade = [
            'valor'            => $dadoPagamento['valor'],
            'user_id'          => $dadoPagamento['user_id'],
            'mes_referencia'   => $vencimento->month,
            'ano_referencia'   => $vencimento->year,
            'contrato_id'      => $contrato->id,
            'dt_pagamento'     => $vencimento,
            'parcela'          => $dadoPagamento['parcela'],
            'proxima'          => is_null($lastIdMensalidade) ? null : $lastIdMensalidade,
            'status'           => $i == 1 ? 2 : 1,
        ];

        return Mensalidade::create($dadosMensalidade);
    }
}
