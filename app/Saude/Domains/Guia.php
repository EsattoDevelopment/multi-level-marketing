<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Domains;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Itens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Medico.
 */
class Guia extends Model
{
    use SoftDeletes;

    protected $table = 'guias';

    protected $dates = [
        'created_at',
        'dt_autorizado',
        'dt_atendimento',
    ];

    protected $fillable = [
        'tipo',
        'tipo_atendimento',
        'dt_atendimento',
        'medico_id',
        'valor_consulta',
        'dependente_id',
        'plano_id',
        'user_id',
        'observacao',
        'confirmado_por',
        'clinica_id',
        'autorizado',
        'dt_autorizado',
        'autorizado_por',
        'guia_referencia',
        'valor_fisioterapia',
    ];

    protected $guarded = [];

    public function setDtAtendimentoAttribute($value)
    {
        return $this->attributes['dt_atendimento'] = implode('-', array_reverse(explode('/', $value)));
    }

    public function getDtAtendimentoAttribute()
    {
        return Carbon::parse($this->attributes['dt_atendimento'])->format('d/m/Y');
    }

    public function dependente()
    {
        return $this->belongsTo(Dependente::class);
    }

    public function plano()
    {
        return $this->belongsTo(Itens::class, 'plano_id');
    }

    public function confimacao()
    {
        return $this->belongsTo(User::class, 'confirmado_por');
    }

    public function clinica()
    {
        return $this->belongsTo(User::class, 'clinica_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }

    public function exames()
    {
        return $this->belongsToMany(Exame::class, 'guia_has_exames', 'guia_id');
    }

    public function procedimentos()
    {
        return $this->belongsToMany(Procedimento::class, 'guia_has_procedimentos', 'guia_id');
    }
}
