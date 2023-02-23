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
 * Class Parceiro.
 */
class Parceiro extends Model
{
    use SoftDeletes;

    protected $table = 'parceiro';

    protected $fillable = [
        'name',
        'cnpj_cpf',
        'pertence_grupo',
    ];

    protected $guarded = [];
}
