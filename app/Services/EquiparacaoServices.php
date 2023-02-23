<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

use App\Models\User;
use App\Models\Itens;
use App\Models\Pedidos;
use App\Models\Sistema;
use App\Models\Movimentos;
use App\Models\ItensPedido;
use App\Repositories\MovimentosRepository;
use Illuminate\Support\Facades\Auth;

class EquiparacaoServices
{
    private $valor;
    private $item;
    private $pedido;
    private $itemPedido;
    private $retorno = [];

    private $valorPago = 0;
    private $ultimoPercentual;
    private $ultimoTituloPago;

    private $sistema;

    private $usuarioResponsavel;

    public function __construct(Sistema $sistema)
    {
        $this->sistema = $sistema;
    }

    public function valor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    public function item(Itens $item)
    {
        $this->item = $item;

        return $this;
    }

    public function pedido(Pedidos $pedido)
    {
        $this->pedido = $pedido;

        $this->usuarioResponsavel = Auth::user() ?? $this->pedido->user;

        return $this;
    }

    public function dadosItem(ItensPedido $itemPedido)
    {
        $this->itemPedido = $itemPedido;
        $this->item = $itemPedido->item;
        //$this->valor = $itemPedido->quantidade * $this->item->bonus_equiparacao;

        return $this;
    }

    public function usuario(User $usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function equiparar()
    {
        \Log::info('Valor para equiparar: '.mascaraMoeda($this->sistema->moeda, $this->valor, 2, true));

        $this->retorno['valor'] = $this->valor;
        $this->retorno['restante'] = 0;

        \Log::info('---------> Pagar usuario #'.$this->usuario->id);

        self::pagarRede();

        return $this->retorno;
    }

    /**
     * Anda na rede.
     *
     * @return bool
     */
    private function pagarRede()
    {
        if ($this->usuario->id == 2) {
            return false;
        }

        if ($this->usuario->status != 1) {
            \Log::info('Usuario inativo #'.$this->usuario->id);

            return self::subirRede();
        }

        return self::verificaTitulo();
    }

    /**
     * Verifica se titulo recebe equiparacao.
     */
    private function verificaTitulo()
    {
        $titulo = $this->usuario->titulo;

        /*
         * TODO Validar para que o titulo inferior nao tenha um percentual maior que o titulo acima
         */
        if ($titulo->equiparacao_percentual == 0 || $this->ultimoPercentual >= ($titulo->equiparacao_percentual * 10)) {
            \Log::info('Titulo ja pago #'.$titulo->name);
            \Log::info('Pencentual ja pago %'.$titulo->equiparacao_percentual * 10);

            return self::subirRede();
        }

        \Log::info('Pagar titulo #'.$titulo->name);
        \Log::info('Pencentual %'.$titulo->equiparacao_percentual * 10);

        $this->ultimoTituloPago = $titulo;

        //Ex: 10 * 7 = 70%
        $this->ultimoPercentual = 10 * $this->ultimoTituloPago->equiparacao_percentual;

        $valorAPagar = round(($this->ultimoPercentual * $this->valor) / 100, 2);

        return self::pagaTitulo($valorAPagar);
    }

    /**
     * Pula o usuario.
     */
    private function subirRede()
    {
        $this->usuario = $this->usuario->indicador;

        if ($this->usuario->id == 2) {
            return false;
        }

        \Log::info('---------> Pagar usuario #'.$this->usuario->id);

        return self::pagarRede();
    }

    private function pagaTitulo($valorAPagar)
    {
        if ($this->usuario->titulo->habilita_rede == 1) {
            $totalAPagar = $valorAPagar - $this->valorPago;

            $valorPago = 0;
            $ultimoMovimento = null;
            if ($totalAPagar > 0) {

                $totalGanhos = (new MovimentosRepository($this->usuario))->movimentoMensal();

                $result = (new CalculoTetoRecebimento())
                    ->usuario($this->usuario)
                    ->valor($totalAPagar)
                    ->totalGanho($totalGanhos)
                    ->calcular();

                if ($result['valor'] > 0) {
                    $valorPago = $result['valor'];

                    $ultimoMovimento = $this->usuario->ultimoMovimento();

                    $dadosMovimento = [
                        'valor_manipulado' => $result['valor'],
                        'valor_excedente' => $result['valor_excedente'],
                        'saldo_anterior' => ! $ultimoMovimento ? 0 : $ultimoMovimento->saldo,
                        'saldo' => ! $ultimoMovimento ? $result['valor'] : $result['valor'] + $ultimoMovimento->saldo,
                        'descricao' => "Bônus de equipe, referente ao depósito #{$this->pedido->id}",
                        'responsavel_user_id' => $this->usuarioResponsavel->id,
                        'user_id' => $this->usuario->id,
                        'operacao_id' => 17,
                        'pedido_id' => $this->pedido->id,
                        'item_id' => $this->pedido->itens->first()->item->id,
                        'titulo_id' => $this->usuario->titulo_id,
                    ];

                    \Log::info('Pago: '.mascaraMoeda($this->sistema->moeda, $result['valor'], 2, true).' - %'.$this->ultimoPercentual);
                    \Log::info('inserido movimento equiparacao', $dadosMovimento);

                    $ultimoMovimento = Movimentos::create($dadosMovimento);
                }
            }

            if ($valorPago > $this->sistema->royalties_valor_minimo_bonus) {
                $valorRoyalties = round((($valorPago * $this->sistema->royalties_porcentagem) / 100), 2);
                $valorResidual = round((($valorRoyalties * $this->sistema->royalties_porcentagem_distribuir) / 100) / $this->sistema->profundidade_pagamento_matriz, 2);

                \Log::info('$valorPago:'.$valorPago);
                \Log::info('profundidade_pagamento_matriz = '.$this->sistema->profundidade_pagamento_matriz);
                \Log::info('$valorRoyalties = round((($valorPago * $this->sistema->royalties_porcentagem) / 100), 2)');
                \Log::info('$valorResidual = round((($valorRoyalties * $this->sistema->royalties_porcentagem_distribuir) / 100) / $this->sistema->profundidade_pagamento_matriz, 2)');

                \Log::info('$valorRoyalties:'.$valorRoyalties);
                \Log::info('$valorResidual:'.$valorResidual);

                (new PedidoService())
                    ->usuario($this->usuario)
                    ->pedido($this->pedido)
                    ->valor($valorPago - $valorRoyalties)
                    ->operacao(17)
                    ->movimentoReferencia($ultimoMovimento)
                    ->pagarRentabilidade();

                $dadosMovimentoRoyalties = [
                    'valor_manipulado' => $valorRoyalties,
                    'valor_excedente' => 0,
                    'saldo_anterior' => $ultimoMovimento->saldo,
                    'saldo' =>  $ultimoMovimento->saldo - $valorRoyalties,
                    'descricao' => "Coleta de Royalties, referente ao bônus residual do depósito #{$this->pedido->id}",
                    'responsavel_user_id' => $this->usuarioResponsavel->id,
                    'user_id' => $this->usuario->id,
                    'operacao_id' => 31,
                    'pedido_id' => $this->pedido->id,
                    'item_id' => $this->pedido->itens->first()->item->id,
                    'titulo_id' => $this->usuario->titulo_id,
                ];

                Movimentos::create($dadosMovimentoRoyalties);

                \Log::info('inserindo movimento - 5%', $dadosMovimentoRoyalties);

                if ($valorResidual > 0) {
                    (new PagarMovimentoUniLevel())
                        ->usuario($this->usuario->indicador)
                        ->pedidoReferencia($this->pedido)
                        ->valor($valorResidual)
                        ->operacao(27)
                        ->profundidade($this->sistema->profundidade_pagamento_matriz)
                        ->descricaoMovimento('Royalties - Bônus Residual nível %d de %d - ref. Doc. Nº %d')
                        ->pagar();
                }
            }else{
                Log::info("Não paga royalties, valor mínimo do bônus para pagar royalties é de {$this->sistema->royalties_valor_minimo_bonus}, valor do bônus pago: {$valorPago}");
            }

            $this->valorPago += $totalAPagar;
        }

        return self::subirRede();
    }
}
