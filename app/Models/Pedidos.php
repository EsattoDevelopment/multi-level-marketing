<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedidos extends Model
{
    use SoftDeletes;

    protected $table = 'pedidos';

    protected $fillable = [
       'status',
       'valor_total',
       'user_id_pagamento',
       'user_id',
       'data_compra',
       'data_fim',
       'tipo_pedido',
       'pedido_referencia_id',
       'pedido_id',
    ];

    protected $dates = ['created_at', 'data_compra', 'data_fim'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function itens()
    {
        return $this->hasMany(ItensPedido::class, 'pedido_id');
    }

    public function item()
    {
        return $this->belongsToMany(Itens::class, 'itens_pedido', 'pedido_id', 'item_id')->withTrashed()->first();
    }

    public function dadosPagamento()
    {
        return $this->hasOne(DadosPagamento::class, 'pedido_id');
    }

    /*    public function getDataCompraAttribute()
        {
            return Carbon::parse($this->attributes['data_compra'])->format('d/m/Y');
        }*/

    public function getDataCompraFormatadaAttribute()
    {
        return Carbon::parse($this->attributes['data_compra'])->format('d/m/Y');
    }

    public function boleto()
    {
        return $this->belongsTo(Boletos::class, 'boleto_id');
    }

    public function status()
    {
        return $this->belongsTo(PedidosStatus::class, 'status');
    }

    public function movimento()
    {
        return $this->hasOne(Movimentos::class, 'pedido_id')->orderBy('id');
    }

    public function ultimoMovimento()
    {
        $ultimoMovimento = $this->hasMany(Movimentos::class)->orderBy('id', 'desc')->limit(1)->get();

        $ultimoMovimento = $ultimoMovimento->first() instanceof Movimentos ? $ultimoMovimento->first() : null;

        return $ultimoMovimento;
    }

    //tras o movimento baseado no campo referencia, utilizando anteriormente
    public function movimentoRef()
    {
        return $this->hasOne(Movimentos::class, 'referencia')->orderBy('id');
    }

    public function movimentosInterno()
    {
        return $this->hasMany(PedidosMovimentos::class, 'pedido_id')->orderBy('id');
    }

    public function ultimoMovimentosInterno()
    {
        $ultimoMovimentoInterno = $this->hasMany(PedidosMovimentos::class, 'pedido_id')->orderBy('id', 'desc')->limit(1)->get();

        $ultimoMovimentoInterno = $ultimoMovimentoInterno->first() instanceof PedidosMovimentos ? $ultimoMovimentoInterno->first() : null;

        return $ultimoMovimentoInterno;
    }

    public static function totalGanhoPedidoMovimento($pedido_id):float
    {
        $totalGranho = PedidosMovimentos::where('pedido_id', $pedido_id)->whereNotIn('operacao_id', [26, 35])->sum('valor_manipulado');
        $totalEstorno = PedidosMovimentos::where('pedido_id', $pedido_id)->where('operacao_id', '=', 35)->sum('valor_manipulado');

        if ($totalGranho == null) {
            $totalGranho = 0;
        }

        if ($totalEstorno == null) {
            $totalEstorno = 0;
        }

        return decimal($totalGranho - $totalEstorno);
    }

    /**
     * @param $pedido_id
     * @return float
     */
    public static function totalGanhoPedidoMovimentoTransferidos($pedido_id):float
    {
        $totalGranho = PedidosMovimentos::where('pedido_id', $pedido_id)->where('operacao_id', '=', 26)->sum('valor_manipulado');

        if ($totalGranho == null) {
            $totalGranho = 0;
        }

        return decimal($totalGranho);
    }

    public function scopeContratos($query)
    {
        return $query->whereHas('itens', function ($query) {
            return $query->whereNotIn('item_id', [7, 8]);
        });
    }

    public function scopeDepositos($query)
    {
        return $query->whereTipoPedido(4);
    }

    public function scopePedidos($query)
    {
        return $query->where('tipo_pedido', '<>', 4);
    }
}
