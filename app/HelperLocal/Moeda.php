<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

    /**
     * Retorna um valor formatado com mascara da moeda escolhida.
     *
     * @param $formato: formato do moeda Ex. R$ para Real, $ para Dolar
     * @param $valor: valor a ser aplicado a mascara
     * @param $casaDecimal: o numero de casas decimais para a mascara
     * @param $retornaFormato: retornar com o formato da moeda.
     * @return valor com mascara
     */
    function mascaraMoeda($formato = 'R$', $valor = '0.00', $casaDecimal = 2, $retornaFormato = false)
    {
        $formato = strtoupper($formato);

        if (trim($formato) == 'R$') {
            $valor = number_format(convertDoubleGeral($valor), $casaDecimal, ',', '.');
        } elseif (trim($formato) == '$') {
            $valor = number_format(convertDoubleGeral($valor), $casaDecimal, '.', ',');
        }

        if ($retornaFormato) {
            $valor = $formato.' '.$valor;
        }

        return $valor;
    }

    function convertDoubleGeral($valor = '0.00')
    {
        $valor = (string) $valor;
        $posPonto = strpos($valor, '.');
        $posVirgula = strpos($valor, ',');

        if ($posPonto === false) {
            $posPonto = -1;
        }

        if ($posVirgula === false) {
            $posVirgula = -1;
        }

        try {
            if ($posPonto > -1 || $posVirgula > -1) {
                if ($posPonto == 0 || $posVirgula == 0) {
                    //o ponto ou virgula é o primeiro caracter
                    $valor = '0.00';
                } elseif ($posPonto > -1 && $posVirgula > -1) {
                    //tem virgula e ponto na string
                    if ($posPonto < $posVirgula) {
                        //o ponto vem antes da virgula
                        $valor = str_replace('.', '', $valor);
                        $valor = str_replace(',', '.', $valor);
                    } else {
                        //a virgula vem antes do ponto
                        $valor = str_replace(',', '', $valor);
                    }
                } elseif ($posVirgula > $posPonto) {
                    //só tem virgula
                    $valor = str_replace(',', '.', $valor);
                }
            }

            $valor = (float) $valor;
        } catch (Exception $e) {
            $valor = (float) '0.00';
        }

        return $valor;
    }
