<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

use App\Models\Pedidos;
use App\Models\Sistema;

class RentabilidadeServices
{
    private $rentabilidade;
    private $pedidos;
    private $sistema;

    public function __construct()
    {
        $this->sistema = Sistema::findOrFail(1);
    }

    public function rentabilidade($rentabilidade)
    {
        $this->rentabilidade = $rentabilidade;

        return $this;
    }

    public function pagar()
    {
        $this->pedidos = Pedidos::where('status', 2)->where('tipo_pedido', '<>', 3)->get();

        self::verificaPedidos();
    }

    private function verificaPedidos()
    {
        foreach ($this->pedidos as $pedido) {
            \Log::info("Pedido do usuário #{$pedido->user_id}");
            \Log::info("Pedido N #{$pedido->id}");
            self::capitalizar($pedido);
        }
    }

    private function capitalizar(Pedidos $pedido)
    {
        $itemPedido = $pedido->itens->first();

        if ($this->sistema->rendimento_item) {
            self::capitalizarItem($pedido, $itemPedido);
        }

        if ($this->sistema->rendimento_titulo) {
            self::capitalizarTitulo($pedido, $itemPedido);
        }
    }

    private function capitalizarItem($pedido, $itemPedido)
    {
        $rentabilidade = $this->rentabilidade->where('item_id', $itemPedido->item_id)->first();

        if (! $rentabilidade) {
            return false;
        }

        \Log::info('Item ---> '.$rentabilidade->getRelation('item')->name);
        $valorPercentual = $rentabilidade->percentual * $itemPedido->valor_total;

        self::inserirCapitalizacao($pedido, $valorPercentual, $rentabilidade);
    }

    private function capitalizarTitulo($pedido, $itemPedido)
    {
        $usuario = $pedido->usuario;
        $rentabilidade = $this->rentabilidade->where('titulo_id', $usuario->titulo_id)->first();

        if (! $rentabilidade) {
            return false;
        }

        \Log::info('Titulo ---> '.$rentabilidade->getRelation('titulo')->name);

        $valorPercentual = $rentabilidade->percentual * $itemPedido->valor_total;

        self::inserirCapitalizacao($pedido, $valorPercentual, $rentabilidade);
    }

    private function inserirCapitalizacao(Pedidos $pedido, $valorPercentual, $rentabilidade)
    {
        \Log::info('Valor do depósito: '.mascaraMoeda($this->sistema->moeda, $pedido->valor_total, 2, true));
        \Log::info("Rentabilidade % {$rentabilidade->percentual}");
        \Log::info('Rentabilidade '.mascaraMoeda($this->sistema->moeda, $valorPercentual, 2, true));

        if ($rentabilidade->percentual > 0) {
            $retorno = (new PedidoService())
                    ->usuario($pedido->usuario)
                    ->valor(round($valorPercentual, 2)) //arredonda valor antes de enviar
                    ->pedido($pedido)
                    ->rentabilidade($rentabilidade)
                    ->operacao(7)
                    ->pagarRentabilidade();
        }

        \Log::info('Valor Fixo '.mascaraMoeda($this->sistema->moeda, $rentabilidade->valor_fixo, 2, true));

        if ($rentabilidade->valor_fixo > 0) {
            (new PedidoService())
                    ->usuario($pedido->usuario)
                    ->valor($rentabilidade->valor_fixo)
                    ->pedido($pedido)
                    ->ultimoMovimentoInterno(isset($retorno['movimento']) ? $retorno['movimento'] : null)
                    ->rentabilidade($rentabilidade)
                    ->operacao(7)
                    ->pagarRentabilidade();
        }

        \Log::info("xxxxxxxxxx Inserido valores xxxxxxxxxx\n");
    }
}
