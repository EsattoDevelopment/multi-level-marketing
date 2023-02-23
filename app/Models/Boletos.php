<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use App\Saude\Domains\Mensalidade;
use Illuminate\Database\Eloquent\Model;

class Boletos extends Model
{
    protected $table = 'boletos';

    protected $fillable = [
        'codigo_de_barras',
        'nosso_numero',
        'numero_documento',
        'vencimento',
    ];

    protected $dates = ['vencimento'];

    public function pedido()
    {
        return $this->hasOne(Pedidos::class, 'boleto_id');
    }

    public function mensalidade()
    {
        return $this->hasOne(Mensalidade::class, 'boleto_id');
    }

    public function remessa()
    {
        return $this->belongsTo(Remessa::class);
    }
}
