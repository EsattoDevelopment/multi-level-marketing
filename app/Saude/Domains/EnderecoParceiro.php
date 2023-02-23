<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Domains;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EnderecoParceiro.
 */
class EnderecoParceiro extends Model
{
    protected $table = 'endereco_parceiro';

    public $timestamps = true;

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
        'parceiro_id',
    ];

    protected $guarded = [];
}
