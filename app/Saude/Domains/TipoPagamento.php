<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Domains;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoPagamento.
 */
class TipoPagamento extends Model
{
    protected $table = 'tipo_pagamento';

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    protected $guarded = [];
}
