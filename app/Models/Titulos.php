<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Titulos extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'titulos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        //'dinheiro_indicador', OBSOLETO

        //'min_diretos_aprovados', //OBSOLETO
        'min_diretos_aprovados_matriz', //usado na matriz
        'min_diretos_aprovados_binario_perna', //usado no binario

        'bonus_indicador', // Bonus para patrocinador quando direto subir de titulo
        //'bonus_indicador_percentual', Não utilizado

        'binario_patrocinado',
        'percentual_binario',
        'teto_pagamento_sobre_binario',

        'teto_mensal_financeiro',

        'min_pontuacao_perna_menor',

        'acumulo_pessoal_milhas',
        'milhas_indicador',
        'bonus_hvip_diretos',

        'descricao',

        'titulo_inicial', // true or false
        'titulo_superior',

        'recebe_pontuacao', //true or false

        'cor',
        'user_id',
        'habilita_rede',

        'equiparacao_percentual',
        'pontos_pessoais_update',
        'pontos_equipe_update',
        'titulos_update',
        'configuracao_bonus_adesao_id',
        'configuracao_bonus_rentabilidade_id',
    ];

    protected $casts = [
        'titulos_update' => 'array',
    ];

    public function setTitulosUpdateAttribute($value)
    {
        $this->attributes['titulos_update'] = json_encode($value);
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function configuracaoBonusAdesao()
    {
        return $this->belongsTo(ConfiguracaoBonus::class, 'configuracao_bonus_adesao_id');
    }

    public function configuracaoBonusRentabilidade()
    {
        return $this->belongsTo(ConfiguracaoBonus::class, 'onfiguracao_bonus_rentabilidade_id');
    }

    public function movimentos()
    {
        return $this->hasMany(Movimentos::class);
    }

    public function tituloSuperior()
    {
        return $this->belongsTo(self::class, 'titulo_superior');
    }

    public function maiorQue(Titulos $titulo)
    {
        Log::info("Camparando # $this->id com o titulo # $titulo->id");
        $superior = $titulo->tituloSuperior;
        if (!$superior) {
            return false;
        }
        return $superior->id == $this->id ? true : $this->maiorQue($superior);
    }

    public function setTituloSuperiorAttribute($value)
    {
        $this->attributes['titulo_superior'] = $value;

        if ($value == '0') {
            $this->attributes['titulo_superior'] = null;
        }
    }
}
