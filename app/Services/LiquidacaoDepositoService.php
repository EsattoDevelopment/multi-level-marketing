<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

use App\Models\User;
use App\Models\Itens;
use App\Models\Movimentos;
use App\Models\ItensPedido;
use App\Models\PedidosMovimentos;
use Illuminate\Support\Facades\Auth;

class LiquidacaoDepositoService
{
    private $movimento;
    private $item;
    private $itemPedido;
    private $userDeposito;
    private $usuario;

    /**
     * @param PedidosMovimentos $movimento
     * @return $this
     */
    public function movimento(PedidosMovimentos $movimento)
    {
        $this->movimento = $movimento;

        return $this;
    }

    /**
     * @param User $userDeposito
     * @return $this
     */
    public function userDeposito(User $userDeposito)
    {
        $this->userDeposito = $userDeposito;

        return $this;
    }

    /**
     * @param User $usuario
     * @return $this
     */
    public function usuario(User $usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * @param Itens $item
     * @return $this
     */
    public function item(Itens $item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * @param ItensPedido $itemPedido
     * @return LiquidacaoDepositoService $this
     */
    public function itemPedido(ItensPedido $itemPedido)
    {
        $this->itemPedido = $itemPedido;

        return $this;
    }

    public function calcular():void
    {
        $dadosPagamento = [
                    'valor_manipulado' => $this->movimento->valor_manipulado,
                    'saldo_anterior' => $this->movimento->saldo,
                    'saldo' => round($this->movimento->saldo - $this->movimento->valor_manipulado, 2),
                    'status' => 1,
                    'descricao' => "Tranferência automática deste bônus Crédito ref. Doc #{$this->itemPedido->pedido_id} para sua Carteira",
                    'pedido_id' => $this->movimento->pedido_id,
                    'pedido_referencia_id' => $this->movimento->pedido_referencia_id,
                    'item_id' => $this->movimento->item_id,
                    'user_id' => $this->movimento->user_id,
                    'operacao_id' => 26,
                    'responsavel_user_id' => \Auth::user()->id,
                ];

        PedidosMovimentos::create($dadosPagamento);

        $ultimoMovimento = Movimentos::whereUserId($this->usuario->id)
                    ->orderBy('id', 'desc')
                    ->take(1)
                    ->first();

        $dadosMovimento = [
                    'valor_manipulado' =>  $this->movimento->valor_manipulado,
                    'saldo_anterior' => ! $ultimoMovimento ? 0 : $ultimoMovimento->saldo,
                    'saldo' => ! $ultimoMovimento ? $this->movimento->valor_manipulado : $this->movimento->valor_manipulado + $ultimoMovimento->saldo,
                    'pedido_id' => $this->itemPedido->pedido_id,
                    'descricao' => "Tranferência automática da capitalizaçao Doc #{$this->movimento->pedido_id} para sua Carteira",
                    'responsavel_user_id' => Auth::user()->id,
                    'user_id' => $this->usuario->id,
                    'item_id' => $this->itemPedido->item_id,
                    'titulo_id' => $this->usuario->titulo->id,
                    'operacao_id' => 26,
                ];

        \log::info("Pago bonus direto para #{$this->usuario->id} - {$this->usuario->name}", $dadosMovimento);

        Movimentos::create($dadosMovimento);
    }
}
