<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Galeria extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'galerias';

    /**
     * The loaded relationships for the model.
     *
     * @var array
     */
    protected $relations = [
        'pacotes',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'description', 'galeria_tipo_id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Retorna um objeto do  Galeria_Tipo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function galeria_tipo()
    {
        return $this->belongsTo(Galeria_Tipo::class);
    }

    /**
     * Retorna as fotos da galeria.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function imagens()
    {
        return $this->hasMany(GaleriaImagens::class)->orderBy('ordem');
    }

    public function imagensCount()
    {
        return $this->imagens()->get()->count();
    }

    public function imagemPrincipal()
    {
        $imagem = $this->imagens()->where('principal', 1)->get();

        if ($imagem->count() > 0) {
            return $imagem->first();
        }

        return $this->imagens->sortBy('ordem')->first();
    }

    /**
     * Retorna o valor maximo da ordem.
     *
     * @return mixed
     */
    public function maxOrdem()
    {
        return $this->imagens()->get()->sortByDesc('ordem')->first()->ordem;
    }

    /**
     * Retorna Noticia ligada a galeria.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function pacote()
    {
        return $this->hasOne(Pacotes::class);
    }
}
