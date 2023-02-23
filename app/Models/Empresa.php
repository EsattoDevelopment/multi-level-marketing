<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresa';

    protected $fillable = [
        'logo1',
        'logo2',
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'inscricao_estadual',

        'nome_contato',
        'cpf_contato',
        'rg_contato',
        'telefone_contato',
        'email_contato',

        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'cep',
        'uf',

        'background',
        'logo',
        'cor',
        'site',
        'favicon',

        'logo_flutuante',
        'logo_email',
        'link_facebook',
        'link_instagram',

        'nome_termo_inicial',

        'background_manutencao',
    ];
}
