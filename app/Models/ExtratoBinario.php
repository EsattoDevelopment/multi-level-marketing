<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ExtratoBinario extends Model
{
    protected $table = 'extrato_binario';

    protected $fillable = [
        'pontos',
        'saldo_anterior',
        'saldo',
        'referencia',
        'acumulado_direita',
        'acumulado_esquerda',
        'acumulado_total',
        'saldo_direita',
        'saldo_esquerda',
        'user_id',
        'operacao_id',
        'operacao_id',
        'user_responsavel',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function operacao()
    {
        return $this->belongsTo(Operacoes::class, 'operacao_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }
}
