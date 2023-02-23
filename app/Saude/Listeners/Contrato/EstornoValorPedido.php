<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Listeners\Contrato;

use App\Models\Movimentos;
use Illuminate\Support\Facades\Auth;
use App\Saude\Events\CancelamentoContrato;

class EstornoValorPedido
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CancelamentoContrato  $event
     * @return void
     */
    public function handle(CancelamentoContrato $event)
    {
        \Log::info('$$$$$ Inicio estorno Valor Pedido');

        if (7 == $event->getContato()->status) {
            $item = $event->getContato()->item;

            //TODO só tem bonus se o pedido for do tipo adesão
            if (1 == $item->tipo_pedido_id) {
                $pedido = $event->getContato()->pedido;

                $dadosPagamento = $pedido->dadosPagamento;

                $dadosMovimento = [
                    'valor_manipulado'    => -$dadosPagamento->valor,
                    'saldo_anterior'      => 0,
                    'saldo'               => 0,
                    'pedido_id'           => $pedido->id,
                    'descricao'           => 'Estorno de cancelamento de contrato #'.$event->getContato()->id,
                    'responsavel_user_id' => Auth::user()->id,
                    'user_id'             => $pedido->user_id,
                    'operacao_id'         => 23, //Estorno
                ];

                Movimentos::create($dadosMovimento);
            } else {
                \Log::info('Não há nada para estornar');
            }
        } else {
            \Log::info('Não foi cancelado dentro do prazo');
        }
        \Log::info('$$$$$ Fim estorno Valor Pedido');
    }
}
