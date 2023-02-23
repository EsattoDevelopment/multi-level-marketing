<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

class Cotacao
{
    public function dolar()
    {
        $valor = 0;
        $endPoint = 'https://economia.awesomeapi.com.br/all/USDT-BRL';

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $endPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if (! $err) {
            $obj = json_decode($response);
            if (isset($obj->USDT)) {
                $valor = $obj->USDT->ask;
            }
        }

        return $valor;
    }
}
