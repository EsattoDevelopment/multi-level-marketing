<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracaoBonus extends Model
{
    protected $table = 'configuracao_bonus';

    protected $fillable = [
        'nome',
        'status',
        'itens',
        'user_id',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'itens' => 'array',
    ];

    public function setItensAttribute($value)
    {
        $this->attributes['itens'] = json_encode($value);
    }

    public function titulosBonusAdesao()
    {
        return $this->hasMany(Titulos::class, 'configuracao_bonus_adesao_id');
    }

    public function titulosBonusRentabilidade()
    {
        return $this->hasMany(Titulos::class, 'configuracao_bonus_rentabilidade_id');
    }

    public function itensBonusAdesao()
    {
        return $this->hasMany(Itens::class, 'configuracao_bonus_adesao_id');
    }

    public function itensBonusRentabilidade()
    {
        return $this->hasMany(Itens::class, 'configuracao_bonus_rentabilidade_id');
    }
}
