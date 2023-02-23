<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Domains;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Procedimento.
 */
class Procedimento extends Model
{
    use SoftDeletes;

    protected $table = 'procedimentos';

    protected $fillable = [
        'codigo',
        'name',
        'co',
        'ch',
        'valor',
    ];

    protected $guarded = [];
}
