<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners;

use App\Events\MensalidadeSemBonus;
use App\Services\VerificaContratos;

class verificacaoContrato2
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
     * @param  MensalidadeSemBonus  $event
     * @return void
     */
    public function handle(MensalidadeSemBonus $event)
    {
        new VerificaContratos($event);
    }
}
