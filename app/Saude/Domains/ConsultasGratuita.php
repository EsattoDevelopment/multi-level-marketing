<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Domains;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ConsultasGratuita.
 */
class ConsultasGratuita extends Model
{
    protected $table = 'consultas_gratuitas';

    public $timestamps = false;

    protected $fillable = [
        'especialidades_id',
        'user_id',
        'quantidade',
        'inicio_validade',
        'fim_validade',
        'pedidos_id',
    ];

    protected $guarded = [];
}
