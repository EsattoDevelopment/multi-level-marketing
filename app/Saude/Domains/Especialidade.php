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
 * Class Especialidade.
 */
class Especialidade extends Model
{
    use SoftDeletes;

    protected $table = 'especialidades';

    protected $fillable = [
        'codigo',
        'name',
        'descricao',
    ];

    protected $guarded = [];

    public function medicos()
    {
        return $this->belongsToMany(Medico::class, 'especialidades_medicos', 'especialidades_id', 'medicos_id');
    }
}
