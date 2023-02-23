<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

use App\Models\User;
use App\Models\ItensPedido;
use App\Models\RedeBinaria;
use App\Models\ExtratoBinario;

class PagamentoPontosBinarios
{
    private $item_pedido = null;
    private $user;
    private $redeBinaria;
    private $pontos;
    private $operacao_id;
    private $lado;
    private $descricao = null;
    private $resultadoCanculoBinario = [];

    public function __construct()
    {
        \Log::info('01010101010101010101010101010101 Construtor Pagamentos pontos binarios 01010101010101010101010101010101');
        $this->redeBinaria = new RedeBinaria();
    }

    /**
     * @param  User
     * @return [type]
     */
    public function user(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @param  ItensPedido
     * @return [type]
     */
    public function item(ItensPedido $item_pedido)
    {
        $this->item_pedido = $item_pedido;
        $this->pontos = $this->item_pedido->pontos_binarios;

        return $this;
    }

    /**
     * @param  int
     * @return [type]
     */
    public function operacao(int $operacao_id)
    {
        $this->operacao_id = $operacao_id;

        return $this;
    }

    /**
     * @param  [type]
     * @return [type]
     */
    public function pontos($pontos)
    {
        $this->pontos = $pontos;

        return $this;
    }

    public function descricao($descricao)
    {
        $this->descricao = $descricao;

        if (empty($descricao)) {
            $this->descricao = null;
        }

        return $this;
    }

    public function lado($lado)
    {
        $this->lado = $lado;

        return $this;
    }

    /**
     * @param  User
     * @param  [type]
     * @return [type]
     */
    public function pagar()
    {
        if ($this->user->id > 2) {
            if ($this->user->titulo->recebe_pontuacao == 1) {
                $this->inserirPontos();
                \Log::info('Titulo pontua');
            }

            $redeBinariaSuperior = $this->redeBinaria->redeSuperior($this->user->id);

            $this->lado = $this->user->id == $redeBinariaSuperior->esquerda ? 1 : 2;
            $this->user = $redeBinariaSuperior->usuario;

            $this->pagar();
        }

        return true;
    }

    /**
     * @return [type]
     */
    private function userExtratoBinario()
    {
        if ($this->user->extratoBinario->last() instanceof ExtratoBinario) {
            return $this->userHasExtrato($this->user->extratoBinario->last());
        }

        return $this->userSetExtrato();
    }

    /**
     * @param  ExtratoBinario $extratoBinario
     * @return array
     */
    private function userHasExtrato(ExtratoBinario $extratoBinario)
    {
        self::calculoBinario($extratoBinario);

        return [
                'pontos'             => $this->pontos,
                'user_id'            => $this->user->id,
                'pedido_id'          => $this->item_pedido ? $this->item_pedido->pedido_id : null,
                'operacao_id'        => $this->operacao_id,
                'saldo_anterior'     => 0,
                'saldo'              => $extratoBinario->saldo + $this->pontos,
                'referencia'         => $extratoBinario->id,
                'acumulado_direita'  => $this->resultadoCanculoBinario['acumuladoDireita'],
                'acumulado_esquerda' => $this->resultadoCanculoBinario['acumuladoEsquerda'],
                'acumulado_total'    => 0,
                'saldo_direita'      => $this->resultadoCanculoBinario['saldoDireita'],
                'saldo_esquerda'     => $this->resultadoCanculoBinario['saldoEsquerda'],
                'user_responsavel'   => \Auth::user()->id,
                'descricao'         => $this->descricao,

            ];
    }

    private function calculoBinario(ExtratoBinario $extratoBinario)
    {
        self::credito($extratoBinario);

        if ($this->operacao_id == 24) {
            self::debito($extratoBinario);
        }

        return true;
    }

    private function credito(ExtratoBinario $extratoBinario)
    {
        $this->resultadoCanculoBinario['saldoDireita'] = $this->lado == 2 ? $extratoBinario->saldo_direita + $this->pontos : $extratoBinario->saldo_direita;
        $this->resultadoCanculoBinario['acumuladoDireita'] = $this->lado == 2 ? $extratoBinario->acumulado_direita + $this->pontos : $extratoBinario->acumulado_direita;

        $this->resultadoCanculoBinario['saldoEsquerda'] = $this->lado == 1 ? $extratoBinario->saldo_esquerda + $this->pontos : $extratoBinario->saldo_esquerda;
        $this->resultadoCanculoBinario['acumuladoEsquerda'] = $this->lado == 1 ? $extratoBinario->acumulado_esquerda + $this->pontos : $extratoBinario->acumulado_esquerda;

        return true;
    }

    private function debito(ExtratoBinario $extratoBinario)
    {
        $this->resultadoCanculoBinario['saldoDireita'] = $this->lado == 2 ? $extratoBinario->saldo_direita - $this->pontos : $extratoBinario->saldo_direita;
        $this->resultadoCanculoBinario['acumuladoDireita'] = $this->lado == 2 ? $extratoBinario->acumulado_direita - $this->pontos : $extratoBinario->acumulado_direita;

        $this->resultadoCanculoBinario['saldoEsquerda'] = $this->lado == 1 ? $extratoBinario->saldo_esquerda - $this->pontos : $extratoBinario->saldo_esquerda;
        $this->resultadoCanculoBinario['acumuladoEsquerda'] = $this->lado == 1 ? $extratoBinario->acumulado_esquerda - $this->pontos : $extratoBinario->acumulado_esquerda;

        return true;
    }

    /**
     * @return array
     */
    private function userSetExtrato()
    {
        return [
                'pontos'             => $this->pontos,
                'user_id'            => $this->user->id,
                'pedido_id'          => $this->item_pedido ? $this->item_pedido->pedido_id : null,
                'operacao_id'        => $this->operacao_id,
                'saldo_anterior'     => 0,
                'saldo'              => 0,
                'referencia'         => '',
                'acumulado_direita'  => $this->lado == 2 ? $this->pontos : 0,
                'acumulado_esquerda' => $this->lado == 1 ? $this->pontos : 0,
                'acumulado_total'    => $this->pontos,
                'saldo_direita'      => $this->lado == 2 ? $this->pontos : 0,
                'saldo_esquerda'     => $this->lado == 1 ? $this->pontos : 0,
                'user_responsavel'   => \Auth::user()->id,
                'descricao'         => $this->descricao,
        ];
    }

    /**
     * @return bool
     */
    public function inserirPontos()
    {
        if ($this->user->id < 3) {
            return false;
        }

        $dadosExtratoBinario = $this->userExtratoBinario();

        $extratoAtual = ExtratoBinario::create($dadosExtratoBinario);

        if ($this->user->recebe_pagamento_binario == 0) {
            $this->verificaPontuacaoPernas($extratoAtual, $this->user);
        }

        \Log::info("Pago {$this->pontos} pontos para:  #".$this->user->id);

        return true;
    }

    /**
     * Se o usuário tem pontuação nas duas pernas ele esta apto a receber o calculo binario.
     *
     * @param ExtratoBinario $extrato
     * @param User $user
     */
    private function verificaPontuacaoPernas(ExtratoBinario $extrato, User $user)
    {
        \Log::info('Não esta habilitado para receber calculo binário');

        if ($extrato->saldo_esquerda > 0 && $extrato->saldo_direita > 0) {
            $user->update(['recebe_pagamento_binario' => 1]);
            \Log::info('Habilitado para receber calculo binário');
        }
    }
}
