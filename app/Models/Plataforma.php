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

class Plataforma extends Model
{
    //use SoftDeletes;

    protected $table = 'plataforma';

    protected $fillable = [
        'nome',
        'descricao',
        'status',
    ];

    public function contas()
    {
        return $this->hasMany(PlataformaConta::class);
    }

    public function contasCount()
    {
        return $this->contas()->get()->count();
    }

    /*public function getDataAttribute()
    {
        return Carbon::parse($this->attributes['data'])->format('d/m/Y');
    }

    public function setDataAttribute($value)
    {
        return $this->attributes['data'] = implode('-', array_reverse(explode('/', $value)));
    }*/
}
