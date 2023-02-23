<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PedidoRecontratados extends Model
{
    use SoftDeletes;

    protected $table = 'pedidos_recontratados';

    protected $fillable = [
        'pedido_id_finalizado',
        'pedido_id_recontratado',
        'modo_recontratacao_automatica',
        'item_id',
        'user_id',
    ];

    protected $dates = ['created_at', 'data_compra'];
}
