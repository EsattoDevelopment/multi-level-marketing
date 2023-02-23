<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnderecosUsuarios extends Model
{
    protected $table = 'enderecos_usuario';

    protected $fillable = [
        'cep',
        'logradouro',
        'numero',
        'bairro',
        'cidade',
        'estado',
        'telefone1',
        'telefone2',
        'celular',
        'complemento',
        'user_id',
        'user_id_editor',
    ];

    public function enderecoEditor()
    {
        return $this->hasMany(EnderecosUsuariosEdit::class, 'enderecos_usuario_id');
    }

    public function lastEndereco()
    {
        return $this->enderecoEditor()->last();
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
