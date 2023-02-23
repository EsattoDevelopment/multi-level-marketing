<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners;

use Log;
use App\Models\Movimentos;
use App\Events\PedidoFoiPago;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendPedidoConfirmadoEmail;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PagaDeposito
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
     * @param  PedidoFoiPago $event
     *
     * @return bool
     */
    public function handle(PedidoFoiPago $event)
    {
        Log::info('############# Entrou pagar depósito #############');

        $pedido = $event->getPedido();

        if ($pedido->tipo_pedido === 4) {
            try {
                //resgata ultima movimentação
                $ultimoMovimento = Movimentos::ultimoMovimentoUserId($pedido->user->id);

                $dadosMovimento = [
                    'valor_manipulado' => $pedido->valor_total,
                    'saldo_anterior' => ! $ultimoMovimento ? 0 : $ultimoMovimento->saldo,
                    'saldo' => ! $ultimoMovimento ? $pedido->valor_total : $pedido->valor_total + $ultimoMovimento->saldo,
                    'pedido_id' => $pedido->id,
                    //'documento' => $dadospagamento->id,
                    'descricao' => 'Depósito #'.$pedido->id,
                    'responsavel_user_id' => Auth::user() ? Auth::user()->id : 1,
                    'user_id' => $pedido->user->id,
                    'item_id' => $pedido->item()->id,
                    'titulo_id' => $pedido->user->titulo->id,
                    'operacao_id' => 32,
                ];

                Movimentos::create($dadosMovimento);

                Log::info('Valor depositado: '.$pedido->valor_total.' para #'.$pedido->user->id.' - '.$pedido->user->name);

                /*
                 * disparar pedido confirmado
                 */
                dispatch(new SendPedidoConfirmadoEmail($pedido));
            } catch (ModelNotFoundException $e) {
                Log::info('############# Falhou pagar deposito #############');

                return false;
            }
        }

        Log::info('############# Terminou de pagar depósito. #############');

        return true;
    }
}
