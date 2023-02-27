<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracaoEmprestimo extends Model
{
    protected $table = 'configuracao_emprestimo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'numero',
        'nome',
        'grupo',
        'valor_porcentagem',
        'valor_fixo',
    ];
}
