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
use App\Models\Movimentos;
use App\Models\ItensPedido;

class CalculoTetoRecebimento
{
    private $item = null;
    private $pedido;
    private $itemPedido = null;
    private $usuario;
    private $totalMovimentoMes;
    private $valor = 0.0;
    private $tetoTitulo = false;
    private $retorno = [];

    public function dadosItem(ItensPedido $itemPedido)
    {
        $this->itemPedido = $itemPedido;
        $this->item = $itemPedido->item;
        $this->valor = $itemPedido->quantidade * $this->item->bonus_indicador;

        return $this;
    }

    public function item(Itens $item)
    {
        $this->item = $item;

        return $this;
    }

    public function valor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    public function totalGanho($totalMovimentoMes)
    {
        $this->totalMovimentoMes = $totalMovimentoMes;

        return $this;
    }

    public function usuario(User $usuario)
    {
        \Log::critical("Usuario #{$usuario->id} - {$usuario->name} - {$usuario->status} - {$usuario->titulo_id}");

        $this->usuario = $usuario;
        $this->pedido = $usuario->pedidos()->where('status', 2)->where('tipo_pedido', '<>', 3)->first();

        return $this;
    }

    public function calcular()
    {
        $this->retorno['valor'] = $this->valor;

        self::calcTetoMensalTitulo();

        return $this->retorno;
    }

    /**
     * verifica se teto mensal do titulo foi atingido.
     *
     * @return bool
     */
    private function calcTetoMensalTitulo()
    {
        \Log::info("Calculo de teto mensal titulo: #{$this->usuario->titulo->id} - {$this->usuario->titulo->name}");

        /*       $inicio = Carbon::now()->firstOfMonth()->format('Y-m-d').' 00:00:00';
               $fim = Carbon::now()->lastOfMonth()->format('Y-m-d').' 23:59:59';

               $totalMovimentoMes = (float) Movimentos::whereBetween('created_at', [$inicio, $fim])
                   ->whereIn('operacao_id', [1, 3, 6, 17, 20, 27])
                   ->whereUserId($this->usuario->id)
                   ->sum('valor_manipulado');*/

        if ($this->totalMovimentoMes > $this->usuario->titulo->teto_mensal_financeiro) {
            $this->tetoTitulo = true;
            $this->retorno['valor'] = 0;
            \Log::info('Usuario atingiu o teto de recebimento do titulo.');

            return false;
        }

        return $this->tetoTitulo = self::calcTeto();
    }

    /**
     * @param $movimento
     * @return bool
     */
    private function calcTeto()
    {
        \Log::info('Calcula teto do titulo!');

        $valorMaximo = $this->usuario->titulo->teto_mensal_financeiro - $this->totalMovimentoMes;

        if ($valorMaximo > $this->valor) {
            \Log::info("R$ {$this->valor} - Valor nao excede o teto!");
            $this->retorno['valor'] = $this->valor;
            $this->retorno['valor_excedente'] = 0;

            return false;
        }

        $this->retorno['valor'] = $valorMaximo;
        $this->retorno['valor_excedente'] = $this->valor - $valorMaximo;

        \Log::info('Valor da bonificaçao excede o teto!');
        \Log::info("Valor a ser pago $ {$valorMaximo}");
        \Log::info("Valor excedido $ {$this->retorno['valor_excedente']}");

        return true;
    }
}
