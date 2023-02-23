<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Domains;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ParceiroProcedimento.
 */
class ParceiroProcedimento extends Model
{
    protected $table = 'parceiro_procedimentos';

    public $timestamps = false;

    protected $fillable = [
        'parceiro_id',
        'procedimentos_id',
        'valor',
    ];

    protected $guarded = [];
}
