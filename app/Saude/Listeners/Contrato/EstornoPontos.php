<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Listeners\Contrato;

use App\Saude\Events\CancelamentoContrato;

class EstornoPontos
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CancelamentoContrato  $event
     * @return void
     */
    public function handle(CancelamentoContrato $event)
    {
        \Log::info('----Inicio estorno Pontos');

        if (7 == $event->getContato()->status) {
            $pedido = $event->getContato()->pedido;

            $item = $pedido->itens->first();

            $item = $item->itens;

            //$extratoBinario = $userIndicador->extratoBinario()->sortByDesc('id')->take(1)->first();

            dd($item);

            $dadosExtratoBinario = [
                'pontos'             => -$item->pontos_binarios,
                'saldo_anterior'     => $extratoBinario->saldo,
                'saldo'              => $extratoBinario->saldo - $item->pontos_binarios,
                'referencia'         => $extratoBinario->id,
                'acumulado_direita'  => 0,
                'acumulado_esquerda' => 0,
                'acumulado_total'    => $extratoBinario->acumulado_total - $item->pontos_binarios,
                'saldo_direita'      => 0,
                'saldo_esquerda'     => 0,
                'user_id'            => $pedido->iser_id,
                'operacao_id'        => 22,
            ];

            ExtratoBinario::create($dadosExtratoBinario);
        }
        \Log::info('----Inicio estorno Pontos');
    }
}
