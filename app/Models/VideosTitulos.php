<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideosTitulos extends Model
{
    protected $table = 'videos_titulos';

    protected $fillable = [
        'video_id',
        'titulo_id',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function videos()
    {
        return $this->hasOne(Videos::class, 'id', 'video_id');
    }

    public function titulos()
    {
        return $this->hasOne(Titulos::class, 'id', 'titulo_id');
    }
}
