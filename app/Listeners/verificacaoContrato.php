<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners;

use App\Events\BonusMensalidade;
use App\Services\VerificaContratos;

class verificacaoContrato
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
     * @param  BonusMensalidade $event
     * @return void
     */
    public function handle(BonusMensalidade $event)
    {
        new VerificaContratos($event);
    }
}
