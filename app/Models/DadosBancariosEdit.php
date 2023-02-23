<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DadosBancariosEdit extends Model
{
    protected $table = 'dados_bancarios_editor';

    protected $fillable = [
        'banco',
        'agencia',
        'agencia_digito',
        'conta',
        'conta_digito',
        'user_id',
        'user_id_editor',
        'dados_bancarios_id',
        'tipo_conta',
        'receber_bonus',
        'banco_id',
    ];

    public function DadosEdit()
    {
        return $this->belongsTo(self::class, 'id', 'dados_bancarios_id');
    }
}
