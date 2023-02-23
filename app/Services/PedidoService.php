<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

use Carbon\Carbon;
    use App\Models\User;
    use App\Models\Itens;
    use App\Models\Pedidos;
    use App\Models\Sistema;
    use App\Models\Movimentos;
    use App\Models\ItensPedido;
    use App\Models\Rentabilidade;
    use App\Models\PedidosMovimentos;
    use App\Jobs\SendCapitalizacaoEmail;

    class PedidoService
    {
        private $usuario;
        private $item;
        private $valor;
        private $pedido;
        private $operacao;
        private $itemPedido;
        private $pedidoCompletoAssociado = false;
        private $usuario_comprador = null;
        private $rentabilidade = null;
        private $ultimoMovimentoInterno = null;
        private $movimentoReferencia = null;
        private $pedidoCompleto = false;
        private $retorno = [];
        private $sistema;
        private $usuarioResponsavel;

        public function __construct()
        {
            $this->sistema = Sistema::findOrFail(1);
        }

        public function rentabilidade(Rentabilidade $rentabilidade)
        {
            $this->rentabilidade = $rentabilidade;

            return $this;
        }

        public function ultimoMovimentoInterno(PedidosMovimentos $ultimoMovimentoInterno)
        {
            $this->ultimoMovimentoInterno = $ultimoMovimentoInterno;

            return $this;
        }

        public function movimentoReferencia(Movimentos $movimentoReferencia)
        {
            $this->movimentoReferencia = $movimentoReferencia;

            return $this;
        }

        public function valor($valor)
        {
            $this->valor = $valor;

            return $this;
        }

        public function operacao($operacao)
        {
            $this->operacao = $operacao;

            return $this;
        }

        public function dadosItem(ItensPedido $itemPedido)
        {
            $this->itemPedido = $itemPedido;
            $this->item = $itemPedido->item;
            $this->usuario_comprador = $this->itemPedido->pedido->usuario;
            $this->usuarioResponsavel = \Auth::user() ?? $this->usuario_comprador;

            return $this;
        }

        public function pedido(Pedidos $pedido)
        {
            $this->pedido = $pedido;
            $this->itemPedido = $pedido->itens()->first();
            $this->item = $this->itemPedido->item;
            $this->usuario_comprador = $pedido->usuario;
            $this->usuarioResponsavel = \Auth::user() ?? $pedido->user;

            return $this;
        }

        public function usuario(User $usuario)
        {
            $this->usuario = $usuario;

            return $this;
        }

        /**
         * Pega pedidos ativos.
         *
         * @return array
         */
        public function pagarRentabilidade()
        {
            \Log::info('$$$$$$$$$$$$ --> Entrou para pagar rentabilidade');
            $this->retorno['valor'] = $this->valor;
            $this->retorno['restante'] = 0;

            \Log::info("Valor manipulado $ {$this->valor}");

            if ($this->rentabilidade) {
                self::verificaPedidoUnico($this->pedido, $this->item);
            } else {
                $pedidos = $this->usuario->pedidos()
                    ->where('status', 2)
                    ->where('tipo_pedido', '<>', 3)
                    ->whereHas('itens', function ($query) {
                        $query->whereHas('item', function ($query) {
                            $query->where('quitar_com_bonus', 1);
                        });
                    })
                    ->get();

                if ($pedidos->count() > 0) {
                    self::verificarPedidos($pedidos);
                }

                if ($pedidos->count() == 0) {
                    \Log::info('Nao tem depositos ativos para receber rentabilidade.');
                }
            }
            \Log::info('$$$$$$$$$$$$ --> Saiu pagamento rentabilidade');

            return $this->retorno;
        }

        /**
         * verifica se os itens do pedido recebe rentabilidade.
         *
         * @param $pedidos
         */
        private function verificarPedidos($pedidos):void
        {
            \Log::info('Verificando pedidos!');
            //ordena pelo pedido que paga mais
            foreach ($pedidos->sortByDesc('valor_total')->values()->all() as $key => $pedido) {
                \Log::info("Looping {$key}");
                foreach ($pedido->itens as $itemPedido) {
                    if (in_array($itemPedido->item->tipo_pedido_id, [1, 2]) && $this->retorno['valor'] > 0) {
                        \Log::info('Item do pedido atende aos critérios!');
                        self::verificaPedidoUnico($pedido, $itemPedido->item);
                    }
                }
            }
        }

        private function verificaPedidoUnico(Pedidos $pedido, Itens $item):void
        {
            \Log::info("Verificando pedido #{$pedido->id}");

            self::calcPedido($pedido, $item);

            if ($this->retorno['valor'] > 0) {
                self::pagar($pedido);
            }

            self::verificaPedidoCompleto($pedido);
        }

        private function verificaPedidoCompleto(Pedidos $pedido):void
        {
            if ($this->pedidoCompleto) {
                $pedido->status = 7;
                $pedido->save();
                \Log::info("Potencial rendimento atingido, pedido # {$pedido->id} Fechado!");
            }

            $this->pedidoCompleto = false;
            $this->pedidoCompletoAssociado = false;
        }

        /**
         * Calcula o valor que falta ser pago.
         *
         * @param Pedidos $pedido
         * @param Itens $item
         */
        private function calcPedido(Pedidos $pedido, Itens $item):void
        {
            $totalPago = 0;

            $this->ultimoMovimentoInterno = $this->ultimoMovimentoInterno ?? $pedido->ultimoMovimentosInterno();

            if ($this->usuario->titulo->habilita_rede) {
                \Log::info('Utilizará base total de rendimento para calculo!');

                $potenciaTotalPercentual = $item->meses * $item->potencial_mensal_teto;

                \Log::info("Teto percentual %{$potenciaTotalPercentual}");

                $totalPago = $pedido->movimentosInterno()->whereNotIn('operacao_id', [26, 35])->sum('valor_manipulado');
            } else {
                //se o usuário não faz rede o calculo do teto é mensal baseado na rentabilidade do Item
                \Log::info('Utilizará base mensal para calculo!');
                $dataInicio = $pedido->dadosPagamento->data_pagamento_efetivo->firstOfMonth();
                $dataFim = $pedido->dadosPagamento->data_pagamento_efetivo->firstOfMonth();

                $today = Carbon::now();

                //verifica o range de meses entre a aquisição e o mês atual em meses
                $meses = $dataInicio->diffInMonths($today);

                $dataInicio->addMonth($meses);
                $dataFim->addMonth($meses + 1);

                $totalMensalPago = $pedido->movimentosInterno()
                        ->whereIn('operacao_id', [6, 7])
                        ->whereBetween('created_at', [$dataInicio->format('Y-m-d'), $dataFim->format('Y-m-d')])
                        ->sum('valor_manipulado') ?? 0;

                //se estiver pagando rentabilidade, levar em conta teto mensal
                $potenciaMensalPercentualTotal = $item->potencial_mensal_teto;
                \Log::info("Teto percentual Mensal %{$potenciaMensalPercentualTotal}");

                $potenciaTotalPercentual = $item->meses * $item->potencial_mensal_teto;

                \Log::info("Teto total percentual %{$potenciaTotalPercentual}");

                // ganho geral do contrato
                $totalPago = $pedido->movimentosInterno()->whereNotIn('operacao_id', [26, 35])->sum('valor_manipulado');
            }

            // valor total geral que pode ser pago
            $potenciaGeralValorTotal = ($potenciaTotalPercentual * $pedido->valor_total) / 100;

            // caso atenda as condições o potencial de ganha é paior
            if ($item->quitar_com_bonus && $this->usuario->titulo->habilita_rede) {
                $potenciaGeralValorTotal += $pedido->valor_total;
            }

            // calculo do total geral
            self::calcValorPagamento($totalPago, $potenciaGeralValorTotal, 'Geral');

            // calculo do total mensal
            if (isset($totalMensalPago)) {
                $potenciaMensalValorTotal = ($potenciaMensalPercentualTotal * $pedido->valor_total) / 100;

                if ($item->quitar_com_bonus && $this->usuario->titulo->habilita_rede) {
                    $potenciaMensalValorTotal += $pedido->valor_total;
                }

                self::calcValorPagamento($totalMensalPago, $potenciaMensalValorTotal);
            }
        }

        /**
         * verifica se o total pago mais o valor a ser pago ultrapassa o potencial total.
         *
         * @param $totalPago
         * @param $potenciaTotalValor
         */
        private function calcValorPagamento($totalPago, $valorPotenciaTotal, $tipo = 'Mensal'):void
        {
            \Log::info("Total {$tipo} Pago ".mascaraMoeda($this->sistema->moeda, $totalPago, 2, true));
            \Log::info("Potencial {$tipo} ".mascaraMoeda($this->sistema->moeda, $valorPotenciaTotal, 2, true));

            if (($totalPago + $this->retorno['valor']) > $valorPotenciaTotal) {
                $paraPagar = $valorPotenciaTotal - $totalPago;

                //se tiver rede o código estará tratando o teto pelo total do contrato
                //Deste modo se bater o teto o contrato terá sido finalizado
                if ($this->usuario->titulo->habilita_rede) {
                    $this->pedidoCompleto = true;
                } else {
                    $this->pedidoCompletoAssociado = true;
                }

                $this->retorno['restante'] = $this->retorno['valor'] - $paraPagar;
                $this->retorno['valor'] = $paraPagar;

                \Log::info("Pode pagar $ {$paraPagar} de rentabilidade {$tipo}");
                \Log::info("Valor excedente da rentabilidade {$tipo} em R$ {$this->retorno['restante']}");
            }
        }

        /**
         * Paga o valor.
         *
         * @param Pedidos $pedido
         */
        private function pagar(Pedidos $pedido):void
        {
            \Log::info('Registrar pagamento rentabilidade Inicio');
            $descricao = ($this->pedidoCompleto || $this->pedidoCompletoAssociado) ? 'Correção contratual atingida' : 'Correção Contratual - Via Capital Corrigido';

            $dadosPagamento = [
                'valor_manipulado' => $this->retorno['valor'],
                'saldo_anterior' => $this->ultimoMovimentoInterno ? $this->ultimoMovimentoInterno->saldo : 0,
                'saldo' => round($this->ultimoMovimentoInterno ? $this->ultimoMovimentoInterno->saldo + $this->retorno['valor'] : $this->retorno['valor'], 2),
                'status' => 1,
                'descricao' => ! $this->rentabilidade ? 'Amortização do contrato - Crédito - '.$this->usuario_comprador->name : $descricao,
                'pedido_id' => $pedido->id,
                'pedido_referencia_id' => $this->itemPedido->pedido_id,
                'item_id' => $this->item->id,
                'user_id' => $this->usuario->id,
                'operacao_id' => $this->operacao, //TODO compra de item no banco de dados, mudar!
                'responsavel_user_id' => $this->usuarioResponsavel->id,
            ];

            if ($this->rentabilidade) {
                $dadosPagamento['rentabilidade_id'] = $this->rentabilidade->id;
            }

            $name = $this->usuario->name;
            $id = $this->usuario->id;
            $this->retorno['valor_pago'] = $this->retorno['valor'];

            \log::info("Pago bonus direto na rentablidade para # { $id } - {$name}", $dadosPagamento);

            if (! $this->rentabilidade) {
                $dadosPagamento['movimento_id'] = $this->movimentoReferencia->id;
            }

            //utilizado para pagar rentabilidade
            $this->retorno['movimento'] = PedidosMovimentos::create($dadosPagamento);

            if (! $this->rentabilidade) {
                $this->ultimoMovimentoInterno = $this->retorno['movimento'];

                $dadosPagamento = [
                    'valor_manipulado' => $this->ultimoMovimentoInterno->valor_manipulado,
                    'saldo_anterior' => $this->ultimoMovimentoInterno->saldo,
                    'saldo' => round($this->ultimoMovimentoInterno->saldo - $this->ultimoMovimentoInterno->valor_manipulado, 2),
                    'status' => 1,
                    'descricao' => "Tranferência automática deste bônus Crédito ref. Doc #{$this->itemPedido->pedido_id} para sua Carteira - Ref. Mov N° {$this->movimentoReferencia->id}",
                    'pedido_id' => $pedido->id,
                    'pedido_referencia_id' => $this->itemPedido->pedido_id,
                    'item_id' => $this->item->id,
                    'user_id' => $this->usuario->id,
                    'operacao_id' => 26,
                    'responsavel_user_id' => $this->usuarioResponsavel->id,
                    'movimento_id' => $this->movimentoReferencia->id,
                ];

                $this->retorno['movimento'] = PedidosMovimentos::create($dadosPagamento);
            }

            /*
            if (! $this->rentabilidade) {
                (new LiquidacaoDepositoService())
                    ->movimento($this->retorno['movimento'])
                    ->item($this->item)
                    ->usuario($this->usuario)
                    ->userDeposito($this->usuario_comprador)
                    ->itemPedido($this->itemPedido)
                    ->calcular();
            }
            */

            /* dispararar email para rentabilidade de item */
            if ($this->rentabilidade) {
                if ($this->rentabilidade->item_id !== null && $this->usuario->avisa_recebimento_rentabilidade) {
                    dispatch(new SendCapitalizacaoEmail([
                        'percentual' => $this->rentabilidade->percentual,
                        'valor' => $this->retorno['movimento']->valor_manipulado,
                        'nome_usuario' => $this->usuario->name,
                        'email_usuario' => $this->usuario->email,
                        'item' => $this->itemPedido->name_item,
                        'contrato' => $pedido->id,
                    ]));
                }
            }

            $this->retorno['valor'] = $this->retorno['restante'];
            $this->retorno['restante'] = 0;

            \Log::info('Registrar pagamento rentabilidade fim');
        }
    }
