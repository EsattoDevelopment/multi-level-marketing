<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContasEmpresa extends Model
{
    protected $table = 'contas_empresa';

    protected $fillable = [
        'usar_boleto',
        'banco_id',
        'logo_empresa',
        'dataVencimento',
        'multa',
        'juros',
        'juros_apos',
        'diasProtesto',
        'agencia',
        'agenciaDv',
        'conta',
        'contaDv',
        'carteira',
        'convenio',
        'variacaoCarteira',
        'range',
        'codigoCliente',
        'ios',
        'msg1',
        'msg2',
        'msg3',
        'msg4',
        'msg5',
        'inst1',
        'inst2',
        'inst3',
        'inst4',
        'inst5',
        'aceite',
        'especieDoc',
        'status',
        'recebe_ted',
        'favorecido',
        'cpfcnpj',
    ];

    public function banco()
    {
        return $this->belongsTo(Bancos::class);
    }

    public function getUsarBoletoStringAttribute($value)
    {
        if ($this->attributes['usar_boleto']) {
            return 'Sim';
        }

        return 'Não';
    }
}
