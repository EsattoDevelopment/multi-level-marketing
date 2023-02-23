<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Domains;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EspecialidadesMedico.
 */
class EspecialidadesMedico extends Model
{
    protected $table = 'especialidades_medicos';

    protected $primaryKey = 'especialidades_id';

    public $timestamps = false;

    protected $fillable = [
        'medicos_id',
    ];

    protected $guarded = [];
}
