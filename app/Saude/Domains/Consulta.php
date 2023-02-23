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
 * Class Consulta.
 */
class Consulta extends Model
{
    use SoftDeletes;

    protected $table = 'consultas';

    protected $fillable = [
        'data',
        'valor',
        'parceiro_id',
        'status',
        'medicos_id',
        'procedimentos_id',
        'especialidades_id',
        'titular_id',
        'dependentes_id',
        'observacao',
        'user_consultorio',
        'tipo_pagamento_id',
        'user_autoriza',
        'user_pagamento',
    ];

    protected $guarded = [];
}
