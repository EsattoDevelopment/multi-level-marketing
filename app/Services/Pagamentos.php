<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

use Carbon\Carbon;
use App\Models\Pedidos;
use App\Models\Sistema;
use App\Models\Movimentos;
use App\Events\PedidoFoiPago;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Pagamentos
{
    private $sistema;
    private $pedido;
    private $usuario;

    public function __construct(Pedidos $pedido)
    {
        $this->sistema = Sistema::findOrFail(1);
        $this->pedido = $pedido;
        $this->usuario = Auth::user() ?? $pedido->user;
    }

    public function efetivarPagamento()
    {
        try {
            Log::info('@@@@@@@@@@@@@@@@@@@  Rodar sistema apos pagamento @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@');
            $respostaEventos = \Event::fire(new PedidoFoiPago($this->pedido));

            $count = 0;

            foreach ($respostaEventos as $key => $respostas) {
                if (! $respostas) {
                    Log::info('Erro no evento #'.$key);
                    $count++;
                }
            }

            return $count;
        } catch (ModelNotFoundException $e) {
            Log::error('Erro ao efetivar pagamento!');

            return 1;
        }
    }

    public function pagarComSaldo()
    {
        Log::info('Entrou pagar pedido #'.$this->pedido->id);
        try {
            DB::beginTransaction();

            Log::info('Pagamento Saldo, saldo de #'.$this->usuario->id);

            $this->pedido->load('dadosPagamento', 'user', 'itens');

            if (in_array($this->pedido->status, [1, 4])) {
                if (in_array($this->pedido->getRelation('dadosPagamento')->status, [0, 1, 4])) {
                    $movimento = Movimentos::whereUserId($this->usuario->id)->orderBy('id')->get();

                    if (! $movimento) {
                        DB::rollBack();
                        flash()->success('Não há saldo suficiente para realizar o pagamento!');
                        Log::info('Não há saldo suficiente para realizar o pagamento!');

                        return false;
                    }

                    $movimento = $movimento->last();

                    Log::info('Saldo antes pagamento, '.$this->sistema->moeda.$movimento->saldo);
                    Log::info('Valor pedido, '.$this->sistema->moeda.$this->pedido->valor_total);
                    Log::info('Movimento antes pagamento', $movimento->toArray());
                    // pagamento dos bonus
                    $dadosMovimento = [
                        'valor_manipulado'    => $this->pedido->valor_total,
                        'saldo_anterior'      => $movimento->saldo,
                        'saldo'               => $movimento->saldo - $this->pedido->valor_total,
                        'pedido_id'          => $this->pedido->id,
                        'documento'           => '',
                        'descricao'           => 'Pagamento do Contrato nº '.$this->pedido->id.' - '.$this->pedido->item()->name,
                        'responsavel_user_id' => $this->usuario->id,
                        'user_id'             => $this->usuario->id,
                        'operacao_id'         => 12,
                    ];

                    $movimentoAtual = Movimentos::create($dadosMovimento);
                    Log::info('Saldo apos pagamento, '.$this->sistema->moeda.$movimentoAtual->saldo);
                    Log::info('Movimento apos pagamento', $movimentoAtual->toArray());

                    $this->pedido->dadosPagamento->documento = 'Pagamento de pedido com bonus, por user#'.$this->usuario->id;
                    $this->pedido->dadosPagamento->status = 2;
                    $this->pedido->dadosPagamento->valor_autorizado_diretoria = $this->pedido->valor_total;
                    $this->pedido->dadosPagamento->valor_efetivo = $this->pedido->valor_total;
                    $this->pedido->dadosPagamento->metodo_pagamento_id = 6; //liberação sistema
                    $this->pedido->dadosPagamento->data_pagamento = Carbon::now()->format('Y-m-d H:i:s');
                    $this->pedido->dadosPagamento->data_pagamento_efetivo = Carbon::now()->format('Y-m-d H:i:s');
                    $this->pedido->dadosPagamento->responsavel_user_id = $this->usuario->id;
                    $this->pedido->dadosPagamento->save();

                    $this->pedido->status = 2;
                    $this->pedido->save();

                    $erros = self::efetivarPagamento();

                    $retorno = self::verificaErros($erros);

                    if (! $retorno) {
                        Log::info('Houve erros no pagamento');
                        Log::info('');
                        flash()->error('Houve alguns erros no processamento do pagamento!');

                        return false;
                    }

                    Log::info('pagamento OK #'.$this->pedido->id);

                    flash()->success('Pedido #'.$this->pedido->id.' pago com sucesso');

                    if (in_array($this->usuario->id, [2])) {
                        return true;
                    } elseif ($this->usuario->id == $this->pedido->user_id) {
                        if ($this->pedido->tipo_pedido === 4) {
                            return true;
                        }

                        return true;
                    }

                    return true;
                }

                Log::info('Pedido não esta aguardando mais o pagamento 2');
                flash()->success('Pedido não esta aguardando mais o pagamento!');

                return true;
            }

            Log::info('Pedido não esta aguardando mais o pagamento 1');
            flash()->success('Pedido não esta aguardando mais o pagamento!');

//            return redirect()->back();
            return false;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            flash()->error('Desculpe, erro ao pagar o pedido. Tente novamente, se o erro persistir entre em contato conosco!');

            Log::info('Erro ao pagar pedido', ['user' => $this->usuario->id]);

//            return redirect()->back();
            return false;
        }
    }

    private function verificaErros($erros)
    {
        if ($erros > 0) {
            DB::rollback();

            return false;
        }

        Log::info('Transação efetivada!');
        DB::commit();

        return true;
    }
}
