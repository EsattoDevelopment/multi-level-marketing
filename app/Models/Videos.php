<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Videos extends Model
{
    use SoftDeletes;

    protected $table = 'videos';

    protected $fillable = [
        'nome',
        'descricao',
        'codigo',
        'capa',
        'tipo',
        'categoria',
        'status',
        'referencia_titulos',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function videosTitulos()
    {
        return $this->hasMany(VideosTitulos::class, 'video_id', 'id');
    }
}
