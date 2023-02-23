<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItensPedido extends Model
{
    protected $table = 'itens_pedido';

    protected $fillable = [
            'name_item',
            'valor_unitario',
            'valor_total',
            'quantidade',
            'item_id',
            'pedido_id',
            'pontos_binarios',
            'quitar_com_bonus',
            'potencial_mensal_teto',
            'resgate_minimo',
            'total_dias_contrato',
            'total_meses_contrato',
            'resgate_minimo_automatico',
            'finaliza_contrato_automatico',
            'dias_carencia_transferencia',
            'dias_carencia_saque',
            'modo_recontratacao_automatica',
        ];

    public function pedido()
    {
        return $this->belongsTo(Pedidos::class, 'pedido_id');
    }

    //TODO verifica ronde esta sendo usado e trocar pelo metodo 'item'
    public function itens()
    {
        return $this->belongsTo(Itens::class, 'item_id')->withTrashed();
    }

    public function item()
    {
        return $this->belongsTo(Itens::class, 'item_id')->withTrashed();
    }
}
