<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners;

use Log;
use App\Events\PedidoFoiPago;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GerarContrato
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
        try {
            $pedido = $event->getPedido();
            if (! in_array($pedido->tipo_pedido, [3, 4])) {
                Log::info('Entrou gerar contrato #'.$pedido->id);
                dispatch(new \App\Jobs\GerarContrato($pedido, true));

                return true;
            }

            return false;
        } catch (ModelNotFoundException $e) {
            Log::error('Erro no Listener do contrato: '.$e->getMessage());

            return false;
        }
    }
}
