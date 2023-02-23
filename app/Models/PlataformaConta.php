<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlataformaConta extends Model
{
    //use SoftDeletes;

    protected $table = 'plataforma_conta';

    protected $fillable = [
        'nome',
        'descricao',
        'status',
        'plataforma_id',
    ];

    public function plataforma()
    {
        return $this->belongsTo(Plataforma::class, 'plataforma_id');
    }
}
