<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Domains;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Medico.
 */
class Medico extends Model
{
    use SoftDeletes;

    protected $table = 'medicos';

    protected $fillable = [
        'name',
        'crm',
        'telefone1',
        'telefone2',
    ];

    protected $guarded = [];

    public function clinicas()
    {
        return $this->belongsToMany(User::class, 'medicos_clinicas', 'medico_id', 'user_id');
    }

    public function especialidades()
    {
        return $this->belongsToMany(Especialidade::class, 'especialidades_medicos', 'medicos_id', 'especialidades_id');
    }
}
