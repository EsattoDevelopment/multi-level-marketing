<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Domains;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ParceiroMedico.
 */
class ParceiroMedico extends Model
{
    protected $table = 'parceiro_medicos';

    protected $primaryKey = 'parceiro_id';

    public $timestamps = false;

    protected $fillable = [
        'medicos_id',
    ];

    protected $guarded = [];
}
