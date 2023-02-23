<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoPedidos extends Model
{
    protected $table = 'tipo_pedidos';

    protected $fillable = ['name'];

    public function itens()
    {
        return $this->hasMany(self::class, 'tipo_pedido_id');
    }
}
