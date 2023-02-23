<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners;

use App\Events\PedidoFoiPago;

class QualificaUsuario
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
        if ($event->sistema->rede_binaria) {
            if ($event->getPatrocinador()->qualificado == 0) {
                $redeDireita = $event->getPatrocinador()->diretosDireita()->count();

                $redeEsquerda = $event->getPatrocinador()->diretosEsquerda()->count();

                if ($redeDireita > 0 && $redeEsquerda > 0) {
                    $event->getPatrocinador()->update(['qualificado' => 1]);
                }
            }

            return true;
        }

        return true;
    }
}
