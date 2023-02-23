<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodoPagamento extends Model
{
    protected $table = 'metodo_pagamento';

    protected $fillable = [
           'name',
           'configuracao',
           'status',
           'nome_codigo_conta',
           'codigo_carteira',
           'taxa_descricao',
           'taxa_valor',
           'taxa_porcentagem',
           'usar_deposito',
           'taxa_porcentagem',
           'usar_deposito',
           'usar_item',
    ];

    protected $casts = [
        'configuracao' => 'array',
    ];

    /*
    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function tituloSuperior()
    {
        return $this->belongsTo(Titulos::class, 'titulo_superior')->first();
    }

    public function tituloMaiorQueDoUsuario(Titulos $titulo, Titulos $tituloUsuario)
    {
        Log::info('Campara titulo do usuario com o titulo a ser subido');

        $superior = $tituloUsuario->tituloSuperior;

        if($superior) {
            if ($superior->id == $titulo->id) {
                return $titulo;
            } else {
                return $this->tituloMaiorQueDoUsuario($titulo, $superior);
            }
        }else{
            return false;
        }
    }

    public function setTituloSuperiorAttribute($value)
    {
        if(empty($value)) {
            $this->attributes['titulo_superior'] = null;
        }else{
            $this->attributes['titulo_superior'] = $value;
        }
    } */

    public function setConfiguracaoAttribute($value)
    {
        $this->attributes['configuracao'] = json_encode($value);
    }
}
