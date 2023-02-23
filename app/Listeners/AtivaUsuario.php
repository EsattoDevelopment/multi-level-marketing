<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners;

use Log;
use App\Events\PedidoFoiPago;

class AtivaUsuario
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
        Log::info('Entrou ativa usuario');

        $itensPedido = $event->getItens();

        foreach ($itensPedido as $itemPedido) {
            $itemPedido->itens()->first();

            if (in_array($event->getUsuario()->status, [0, 3, 4, 5])) {
                $event->getUsuario()->status = 1;
                $event->getUsuario()->save();
                Log::notice('Ativou usuário #'.$event->getUsuario()->id);
            }
        }

        return true;
    }
}
