<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 *
 */

/**
 * @return int
 */
function casasDecimais():int
{
    return 2;
}

/**
 * @return string
 */
function separadorArray():string
{
    return ',';
}

/**
 * @param $valor
 * @return float
 */
function decimal($valor):float
{
    return round($valor, casasDecimais());
}

/**
 * @param $valor
 * @return int
 */
function int($valor):int
{
    return round($valor);
}

/**
 * @param $valor
 * @return string
 */
function string($valor):string
{
    if (gettype($valor) == 'string') {
        return $valor;
    } elseif (gettype($valor) == 'float' || gettype($valor) == 'double') {
        return number_format($valor, casasDecimais(), '.', '');
    } elseif (gettype($valor) == 'integer') {
        return number_format($valor, 0, '.', '');
    } elseif (gettype($valor) == 'array') {
        return implode(separadorArray(), $valor);
    }
}

/**
 * @param mixed ...$valores
 * @return array
 */
function vetor(...$valores):array
{
    $retorno = [];
    $contador = 0;
    foreach ($valores as $valor) {
        if (gettype($valor) == 'string') {
            $itens = explode(separadorArray(), $valor);
            foreach ($itens as $item) {
                $retorno[++$contador] = $item;
            }
        } elseif (gettype($valor) == 'array') {
            $itens = $valor;
            foreach ($itens as $item) {
                $retorno[++$contador] = $item;
            }
        } else {
            $retorno[++$contador] = $valor;
        }
    }

    return $retorno;
}
