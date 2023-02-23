<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners;

use Log;
use App\Models\Sistema;
use App\Models\Movimentos;
use App\Events\PedidoFoiPago;
use App\Services\PedidoService;
use Illuminate\Support\Facades\Auth;
use App\Services\CalculoTetoRecebimento;
use App\Services\PagarMovimentoUniLevel;

class PagaBonusIndicador
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
     * @param  PedidoFoiPago  $event
     * @return void
     */
    public function handle(PedidoFoiPago $event)
    {
        $sistema = Sistema::findOrFail(1);
        Log::info('------- entrou bonus indicador, pedido #'.$event->getPedido()->id.' - Valor Pedido: '.mascaraMoeda($sistema->moeda, $event->getPedido()->valor_total, 2, true));

        $usuarioResponsavel = Auth::user() ?? $event->getPedido()->user;

        try {
            $associado = $event->getUsuario();

            if ($associado->id > 2 && in_array($event->getPedido()->tipo_pedido, [1, 2])) {

                //instancia patrocinador
                $patrocinador = $associado->indicador;

                if ($patrocinador) {
                    //instancia dados necessários
                    $listaItens = $event->getItens();

                    foreach ($listaItens as $itemLista) {
                        $item = $itemLista->item;

                        //verifica se o bonus é maior que 0
                        if ($item->bonus_indicador > 0) {
                            $valorTotal = $itemLista->quantidade * $item->bonus_indicador;

                            \Log::info('$item->bonus_indicador = '.$item->bonus_indicador);
                            \Log::info('$itemLista->quantidade = '.$itemLista->quantidade);
                            \Log::info('$valorTotal: $'.$valorTotal);

                            $valorPago = 0;
                            $ultimoMovimento = null;
                            if ($patrocinador->status == 1 && $patrocinador->titulo->habilita_rede == 1) {
                                if ($valorTotal > 0) {
                                    $result = (new CalculoTetoRecebimento())
                                        ->usuario($patrocinador)
                                        ->valor($valorTotal)
                                        ->calcular();
                                    //pagamento dos bonus
                                    if ($result['valor'] > 0) {
                                        $valorPago = $result['valor'];
                                        //resgata ultima movimentação
                                        $ultimoMovimento = Movimentos::ultimoMovimentoUserId($patrocinador->id);

                                        $dadosMovimento = [
                                            'valor_manipulado' => $result['valor'],
                                            'saldo_anterior' => ! $ultimoMovimento ? 0 : $ultimoMovimento->saldo,
                                            'saldo' => ! $ultimoMovimento ? $result['valor'] : $result['valor'] + $ultimoMovimento->saldo,
                                            'pedido_id' => $event->getPedido()->id,
                                            'descricao' => "Bônus - Cliente - {$associado->name}",
                                            'responsavel_user_id' => $usuarioResponsavel->id,
                                            'user_id' => $patrocinador->id,
                                            'item_id' => $item->id,
                                            'titulo_id' => $patrocinador->titulo->id,
                                            'operacao_id' => $item->tipo_pedido_id == 1 ? 1 : 20,
                                        ];

                                        \log::info("Pago bonus direto para #{ $patrocinador->id } - {$patrocinador->name}", $dadosMovimento);

                                        $ultimoMovimento = Movimentos::create($dadosMovimento);
                                    }
                                }
                            }

                            if ($valorPago > $sistema->royalties_valor_minimo_bonus) {
                                $valorRoyalties = round((($valorPago * $sistema->royalties_porcentagem) / 100), 2);
                                $valorResidual = round((($valorRoyalties * $sistema->royalties_porcentagem_distribuir) / 100) / $sistema->profundidade_pagamento_matriz, 2);

                                \Log::info('$valorPago:'.$valorPago);
                                \Log::info('profundidade_pagamento_matriz = '.$sistema->profundidade_pagamento_matriz);
                                \Log::info('$valorRoyalties = round((($valorPago * $sistema->royalties_porcentagem) / 100), 2)');
                                \Log::info('$valorResidual = round((($valorRoyalties * $sistema->royalties_porcentagem_distribuir) / 100) / $sistema->profundidade_pagamento_matriz, 2)');
                                \Log::info('$valorRoyalties:'.$valorRoyalties);
                                \Log::info('$valorResidual:'.$valorResidual);

                                $restante = (new PedidoService())
                                    ->usuario($patrocinador)
                                    ->dadosItem($itemLista)
                                    ->operacao(6)
                                    ->valor($valorPago - $valorRoyalties)
                                    ->movimentoReferencia($ultimoMovimento)
                                    ->pagarRentabilidade();

                                $dadosMovimentoRoyalties = [
                                    'valor_manipulado' => $valorRoyalties,
                                    'valor_excedente' => 0,
                                    'saldo_anterior' => $ultimoMovimento->saldo,
                                    'saldo' => $ultimoMovimento->saldo - $valorRoyalties,
                                    'descricao' => "Coleta de Royalties, referente ao bônus residual do depósito #{$event->getPedido()->id}",
                                    'responsavel_user_id' => $usuarioResponsavel->id,
                                    'user_id' => $patrocinador->id,
                                    'operacao_id' => 31,
                                    'pedido_id' => $event->getPedido()->id,
                                    'item_id' => $item->id,
                                    'titulo_id' => $patrocinador->titulo_id,
                                ];

                                Movimentos::create($dadosMovimentoRoyalties);

                                \Log::info("inserindo movimento - {$sistema->royalties_porcentagem}%", $dadosMovimentoRoyalties);

                                if ($valorResidual > 0) {
                                    (new PagarMovimentoUniLevel())
                                        ->usuario($patrocinador->indicador)
                                        ->pedidoReferencia($event->getPedido())
                                        ->valor($valorResidual)
                                        ->operacao(27)
                                        ->profundidade($sistema->profundidade_pagamento_matriz)
                                        ->descricaoMovimento('Royalties - Bônus Residual sobre o depósito nível %d de %d - ref. Doc. Nº %d')
                                        ->pagar();
                                }
                            }else{
                                Log::info("Não paga royalties, valor mínimo do bônus para pagar royalties é de {$sistema->royalties_valor_minimo_bonus}, valor do bônus pago: {$valorPago}");
                            }
                        }
                    }
                }
            }

            Log::info('----- Saiu bonus indicador -------');

            return true;
        } catch (ModelNotFoundException $e) {
            Log::info('Falhou pagamento de bonus indicador');

            return false;
        }
    }
}
