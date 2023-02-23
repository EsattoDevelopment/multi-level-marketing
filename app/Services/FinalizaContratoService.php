<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Pedidos;
use App\Models\Sistema;
use App\Models\Movimentos;
use App\Notifications\LogSlack;
use App\Models\PedidosMovimentos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmailFinalizacaoContrato;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FinalizaContratoService
{
    private $usuario;
    private $titulo;
    private $pedidoItem;
    private $pedido;
    private $dadosPagamento;
    private $valorFimContrato;
    private $valorTransferido;
    private $valorCapitalizado;
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

        return $this;
    }

    private function atualizarValorCapitalizado()
    {
        $this->valorCapitalizado = Pedidos::totalGanhoPedidoMovimento($this->pedido->id);
    }

    private function atualizarValorTransferido()
    {
        if ($this->pedidoMovimentoAtualizado) {
            $this->valorTransferido = Pedidos::totalGanhoPedidoMovimentoTransferidos($this->pedido->id);
            $this->pedidoMovimentoAtualizado = false;
        }
    }

    private function inserirNotificacao($notificacao)
    {
        $this->notificacoes[count($this->notificacoes)] = $notificacao;
    }

    public function finalizarContrato()
    {
        $sucesso = false;

        Log::info('##############################################################################');
        Log::info('#### FinalizaContratoService - Inicio                                      ###');
        Log::info('##############################################################################');
        Log::info('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>');
        Log::info("Pedido:{$this->pedido->id}");

        DB::beginTransaction();
        try {
            $this->consultorQuitarComBonus = ($this->pedidoItem->quitar_com_bonus && $this->titulo->habilita_rede);

            $this->valorFimContrato = decimal($this->pedido->valor_total * $this->pedidoItem->total_meses_contrato * ($this->pedidoItem->potencial_mensal_teto / 100));
            $this->atualizarValorTransferido();

            Log::info('$valorFimContrato: '.$this->valorFimContrato);
            Log::info('$consultorQuitarComBonus: '.($this->consultorQuitarComBonus ? 'Sim' : 'Não'));

            if ($this->consultorQuitarComBonus) {
                $this->valorFimContrato += decimal($this->pedido->valor_total);
                Log::info('$valorFimContrato atualizado: '.$this->valorFimContrato);
            }

            if (Carbon::now()->setTime(0, 0, 0) > Carbon::parse($this->dadosPagamento->data_pagamento_efetivo->format('Y-m-d'))->addDay($this->pedidoItem->total_dias_contrato)) {
                if ($this->pedidoItem->total_meses_contrato == null || $this->pedidoItem->total_meses_contrato == 0) {
                    $dataInicio = $this->dadosPagamento->data_pagamento_efetivo;
                    $dataFim = Carbon::parse($this->dadosPagamento->data_pagamento_efetivo->format('Y-m-d'))->addDay($this->pedidoItem->total_dias_contrato);

                    $this->pedidoItem->total_meses_contrato = $dataInicio->diffInMonths($dataFim);
                }

                /*
                 * Valida e faz o estorno do valor excedido
                 */
                self::processarEstornoExcedido();

                /*
                 * Valida e transfere rentabilidades não transferidas caso não resulte em um valor total transferido superior
                 * ao valor final do contrato
                 */
                self::processarRentabilidadeRemanescente();

                /*
                 * valida e ajusta o movimento caso o valor capitalizado supere o valor transferido onde a diferença do
                 * valor capitalizado mais o valor transferido não supere o valor final do contrato
                 */
                self::processarReparacaoMovimento();

                /*
                 * valida e lança rentabilidade/transferencia automatica para o movimento caso o valor transferido seja
                 * inferior ao valor final do contrato onde o saldo de rentabilidade seja igual a zero
                 */
                self::processarReparacaoRentabilidade();

                $sucesso = self::finalizar();
            } elseif ($this->consultorQuitarComBonus && $this->pedido->status == 7) {
                /*
                * Valida e faz o estorno do valor excedido
                */
                self::processarEstornoExcedido();

                /*
                 * Valida e transfere rentabilidades não transferidas caso não resulte em um valor total transferido superior
                 * ao valor final do contrato
                 */
                self::processarRentabilidadeRemanescente();

                $sucesso = self::finalizar();
            }

            if ($sucesso) {
                DB::commit();
            } else {
                DB::rollback();
            }

            foreach ($this->notificacoes as $notificacao) {
                Notification::send(User::findOrFail(2), new LogSlack($notificacao));
            }
        } catch (ModelNotFoundException $e) {
            Log::error('Serviço de finalização de contratos - Erro ao processar FinalizaContratoAutomaticoService::finalizarContrato'.$e);
            DB::rollback();

            $notificacao = [
                'contexto' => "Serviço de finalização de contratos - Erro ao processar FinalizaContratoAutomaticoService::finalizarContrato - {$e->getMessage()}",
                'titulo' => 'Método: finalizarContrato',
                'mensagem' => 'Classe: FinalizaContratoService',
                'detalhes' => [
                    'Valor transferido' => mascaraMoeda($this->sistema->moeda, $this->valorTransferido, 2, true),
                    'Valor final do contrato' => mascaraMoeda($this->sistema->moeda, $this->valorFimContrato, 2, true),
                    'Contrato' => $this->pedido->id,
                    'Valor do contrato' => mascaraMoeda($this->sistema->moeda, $this->pedido->valor_total, 2, true),
                    'Início do contrato' => $this->dadosPagamento->data_pagamento_efetivo->format('d/m/Y'),
                    'Fim do contrato' => Carbon::parse($this->dadosPagamento->data_pagamento_efetivo->format('Y-m-d'))->addDay($this->pedidoItem->total_dias_contrato)->format('d/m/Y'),
                ],
            ];
            Notification::send(User::findOrFail(2), new LogSlack($notificacao));
        }
        Log::info('<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
        Log::info('##############################################################################');
        Log::info('#### FinalizaContratoService - Fim                                         ###');
        Log::info('##############################################################################');

        return $sucesso;
    }

    private function processarEstornoExcedido()
    {
        $ultimoPedidoMovimento = PedidosMovimentos::ultimoPedidoMovimentoPedidoId($this->pedido->id);

        if ($ultimoPedidoMovimento->saldo > 0) {
            $this->atualizarValorTransferido();

            if (decimal($this->valorTransferido + $ultimoPedidoMovimento->saldo) > $this->valorFimContrato) {
                $valorExcedido = decimal(($this->valorTransferido + $ultimoPedidoMovimento->saldo) - $this->valorFimContrato);
                Log::info('------------------------------------------------------------------------------');
                Log::info("Serviço de finalização de contratos - Existe um valor excedido de {$valorExcedido} em pedidos_movimentos para o pedido_id {$this->pedido->id}");

                Log::info('$valorTransferido: '.$this->valorTransferido);
                Log::info('$ultimoPedidoMovimento->saldo: '.$ultimoPedidoMovimento->saldo);
                Log::info('$valorExcedido: '.$valorExcedido);
                Log::info('$valorFimContrato: '.$this->valorFimContrato);

                $ultimoPedidoMovimento = PedidosMovimentos::ultimoPedidoMovimentoPedidoId($this->pedido->id);

                $dadosPedidoMovimento = [
                    'valor_manipulado' => $valorExcedido,
                    'saldo_anterior' => $ultimoPedidoMovimento->saldo,
                    'saldo' => $ultimoPedidoMovimento->saldo - $valorExcedido,
                    'status' => 1,
                    'descricao' => 'COMPLIANCE - Estorno de teto contratado excedido',
                    'pedido_id' => $ultimoPedidoMovimento->pedido_id,
                    'item_id' => $ultimoPedidoMovimento->item_id,
                    'titulo_id' => $ultimoPedidoMovimento->titulo_id,
                    'user_id' => $ultimoPedidoMovimento->user_id,
                    'operacao_id' => 35,
                    'responsavel_user_id' => 1,
                ];

                Log::info('$dadosPedidoMovimento: ', $dadosPedidoMovimento);

                $ultimoPedidoMovimento = PedidosMovimentos::create($dadosPedidoMovimento);

                $this->pedidoMovimentoAtualizado = true;

                $notificacao = [
                    'tipo' => 'sucesso',
                    'contexto' => "Serviço de finalização de contratos - Existe um valor excedido de {$valorExcedido} em pedidos_movimentos para o pedido_id {$this->pedido->id}",
                    'titulo' => 'Método: processarEstornoExcedido',
                    'mensagem' => 'Classe: FinalizaContratoService',
                    'detalhes' => [
                        'Saldo da rentabilidade' => mascaraMoeda($this->sistema->moeda, $ultimoPedidoMovimento->saldo, 2, true),
                        'Valor transferido' => mascaraMoeda($this->sistema->moeda, $this->valorTransferido, 2, true),
                        'Valor excedido' => mascaraMoeda($this->sistema->moeda, $valorExcedido, 2, true),
                        'Valor final do contrato' => mascaraMoeda($this->sistema->moeda, $this->valorFimContrato, 2, true),
                        'Contrato' => $this->pedido->id,
                        'Valor do contrato' => mascaraMoeda($this->sistema->moeda, $this->pedido->valor_total, 2, true),
                        'Início do contrato' => $this->dadosPagamento->data_pagamento_efetivo->format('d/m/Y'),
                        'Fim do contrato' => Carbon::parse($this->dadosPagamento->data_pagamento_efetivo->format('Y-m-d'))->addDay($this->pedidoItem->total_dias_contrato)->format('d/m/Y'),
                    ],
                ];
                Notification::send(User::findOrFail(2), new LogSlack($notificacao));

                Log::info('------------------------------------------------------------------------------');
            }
        }
    }

    private function processarRentabilidadeRemanescente()
    {
        $ultimoPedidoMovimento = PedidosMovimentos::ultimoPedidoMovimentoPedidoId($this->pedido->id);

        if ($ultimoPedidoMovimento->saldo > 0) {
            Log::info('------------------------------------------------------------------------------');
            Log::info("Serviço de finalização de contratos - Existe {$ultimoPedidoMovimento->saldo} de saldo em pedidos_movimentos para o pedido_id {$this->pedido->id}");

            Log::info('$valorTransferido: '.$this->valorTransferido);
            Log::info('$ultimoPedidoMovimento->saldo: '.$ultimoPedidoMovimento->saldo);
            Log::info('$valorFimContrato: '.$this->valorFimContrato);

            $this->atualizarValorTransferido();

            if (decimal($this->valorTransferido + $ultimoPedidoMovimento->saldo) <= $this->valorFimContrato) {
                Log::info("Pedidos_movimentos - Realizar transferencia do valor {$ultimoPedidoMovimento->saldo} do pedido_id {$this->pedido->id} para o movimentos");
                $transferenciaRentabilidadeCarteiraService = new TransferenciaRentabilidadeCarteiraService();
                if (
                        $transferenciaRentabilidadeCarteiraService
                        ->pedidoId($this->pedido->id)
                        ->valorManipulado($ultimoPedidoMovimento->saldo)
                        ->descricaoPedidoMovimento('Transferência automática do capital corrigido para sua Carteira')
                        ->descicaoMovimento("Transferência automática do capital corrigido para sua Carteira, contrato Nº #{$this->pedido->id}")
                        ->transferirParaContaDigital()
                    ) {
                    $this->pedidoMovimentoAtualizado = true;

                    $notificacao = [
                        'tipo' => 'sucesso',
                        'contexto' => 'Serviço de finalização de contratos - Existe '.mascaraMoeda($this->sistema->moeda, $ultimoPedidoMovimento->saldo, 2, true)." de saldo em pedidos_movimentos para o pedido_id {$this->pedido->id} >>> Transferência automática do capital corrigido para sua Carteira",
                        'titulo' => 'Método: processarRentabilidadeRemanescente',
                        'mensagem' => 'Classe: FinalizaContratoService',
                        'detalhes' => [
                            'Saldo da rentabilidade' => mascaraMoeda($this->sistema->moeda, $ultimoPedidoMovimento->saldo, 2, true),
                            'Valor transferido' => mascaraMoeda($this->sistema->moeda, $this->valorTransferido, 2, true),
                            'Valor final do contrato' => mascaraMoeda($this->sistema->moeda, $this->valorFimContrato, 2, true),
                            'Contrato' => $this->pedido->id,
                            'Valor do contrato' => mascaraMoeda($this->sistema->moeda, $this->pedido->valor_total, 2, true),
                            'Início do contrato' => $this->dadosPagamento->data_pagamento_efetivo->format('d/m/Y'),
                            'Fim do contrato' => Carbon::parse($this->dadosPagamento->data_pagamento_efetivo->format('Y-m-d'))->addDay($this->pedidoItem->total_dias_contrato)->format('d/m/Y'),
                        ],
                    ];
                    Notification::send(User::findOrFail(2), new LogSlack($notificacao));
                }
            } else {
                Log::error("Serviço de finalização de contratos - O saldo de {$ultimoPedidoMovimento->saldo} disponível em pedidos_movimentos do pedido_id {$this->pedido->id} somado ao valor transferido é superior ao valor do contrato {$this->valorFimContrato}");

                $notificacao = [
                        'contexto' => 'Serviço de finalização de contratos - O saldo de '.mascaraMoeda($this->sistema->moeda, $ultimoPedidoMovimento->saldo, 2, true)." disponível em pedidos_movimentos do pedido_id {$this->pedido->id} somado ao valor transferido é superior ao valor do contrato ".mascaraMoeda($this->sistema->moeda, $this->valorFimContrato, 2, true).', ação processarRentabilidadeRemanescente abortada ',
                        'titulo' => 'Método: processarRentabilidadeRemanescente',
                        'mensagem' => 'Classe: FinalizaContratoService',
                        'detalhes' => [
                            'Valor transferido' => mascaraMoeda($this->sistema->moeda, $this->valorTransferido, 2, true),
                            'Valor final do contrato' => mascaraMoeda($this->sistema->moeda, $this->valorFimContrato, 2, true),
                            'Saldo da rentabilidade' => mascaraMoeda($this->sistema->moeda, $ultimoPedidoMovimento->saldo, 2, true),
                            'Contrato' => $this->pedido->id,
                            'Valor do contrato' => mascaraMoeda($this->sistema->moeda, $this->pedido->valor_total, 2, true),
                            'Início do contrato' => $this->dadosPagamento->data_pagamento_efetivo->format('d/m/Y'),
                            'Fim do contrato' => Carbon::parse($this->dadosPagamento->data_pagamento_efetivo->format('Y-m-d'))->addDay($this->pedidoItem->total_dias_contrato)->format('d/m/Y'),
                        ],
                    ];
                Notification::send(User::findOrFail(2), new LogSlack($notificacao));
            }

            Log::info('------------------------------------------------------------------------------');
        }
    }

    private function processarReparacaoMovimento()
    {
        $this->atualizarValorCapitalizado();
        $this->atualizarValorTransferido();

        if ($this->valorCapitalizado > $this->valorTransferido) {
            $valor_manipulado = $this->valorCapitalizado - $this->valorTransferido;

            Log::info('------------------------------------------------------------------------------');
            Log::info('Serviço de finalização de contratos - Valor capitalizado maior que o valor transferido');
            Log::info('$valorCapitalizado: '.$this->valorCapitalizado);
            Log::info('$valorTransferido: '.$this->valorTransferido);
            Log::info('$valor_manipulado = $valorCapitalizado - $valorTransferido');
            Log::info('$valor_manipulado: '.$valor_manipulado);

            $ultimoPedidoMovimento = PedidosMovimentos::ultimoPedidoMovimentoPedidoId($this->pedido->id);

            if ($ultimoPedidoMovimento->saldo == 0 && decimal($valor_manipulado + $this->valorTransferido) <= $this->valorFimContrato) {
                $dadosPedidoMovimento = [
                    'valor_manipulado' => $valor_manipulado,
                    'saldo_anterior' => $ultimoPedidoMovimento->saldo,
                    'saldo' => 0,
                    'status' => 1,
                    'descricao' => "Correção de capital, contrato N° #{$this->pedido->id}",
                    'pedido_id' => $ultimoPedidoMovimento->pedido_id,
                    'item_id' => $ultimoPedidoMovimento->item_id,
                    'titulo_id' => $ultimoPedidoMovimento->titulo_id,
                    'user_id' => $ultimoPedidoMovimento->user_id,
                    'operacao_id' => 26,
                    'responsavel_user_id' => 1,
                ];

                Log::info('$dadosPedidoMovimento: ', $dadosPedidoMovimento);

                $ultimoPedidoMovimento = PedidosMovimentos::create($dadosPedidoMovimento);

                $this->pedidoMovimentoAtualizado = true;

                $ultimoMovimento = Movimentos::ultimoMovimentoUserId($this->usuario->id);

                $dadosMovimento = [
                    'valor_manipulado' => $valor_manipulado,
                    'saldo_anterior' => $ultimoMovimento ? $ultimoMovimento->saldo : 0,
                    'saldo' => $ultimoMovimento ? decimal($ultimoMovimento->saldo + $valor_manipulado) : $valor_manipulado,
                    'descricao' => "Correção de capital, contrato N° #{$this->pedido->id}",
                    'responsavel_user_id' => 1,
                    'user_id' => $ultimoMovimento->user_id,
                    'operacao_id' => 26,
                    'pedido_id' => $this->pedido->id,
                    'item_id' => $this->pedidoItem->item_id,
                    'titulo_id' => $this->titulo->id,
                ];

                Log::info('$dadosMovimento:', $dadosMovimento);

                Movimentos::create($dadosMovimento);

                $notificacao = [
                    'tipo' => 'sucesso',
                    'contexto' => "Serviço de finalização de contratos - O Valor capitalizado é maior que o valor transferido. Contrato #{$this->pedido->id} >>> Correção de capital, contrato N° #{$this->pedido->id}",
                    'titulo' => 'Método: processarReparacaoMovimento',
                    'mensagem' => 'Classe: FinalizaContratoService',
                    'detalhes' => [
                        'Valor manipulado' => mascaraMoeda($this->sistema->moeda, $valor_manipulado, 2, true),
                        'Valor transferido' => mascaraMoeda($this->sistema->moeda, $this->valorTransferido, 2, true),
                        'Valor final do contrato' => mascaraMoeda($this->sistema->moeda, $this->valorFimContrato, 2, true),
                        'Contrato' => $this->pedido->id,
                        'Valor do contrato' => mascaraMoeda($this->sistema->moeda, $this->pedido->valor_total, 2, true),
                        'Início do contrato' => $this->dadosPagamento->data_pagamento_efetivo->format('d/m/Y'),
                        'Fim do contrato' => Carbon::parse($this->dadosPagamento->data_pagamento_efetivo->format('Y-m-d'))->addDay($this->pedidoItem->total_dias_contrato)->format('d/m/Y'),
                    ],
                ];
                $this->inserirNotificacao($notificacao);
            } else {
                if ($ultimoPedidoMovimento->saldo > 0) {
                    Log::error("Serviço de finalização de contratos - Existe {$ultimoPedidoMovimento->saldo} de saldo em pedidos_movimentos, ação do processarReparacaoMovimento abortado");

                    $notificacao = [
                            'contexto' => 'Serviço de finalização de contratos - Existe '.mascaraMoeda($this->sistema->moeda, $ultimoPedidoMovimento->saldo, 2, true).' de saldo em pedidos_movimentos, ação do processarReparacaoMovimento abortado',
                            'titulo' => 'Método: processarReparacaoMovimento',
                            'mensagem' => 'Classe: FinalizaContratoService',
                            'detalhes' => [
                                'Valor transferido' => mascaraMoeda($this->sistema->moeda, $this->valorTransferido, 2, true),
                                'Valor final do contrato' => mascaraMoeda($this->sistema->moeda, $this->valorFimContrato, 2, true),
                                'Saldo da rentabilidade' => mascaraMoeda($this->sistema->moeda, $ultimoPedidoMovimento->saldo, 2, true),
                                'Contrato' => $this->pedido->id,
                                'Valor do contrato' => mascaraMoeda($this->sistema->moeda, $this->pedido->valor_total, 2, true),
                                'Início do contrato' => $this->dadosPagamento->data_pagamento_efetivo->format('d/m/Y'),
                                'Fim do contrato' => Carbon::parse($this->dadosPagamento->data_pagamento_efetivo->format('Y-m-d'))->addDay($this->pedidoItem->total_dias_contrato)->format('d/m/Y'),
                            ],
                        ];
                    $this->inserirNotificacao($notificacao);
                } else {
                    Log::error("Serviço de finalização de contratos - O valor manipulado de {$valor_manipulado} mais o valor transferido de {$this->valorTransferido} totalizando ".($valor_manipulado + $this->valorTransferido)." supera o valor final do contrato de {$this->valorFimContrato}");

                    $notificacao = [
                                'contexto' => 'Serviço de finalização de contratos - O valor manipulado de '.mascaraMoeda($this->sistema->moeda, $valor_manipulado, 2, true).' mais o valor transferido de '.mascaraMoeda($this->sistema->moeda, $this->valorTransferido, 2, true).' totalizando '.mascaraMoeda($this->sistema->moeda, ($valor_manipulado + $this->valorTransferido), 2, true).' supera o valor final do contrato de '.mascaraMoeda($this->sistema->moeda, $this->valorFimContrato, 2, true).', ação processarReparacaoMovimento abortada',
                                'titulo' => 'Método: processarReparacaoMovimento',
                                'mensagem' => 'Classe: FinalizaContratoService',
                                'detalhes' => [
                                    'Valor manipulado' => mascaraMoeda($this->sistema->moeda, $valor_manipulado, 2, true),
                                    'Valor transferido' => mascaraMoeda($this->sistema->moeda, $this->valorTransferido, 2, true),
                                    'Valor final do contrato' => mascaraMoeda($this->sistema->moeda, $this->valorFimContrato, 2, true),
                                    'Saldo da rentabilidade' => mascaraMoeda($this->sistema->moeda, $ultimoPedidoMovimento->saldo, 2, true),
                                    'Contrato' => $this->pedido->id,
                                    'Valor do contrato' => mascaraMoeda($this->sistema->moeda, $this->pedido->valor_total, 2, true),
                                    'Início do contrato' => $this->dadosPagamento->data_pagamento_efetivo->format('d/m/Y'),
                                    'Fim do contrato' => Carbon::parse($this->dadosPagamento->data_pagamento_efetivo->format('Y-m-d'))->addDay($this->pedidoItem->total_dias_contrato)->format('d/m/Y'),
                                ],
                            ];
                    $this->inserirNotificacao($notificacao);
                }
            }

            Log::info('------------------------------------------------------------------------------');
        }
    }

    private function processarReparacaoRentabilidade()
    {
        $this->atualizarValorTransferido();

        if ($this->valorTransferido < $this->valorFimContrato) {
            $valor_manipulado = $this->valorFimContrato - $this->valorTransferido;

            Log::info('------------------------------------------------------------------------------');
            Log::info('Serviço de finalização de contratos - Valor transferido é menor que o valor final do contrato');
            Log::info('$valorTransferido: '.$this->valorTransferido);
            Log::info('$valorFimContrato: '.$this->valorFimContrato);
            Log::info('$valor_manipulado = $valorFimContrato - $valorTransferido');
            Log::info('$valor_manipulado: '.$valor_manipulado);

            $ultimoPedidoMovimento = PedidosMovimentos::ultimoPedidoMovimentoPedidoId($this->pedido->id);

            if ($ultimoPedidoMovimento->saldo == 0) {
                $dadosPedidoMovimento = [
                    'valor_manipulado' => $valor_manipulado,
                    'saldo_anterior' => $ultimoPedidoMovimento->saldo,
                    'saldo' => decimal($ultimoPedidoMovimento->saldo + $valor_manipulado),
                    'status' => 1,
                    'descricao' => "Correção de capital, contrato N° #{$this->pedido->id}",
                    'pedido_id' => $this->pedido->id,
                    'item_id' => $this->pedidoItem->item_id,
                    'titulo_id' => $this->titulo->id,
                    'user_id' => $this->usuario->id,
                    'operacao_id' => 7,
                    'responsavel_user_id' => 1,
                ];

                Log::info('$dadosPedidoMovimento: ', $dadosPedidoMovimento);

                $ultimoPedidoMovimento = PedidosMovimentos::create($dadosPedidoMovimento);

                $this->pedidoMovimentoAtualizado = true;

                $transferenciaRentabilidadeCarteiraService = new TransferenciaRentabilidadeCarteiraService();
                $transferenciaRentabilidadeCarteiraService
                    ->pedidoId($this->pedido->id)
                    ->valorManipulado($valor_manipulado)
                    ->descricaoPedidoMovimento('Transferência automática do valor rentabilizado para sua Carteira')
                    ->descicaoMovimento("Transferência automática do valor rentabilizado, contrato Nº #{$this->pedido->id}")
                    ->transferirParaContaDigital();

                $notificacao = [
                    'tipo' => 'sucesso',
                    'contexto' => 'Serviço de finalização de contratos - Valor transferido é menor que o valor final do contrato  >>> Alerta - Foi criado um movimento no valor de ' . mascaraMoeda($this->sistema->moeda,$valor_manipulado,2,true) . ' em pedidos movimentos como correção do valor rentabilizado  >>> Transferência automática do valor rentabilizado para sua Carteira',
                    'titulo' => 'Método: processarReparacaoRentabilidade',
                    'mensagem' => 'Classe: FinalizaContratoService',
                    'detalhes' => [
                        'Valor manipulado' => mascaraMoeda($this->sistema->moeda, $valor_manipulado, 2, true),
                        'Valor transferido' => mascaraMoeda($this->sistema->moeda, $this->valorTransferido, 2, true),
                        'Valor final do contrato' => mascaraMoeda($this->sistema->moeda, $this->valorFimContrato, 2, true),
                        'Contrato' => $this->pedido->id,
                        'Valor do contrato' => mascaraMoeda($this->sistema->moeda, $this->pedido->valor_total, 2, true),
                        'Início do contrato' => $this->dadosPagamento->data_pagamento_efetivo->format('d/m/Y'),
                        'Fim do contrato' => Carbon::parse($this->dadosPagamento->data_pagamento_efetivo->format('Y-m-d'))->addDay($this->pedidoItem->total_dias_contrato)->format('d/m/Y'),
                    ],
                ];
                $this->inserirNotificacao($notificacao);
            } else {
                Log::error("Serviço de finalização de contratos - Existe {$ultimoPedidoMovimento->saldo} de saldo em pedidos_movimentos, ação do processarReparacaoRentabilidade abortado");

                $notificacao = [
                        'contexto' => 'Serviço de finalização de contratos - Existe '.mascaraMoeda($this->sistema->moeda, $ultimoPedidoMovimento->saldo, 2, true).' de saldo em pedidos_movimentos, ação do processarReparacaoRentabilidade abortado',
                        'titulo' => 'Método: processarReparacaoRentabilidade',
                        'mensagem' => 'Classe: FinalizaContratoService',
                        'detalhes' => [
                            'Valor transferido' => mascaraMoeda($this->sistema->moeda, $this->valorTransferido, 2, true),
                            'Valor final do contrato' => mascaraMoeda($this->sistema->moeda, $this->valorFimContrato, 2, true),
                            'Saldo da rentabilidade' => mascaraMoeda($this->sistema->moeda, $ultimoPedidoMovimento->saldo, 2, true),
                            'Contrato' => $this->pedido->id,
                            'Valor do contrato' => mascaraMoeda($this->sistema->moeda, $this->pedido->valor_total, 2, true),
                            'Início do contrato' => $this->dadosPagamento->data_pagamento_efetivo->format('d/m/Y'),
                            'Fim do contrato' => Carbon::parse($this->dadosPagamento->data_pagamento_efetivo->format('Y-m-d'))->addDay($this->pedidoItem->total_dias_contrato)->format('d/m/Y'),
                        ],
                    ];
                $this->inserirNotificacao($notificacao);
            }

            Log::info('------------------------------------------------------------------------------');
        }
    }

    private function finalizar()
    {
        $sucesso = false;

        $this->atualizarValorTransferido();

        if ($this->valorTransferido == $this->valorFimContrato) {
            Log::info('------------------------------------------------------------------------------');
            Log::info('Serviço de finalização de contratos - Finalizar contrato');

            if (! $this->consultorQuitarComBonus) {
                $valor_manipulado = $this->pedido->valor_total;

                Log::info('$consultorQuitarComBonus: '.$this->consultorQuitarComBonus);
                Log::info('Devolver o valor do contrato: '.$valor_manipulado);

                $ultimoMovimento = Movimentos::ultimoMovimentoUserId($this->usuario->id);

                $dadosMovimento = [
                    'valor_manipulado' => $valor_manipulado,
                    'saldo_anterior' => $ultimoMovimento ? $ultimoMovimento->saldo : 0,
                    'saldo' => $ultimoMovimento ? decimal($ultimoMovimento->saldo + $valor_manipulado) : $valor_manipulado,
                    'descricao' => "Contrato N° #{$this->pedido->user_id} finalizado",
                    'responsavel_user_id' => 1,
                    'user_id' => $ultimoMovimento->user_id,
                    'operacao_id' => 7,
                    'pedido_id' => $this->pedido->id,
                    'item_id' => $this->pedidoItem->item_id,
                    'titulo_id' => $this->titulo->id,
                ];

                Log::info('$dadosMovimento:', $dadosMovimento);

                Movimentos::create($dadosMovimento);
            }

            $this->pedido->data_fim = Carbon::now();
            $this->pedido->status = 6;
            $this->pedido->save();
            $sucesso = true;

            Log::info('$valorTransferido:'.$this->valorTransferido);
            Log::info('$valorFimContrato:'.$this->valorFimContrato);

            Log::info('Serviço de finalização de contratos - Contrato finalizado com sucesso');
            Log::info('Id do pedido: '.$this->pedido->id);
            Log::info('status do pedido: '.$this->pedido->status);
            Log::info('data fim do pedido: '.$this->pedido->data_fim);

            $notificacao = [
                'tipo' => 'sucesso',
                'contexto' => "Serviço de finalização de contratos - Contrato #{$this->pedido->id} finalizado com sucesso",
                'titulo' => 'Método: finalizar',
                'mensagem' => 'Classe: FinalizaContratoService',
                'detalhes' => [
                    'Valor transferido' => mascaraMoeda($this->sistema->moeda, $this->valorTransferido, 2, true),
                    'Valor final do contrato' => mascaraMoeda($this->sistema->moeda, $this->valorFimContrato, 2, true),
                    'Contrato' => $this->pedido->id,
                    'Valor do contrato' => mascaraMoeda($this->sistema->moeda, $this->pedido->valor_total, 2, true),
                    'Início do contrato' => $this->dadosPagamento->data_pagamento_efetivo->format('d/m/Y'),
                    'Fim do contrato' => Carbon::parse($this->dadosPagamento->data_pagamento_efetivo->format('Y-m-d'))->addDay($this->pedidoItem->total_dias_contrato)->format('d/m/Y'),
                    'Status do pedido' => $this->pedido->status,
                    'Data de finalização do pedido' =>  $this->pedido->data_fim->format('d/m/Y h:m:s'),
                    'Id usuário' => $this->usuario->id,
                    'Nome do usuário' => $this->usuario->name,
                ],
            ];
            $this->inserirNotificacao($notificacao);

            Notification::send($this->usuario, new EmailFinalizacaoContrato($this->pedido));

            if ($this->pedidoItem->modo_recontratacao_automatica > 0) {
                $reinvestimento = new Recontratacao();

                $sucesso = $reinvestimento
                                ->pedidoId($this->pedido->id)
                                ->processar();
            }
        } else {
            Log::error("Serviço de finalização de contratos - O contrato #{$this->pedido->id} não foi finalizado devido a divergencia entre o 'valor transferido' e 'valor final do contrato'");

            Log::info('$valorTransferido:'.$this->valorTransferido);
            Log::info('$valorFimContrato:'.$this->valorFimContrato);

            $notificacao = [
                    'contexto' => "Serviço de finalização de contratos - O contrato #{$this->pedido->id} não foi finalizado devido a divergencia entre o 'valor transferido' e 'valor final do contrato'",
                    'titulo' => 'Método: finalizar',
                    'mensagem' => 'Classe: FinalizaContratoService',
                    'detalhes' => [
                        'Valor transferido' => mascaraMoeda($this->sistema->moeda, $this->valorTransferido, 2, true),
                        'Valor final do contrato' => mascaraMoeda($this->sistema->moeda, $this->valorFimContrato, 2, true),
                        'Contrato' => $this->pedido->id,
                        'Valor do contrato' => mascaraMoeda($this->sistema->moeda, $this->pedido->valor_total, 2, true),
                        'Início do contrato' => $this->dadosPagamento->data_pagamento_efetivo->format('d/m/Y'),
                        'Fim do contrato' => Carbon::parse($this->dadosPagamento->data_pagamento_efetivo->format('Y-m-d'))->addDay($this->pedidoItem->total_dias_contrato)->format('d/m/Y'),
                    ],
                ];
            $this->inserirNotificacao($notificacao);
        }

        Log::info('------------------------------------------------------------------------------');

        return $sucesso;
    }
}
