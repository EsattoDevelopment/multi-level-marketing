<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use App\Saude\Domains\Mensalidade;
use Illuminate\Database\Eloquent\Model;

class PedidosMovimentos extends Model
{
    protected $fillable = [
        'valor_manipulado',
        'saldo_anterior',
        'saldo',
        'descricao',
        'status',
        'pedido_id',
        'item_id',
        'user_id',
        'operacao_id',
        'pedido_referencia_id',
        'responsavel_user_id',
        'mensalidade_id',
        'rentabilidade_id',
        'movimento_id',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function pedido()
    {
        return $this->belongsTo(Pedidos::class, 'pedido_id');
    }

    public function item()
    {
        return $this->belongsTo(Itens::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function operacao()
    {
        return $this->belongsTo(Operacoes::class, 'operacao_id');
    }

    public function reponsavel()
    {
        return $this->belongsTo(User::class, 'responsavel_user_id');
    }

    public function mensalidade()
    {
        return $this->belongsTo(Mensalidade::class, 'mensalidade_id');
    }

    public function rentabilidade()
    {
        return $this->belongsTo(Rentabilidade::class);
    }

    public static function ultimoPedidoMovimentoPedidoId($pedidoId)
    {
        $ultimoPedidoMovimento = self::wherePedidoId($pedidoId)->orderBy('id', 'desc')->limit(1)->get();

        $ultimoPedidoMovimento = $ultimoPedidoMovimento->first() instanceof self ? $ultimoPedidoMovimento->first() : null;

        return $ultimoPedidoMovimento;
    }
}
