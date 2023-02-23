<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Repositories;

use App\Models\Contrato;

class ContratoRepository
{
    public function getContrato($id, array $relations = [])
    {
        if (count($relations) > 0) {
            return Contrato::with($relations)->findOrFail($id);
        } else {
            return Contrato::findOrFail($id);
        }
    }
}
