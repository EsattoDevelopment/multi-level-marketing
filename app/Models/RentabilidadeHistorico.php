<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RentabilidadeHistorico extends Model
{
    //use SoftDeletes;

    protected $table = 'rentabilidade_historico';

    protected $fillable = [
        'titulo',
        'descricao',
        'path_imagem',
        'data',
        'user_id',
        'status',
        'path_documento',
        'plataforma_conta_id',
        'valor',
        'percentual',
    ];

    protected $dates = [
        'data',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function plataformaconta()
    {
        return $this->belongsTo(PlataformaConta::class, 'plataforma_conta_id');
    }

    public function plataforma()
    {
        return $this->plataformaconta()->first()->plataforma;
    }

    public function getDataAttribute()
    {
        return Carbon::parse($this->attributes['data'])->format('d/m/Y');
    }

    public function setDataAttribute($value)
    {
        return $this->attributes['data'] = implode('-', array_reverse(explode('/', $value)));
    }
}
