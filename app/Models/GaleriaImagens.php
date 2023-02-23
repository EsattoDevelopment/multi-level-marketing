<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GaleriaImagens extends Model
{
    protected $table = 'galerias_imagens';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'galeria_id', 'ordem', 'principal', 'legenda'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function galeria()
    {
        return $this->belongsTo(Galeria::class);
    }

    /**
     * Retorna a imagem setada como principal.
     *
     * @param $query
     * @return mixed
     */
    public function scopePrincipal($query)
    {
        return $query->where('principal', 1);
    }

    public function getImagemAttribute()
    {
        return $this->attributes['caminho'].$this->attributes['name'];
    }
}
