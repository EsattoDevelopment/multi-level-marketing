<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners;

use App\Events\PedidoFoiPago;
use App\Models\PontosEquipeUnilevel;
use App\Services\PagarPontosServices;
use App\Models\PontosEquipeEquiparacao;

class PagaPontosEquiparacao
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  PedidoFoiPago  $event
     * @return void
     */
    public function handle(PedidoFoiPago $event)
    {
        \Log::warning('Pagando pontos Equiparação');

        $usuario = $event->getUsuario()->getRelation('indicador');
        $itemPedido = $event->getPedido()->itens->first();

        //ajuste de dadosPagamento->valor_autorizado_diretoria para dadosPagamento->valor
        $pontos = $itemPedido->valor_total * $itemPedido->item->pontos_equipe;
        \Log::info("Pontos: {$pontos}");

        if ($pontos > 0):
            $ultimosPontos = $usuario->pontosEquiparacao->last();
        $model = new PontosEquipeEquiparacao();

        if ($event->getPedido()->tipo_pedido == 3) {
            $ultimosPontos = $usuario->pontosUnilevel->last();
            $model = new PontosEquipeUnilevel();
        }

        (new PagarPontosServices())
                ->pontos($pontos)
                ->pedido($event->getPedido())
                ->ultimosPontos($ultimosPontos)
                ->model($model)
                ->usuario($usuario)
                ->operacao(1)
                ->pagar();
        endif;

        return true;
    }
}
