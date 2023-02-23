<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rentabilidade extends Model
{
    use SoftDeletes;

    protected $table = 'rentabilidades';

    protected $fillable = [
        'item_id',
        'titulo_id',
        'valor_fixo',
        'percentual', //decimal de 10,4
        'pago', //0 = não, 1 = sim
        'data',
        ];

    protected $dates = [
        'data',
    ];

    public function item()
    {
        return $this->belongsTo(Itens::class, 'item_id');
    }

    public function titulo()
    {
        return $this->belongsTo(Titulos::class, 'titulo_id');
    }
}
