<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners;

use App\Events\PedidoFoiPago;
use App\Models\PontosPessoais;
use App\Services\PagarPontosServices;

class PagaPontosPessoais
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
     * @param  PedidoFoiPago  $event
     * @return void
     */
    public function handle(PedidoFoiPago $event)
    {
        if (in_array($event->getPedido()->tipo_pedido, [1, 2, 3, 5])) {
            \Log::info('Pagando GMilhas pessoais');

            $usuario = $event->getUsuario();
            $itemPedido = $event->getPedido()->itens->first();

            //ajuste de dadosPagamento->valor_autorizado_diretoria para dadosPagamento->valor
            $pontos = round($itemPedido->valor_total * $itemPedido->item->pontos_pessoais);

            if ($pontos == 0) {
                \Log::info("Pontos zerados, fator do item{$itemPedido->item->pontos_pessoais}");

                return true;
            }

            $ultimosPontos = $usuario->pontosPessoais->last();
            $model = new PontosPessoais();

            (new PagarPontosServices())
                    ->pontos($pontos)
                    ->pedido($event->getPedido())
                    ->ultimosPontos($ultimosPontos)
                    ->model($model)
                    ->usuario($usuario)
                    ->operacao(1)
                    ->pagar();
        }

        return true;
    }
}
