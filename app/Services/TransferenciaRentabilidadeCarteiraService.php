<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

use App\Models\Movimentos;
use App\Models\PedidosMovimentos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TransferenciaRentabilidadeCarteiraService
{
    private $pedido_id;
    private $responsavel_user_id;
    private $operacao_id;
    private $valor_manipulado;
    private $descricao_pedido_movimento;
    private $descicao_movimento;

    public function __construct()
    {
        $this->responsavel_user_id = 1;
        $this->operacao_id = 26;
    }

    public function pedidoId($pedido_id)
    {
        $this->pedido_id = $pedido_id;

        return $this;
    }

    public function responsavelUserId($responsavel_user_id)
    {
        $this->responsavel_user_id = $responsavel_user_id;

        return $this;
    }

    public function operacaoId($operacao_id)
    {
        $this->operacao_id = $operacao_id;

        return $this;
    }

    public function valorManipulado($valor_manipulado)
    {
        $this->valor_manipulado = $valor_manipulado;

        return $this;
    }

    public function descricaoPedidoMovimento($descricao_pedido_movimento)
    {
        $this->descricao_pedido_movimento = $descricao_pedido_movimento;

        return $this;
    }

    public function descicaoMovimento($descicao_movimento)
    {
        $this->descicao_movimento = $descicao_movimento;

        return $this;
    }

    public function transferirParaContaDigital()
    {
        $sucesso = false;

        Log::info('##############################################################################');
        Log::info('#### TransferenciaRentabilidadeCarteiraService - Inicio                    ###');
        Log::info('##############################################################################');
        Log::info('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>');
        Log::info("Pedido:{$this->pedido_id}");

        DB::beginTransaction();
        try {
            $ultimoPedidoMovimento = PedidosMovimentos::ultimoPedidoMovimentoPedidoId($this->pedido_id);

            if (decimal($ultimoPedidoMovimento->saldo) > 0 && decimal($ultimoPedidoMovimento->saldo) >= decimal($this->valor_manipulado)) {
                $dadosPedidoMovimento = [
                    'valor_manipulado' => $this->valor_manipulado,
                    'saldo_anterior' => $ultimoPedidoMovimento->saldo,
                    'saldo' => decimal($ultimoPedidoMovimento->saldo - $this->valor_manipulado),
                    'status' => 1,
                    'descricao' => $this->descricao_pedido_movimento,
                    'pedido_id' => $ultimoPedidoMovimento->pedido_id,
                    'item_id' => $ultimoPedidoMovimento->item_id,
                    'titulo_id' => $ultimoPedidoMovimento->titulo_id,
                    'user_id' => $ultimoPedidoMovimento->user_id,
                    'operacao_id' => $this->operacao_id,
                    'responsavel_user_id' => $this->responsavel_user_id,
                ];

                Log::info('$dadosPedidoMovimento: ', $dadosPedidoMovimento);

                $ultimoPedidoMovimento = PedidosMovimentos::create($dadosPedidoMovimento);

                $ultimoMovimento = Movimentos::ultimoMovimentoUserId($ultimoPedidoMovimento->user_id);

                $dadosMovimento = [
                    'valor_manipulado' => $ultimoPedidoMovimento->valor_manipulado,
                    'saldo_anterior' => $ultimoMovimento ? $ultimoMovimento->saldo : 0,
                    'saldo' => $ultimoMovimento ? decimal($ultimoMovimento->saldo + $ultimoPedidoMovimento->valor_manipulado) : $ultimoPedidoMovimento->valor_manipulado,
                    'descricao' => $this->descicao_movimento,
                    'responsavel_user_id' => $this->responsavel_user_id,
                    'user_id' => $ultimoPedidoMovimento->user_id,
                    'operacao_id' => $this->operacao_id,
                    'pedido_id' => $ultimoPedidoMovimento->pedido_id,
                    'item_id' => $ultimoPedidoMovimento->item_id,
                    'titulo_id' => $ultimoPedidoMovimento->titulo_id,
                ];

                Log::info('$dadosMovimento:', $dadosMovimento);

                Movimentos::create($dadosMovimento);
                Log::info('------------------------------------------------------------------------------');

                $sucesso = true;
            } else {
                Log::error('Saldo insuficiente, transferencia abortada');
                Log::info('pedido_id: '.$this->pedido_id);
                Log::info('Saldo pedidos_movimentos: '.$ultimoPedidoMovimento->saldo);
                Log::info('Valor da transferencia: '.$this->valor_manipulado);
            }

            if ($sucesso) {
                DB::commit();
            } else {
                DB::rollback();
            }
        } catch (ModelNotFoundException $e) {
            Log::error('erro ao processar TransferenciaRentabilidadeCarteiraService::transferirParaContaDigital'.$e);
            DB::rollback();
        }
        Log::info('<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
        Log::info('##############################################################################');
        Log::info('#### TransferenciaRentabilidadeCarteiraService - Fim                       ###');
        Log::info('##############################################################################');

        return $sucesso;
    }
}
