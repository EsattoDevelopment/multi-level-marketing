<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Responsaveis extends Model
{
    protected $table = 'responsaveis';

    protected $fillable = [
        'nome',
        'email',
        'cpf',
        'rg',
        'data_nasc',
        'estado_civil',
        'telefone',
        'status',
        'user_id',
        'selfie',
        'documento',
        'status_documento',
        'status_selfie',
        'documento_representacao',
        'status_documento_representacao',
    ];

    protected $dates = ['data_nasc', 'created_at'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getNascimentoAttribute()
    {
        return Carbon::parse($this->attributes['data_nasc'])->format('d/m/Y');
    }
}
