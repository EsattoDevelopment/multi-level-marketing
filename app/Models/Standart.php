<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

class Standart
{
    // TIPOS DE PEDIDOS
    const PRIMEIRO_PEDIDO = 1;
    const PRIMEIRO_NORMAL = 2;
    const PRIMEIRO_ESPECIAL = 3;

    public function tipoDePedidos($id)
    {
        switch ($id) {
            case self::PRIMEIRO_PEDIDO:
                return 'Primeiro pedido';
                break;
            case self::PRIMEIRO_NORMAL:
                return 'Primeiro normal';
                break;
            case self::PRIMEIRO_ESPECIAL:
                return 'Pedido especial';
                break;
        }
    }
}
