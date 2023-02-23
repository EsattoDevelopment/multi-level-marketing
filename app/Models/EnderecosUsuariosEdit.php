<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnderecosUsuariosEdit extends Model
{
    protected $table = 'enderecos_usuario_editor';

    protected $fillable = ['cep', 'logradouro', 'numero', 'bairro', 'cidade', 'estado', 'telefone1', 'telefone2', 'celular', 'complemento', 'user_id', 'user_id_editor', 'enderecos_usuario_id'];

    public function fromEndereco()
    {
        return $this->belongsTo(EnderecosUsuarios::class, 'id', 'enderecos_usuario_id');
    }
}
