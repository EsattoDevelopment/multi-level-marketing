<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Domains\Configuracao;

use Illuminate\Database\Eloquent\Model;

class Configuracao extends Model
{
    protected $table = 'configuracao_sistema';

    protected $fillable = [
        'profundidade_unilevel',
        'bonus_milha_cadastro',
        'bonus_ciclo_hotel',
        'custo_hotel',
        'milhas_ciclo_hotel',
        'validade_milhas_ciclo_hotel',
        'diretos_qualificacao',
    ];
}
