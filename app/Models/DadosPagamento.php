<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DadosPagamento extends Model
{
    protected $table = 'dados_pagamento';

    protected $fillable = [
            'valor',
            'data_vencimento',
            'data_pagamento',
            'status',
            'documento',
            'pedido_id',
            'metodo_pagamento_id',
            'responsavel_user_id',
            'valor_efetivo',
            'valor_real',
            'valor_efetivo_real',
            'cotacao_dolar_dia_compra',
            'cotacao_dolar_dia_efetivo',
            'data_pagamento_efetivo',
            'valor_autorizado_diretoria',
            'invoice_id',
            'dados_boleto',
            'tarifa_boleto',
            'data_geracao_boleto',
            'taxa_valor',
            'ultimo_request_astropay',
            'dados_pagamento',
        ];

    protected $dates = ['data_pagamento', 'data_vencimento', 'data_pagamento_efetivo', 'data_geracao_boleto', 'ultimo_request_astropay'];
    protected $casts = [
        'dados_boleto' => 'array',
        'dados_pagamento' => 'array',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedidos::class, 'pedido_id');
    }

    public function setDadosBoletoAttribute($value)
    {
        $this->attributes['dados_boleto'] = json_encode($value);
    }

    public function getDataVencimentoFormatadaAttribute()
    {
        return Carbon::parse($this->attributes['data_vencimento'])->format('d/m/Y');
    }

    /* public function getDataPagamentoAttribute()
     {
         return Carbon::parse($this->attributes['data_pagamento'])->format('d/m/Y');
     }*/

    /*    public function getDataPagamentoEfetivoAttribute()
        {
            return Carbon::parse($this->attributes['data_pagamento'])->format('d/m/Y');
        }*/

    public function metodoPagamento()
    {
        return $this->belongsTo(MetodoPagamento::class, 'metodo_pagamento_id');
    }

    public function responsavel()
    {
        return $this->belongsTo(User::class, 'responsavel_user_id');
    }
}
