<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Modules\NumerosPorExtenso;

use Illuminate\Support\Facades\Facade;

class NumeroPorExtensoFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'numeroPorExtenso';
    }
}
