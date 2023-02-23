<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

use App\Models\Sistema;

class FerramentasServices
{
    /**
     * @author Brunão
     * @param string $value
     * @return float|mixed|string
     */
    public function getMoney(string $value)
    {
        $sistema = Sistema::find(1);
        $value = str_replace([$sistema->moeda, ' '], '', $value);
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);
        $value = (float) $value;

        return $value;
    }
}
