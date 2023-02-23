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
use App\Models\ItensPedido;
use App\Models\DadosPagamento;
use Illuminate\Support\Facades\DB;
use App\Models\PedidoRecontratados;
use Illuminate\Support\Facades\Log;
use App\Notifications\EmailRecontratacao;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Recontratacao
{
    private $usuario;
    private $titulo;
    private $item;
    private $pedidoItem;
    private $pedido;
    private $pedidoNovo;
    private $dadosPagamento;
    private $valorFimContrato;
    private $valorNovoContrato;
    private $consultorQuitarComBonus;
    private $pedidoMovimentoAtualizado;
    private $notificacoes;

    private $sistema;

    public function __construct()
    {
        $this->pedidoMovimentoAtualizado = true;
        $this->sistema = (object) Sistema::findOrFail(1);
        $this->notificacoes = [];
    }

    public function pedidoId($pedido_id)
    {
        $this->pedido = Pedidos::with('itens', 'dadosPagamento', 'usuario')->find($pedido_id);
        $this->usuario = $this->pedido->user;
        $this->titulo = $this->usuario->titulo;
        $this->pedidoItem = $this->pedido->itens->first();
        $this->dadosPagamento = $this->pedido->dadosPagamento;
        $this->item = $this->pedido->item();

        return $this;
    }

    public function processar()
    {
        $sucesso = false;

        Log::info('##############################################################################');
        Log::info('#### Recontratacao - Inicio                                                ###');
        Log::info('##############################################################################');
        Log::info('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>');
        Log::info("Pedido:{$this->pedido->id}");

        DB::beginTransaction();
        try {
            if ($this->item->ativo && $this->pedido->status == 6 && $this->pedidoItem->modo_recontratacao_automatica > 0) {
                $ultimoMovimento = Movimentos::ultimoMovimentoUserId($this->usuario->id);

                $this->consultorQuitarComBonus = ($this->pedidoItem->quitar_com_bonus && $this->titulo->habilita_rede);
                $this->valorFimContrato = round($this->pedido->valor_total * $this->pedidoItem->total_meses_contrato * ($this->pedidoItem->potencial_mensal_teto / 100), 2);
                $this->valorFimContrato += round($this->pedido->valor_total, 2);

                if ($this->pedidoItem->modo_recontratacao_automatica == config('constants.modo_recontratacao_automatica')['saldo_final_contrato']) {
                    $this->valorNovoContrato = $this->valorFimContrato;
                } else {
                    $this->valorNovoContrato = $this->pedido->valor_total;
                }

                if ($ultimoMovimento && $ultimoMovimento->saldo >= $this->valorNovoContrato) {
                    $sucesso = self::criarNovoContrato();
                }

                if ($sucesso) {
                    DB::commit();
                    Notification::send($this->usuario, new EmailRecontratacao($this->pedido, $this->pedidoNovo));
                } else {
                    DB::rollback();
                    $this->pedidoItem->modo_recontratacao_automatica = config('constants.modo_recontratacao_automatica')['abortada_sem_saldo'];
                    $this->pedidoItem->save();
                }
            }
        } catch (ModelNotFoundException $e) {
            Log::error('Serviço de renovação de contratos - Erro ao processar Recontratacao::processar'.$e);
            DB::rollback();
        }

        Log::info('<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
        Log::info('##############################################################################');
        Log::info('#### Recontratacao - Fim                                                   ###');
        Log::info('##############################################################################');

        return $sucesso;
    }

    private function criarNovoContrato()
    {
        $sucesso = false;

        $dados['status'] = 1;
        $dados['valor_total'] = $this->valorNovoContrato;
        $dados['user_id'] = $this->usuario->id;
        $dados['tipo_pedido'] = $this->item->tipo_pedido_id;
        $dados['data_compra'] = Carbon::now();

        $this->pedidoNovo = Pedidos::create($dados);

        $dadoItenPedido = [
            'pedido_id' => $this->pedidoNovo->id,
            'item_id' => $this->item->id,
            'name_item' => $this->item->name,
            'pontos_binarios' => $this->item->pontos_binarios,
            'valor_unitario' => round($this->item->valor, 2),
            'valor_total' => round($this->valorNovoContrato, 2),
            'quantidade' => round($this->valorNovoContrato / $this->item->valor, 2),

            'quitar_com_bonus' => $this->item->quitar_com_bonus,
            'potencial_mensal_teto' => $this->item->potencial_mensal_teto,
            'resgate_minimo' => $this->item->resgate_minimo,
            'total_dias_contrato' => $this->item->contrato,
            'total_meses_contrato' => $this->item->meses,
            'resgate_minimo_automatico' => $this->item->resgate_minimo_automatico,
            'finaliza_contrato_automatico' => $this->item->finaliza_contrato_automatico,
            'dias_carencia_transferencia' => $this->item->dias_carencia_transferencia,
            'dias_carencia_saque' => $this->item->dias_carencia_saque,
            'modo_recontratacao_automatica' => $this->pedidoItem->modo_recontratacao_automatica,
        ];

        ItensPedido::create($dadoItenPedido);

        DadosPagamento::create([
            'valor'           => $this->pedidoNovo->valor_total,
            'status'          => 0,
            'pedido_id'       => $this->pedidoNovo->id,
            'data_vencimento' => Carbon::now()->addWeekday(5),
        ]);

        $sucesso = (new Pagamentos($this->pedidoNovo))->pagarComSaldo();

        if ($sucesso) {
            $recontratacao = [
                'pedido_id_finalizado' => $this->pedido->id,
                'pedido_id_recontratado' => $this->pedidoNovo->id,
                'modo_recontratacao_automatica' => $this->pedidoItem->modo_recontratacao_automatica,
                'item_id' => $this->item->id,
                'user_id' => $this->usuario->id,
            ];

            $pedidoRecontratado = PedidoRecontratados::create($recontratacao);
            Log::info("ID: {$pedidoRecontratado->id} Recontratacao:", $recontratacao);
        }

        return $sucesso;
    }
}
