<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

use App\Models\User;
use App\Models\Pedidos;
use App\Models\Sistema;
use App\Models\PontosPessoais;
use App\Saude\Domains\Mensalidade;
use App\Models\PontosEquipeUnilevel;
use App\Models\PontosEquipeEquiparacao;

class PagarPontosServices
{
    private $pontos;
    private $usuario;
    private $ultimosPontos;
    private $model;
    private $pedido = null;
    private $mensalidade = null;
    private $operacao;
    private $profundidade;
    private $profundidadePaga = 0;

    public function pontos($pontos)
    {
        $this->pontos = $pontos;

        return $this;
    }

    public function mensalidade(Mensalidade $mensalidade)
    {
        $this->mensalidade = $mensalidade;

        return $this;
    }

    public function pedido(Pedidos $pedido)
    {
        $this->pedido = $pedido;

        return $this;
    }

    public function operacao($operacao)
    {
        $this->operacao = $operacao;

        return $this;
    }

    public function usuario(User $usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function model($model)
    {
        $this->model = $model;

        return $this;
    }

    public function ultimosPontos($ultimosPontos)
    {
        $this->ultimosPontos = $ultimosPontos;

        return $this;
    }

    public function __construct()
    {
        \Log::info('00000000000000000 Inicio do pagamento de pontos 00000000000000000');
        $this->profundidade = Sistema::findOrFail(1)->profundidade_pagamento_matriz;
    }

    public function __destruct()
    {
        \Log::info('00000000000000000 Fim do pagamento de pontos 00000000000000000');
    }

    /**
     * verifica onde os GMilhas devem ser pagos (Pessoais, Equiparação ou unilevel).
     *
     * @return bool
     */
    public function pagar()
    {
        $class = get_class($this->model);

        switch ($class) {
                case PontosPessoais::class:
                    $retorno = self::inserirPontos();
                    break;
                case PontosEquipeEquiparacao::class:
                    $retorno = self::inserirPontosRede('pontosEquiparacao');
                    break;
                case PontosEquipeUnilevel::class:
                    $retorno = self::inserirPontosRedeUnilevel();
                    break;
            }

        return $retorno;
    }

    /**
     * Insere os pontos no usuário.
     *
     * @return bool
     */
    private function inserirPontos()
    {
        if ($this->pontos > 0) {
            $dados = [
                'pontos' => $this->pontos,
                'saldo_anterior' => $this->ultimosPontos ? $this->ultimosPontos->saldo : 0,
                'saldo' => $this->ultimosPontos ? $this->ultimosPontos->saldo + $this->pontos : $this->pontos,
                'pedido_id' => $this->pedido ? $this->pedido->id : null,
                'mensalidade_id' => $this->mensalidade ? $this->mensalidade->id : null,
                'user_id' => $this->usuario->id,
                'operacao_id' => $this->operacao, //TODO verificar operaçao
            ];

            \Log::info('Inseriu pontos para #'.$this->usuario->id.' - '.$this->usuario->name, $dados);
            $this->model->create($dados);
        } else {
            \Log::warning('0 Pontos');
        }

        return true;
    }

    /**
     * insere pontos na rede.
     *
     * @param $tipo tipo de pontos
     * @return bool
     */
    private function inserirPontosRede($tipo)
    {
        if ($this->usuario->id > 2) {
            if ($this->usuario->titulo->recebe_pontuacao == 1 && $this->usuario->titulo->habilita_rede == 1) {
                $this->inserirPontos();
            }

            //carrega usuário novo e seu movimento
            $this->usuario = $this->usuario->indicador;
            $this->ultimosPontos = $this->usuario->$tipo->sortBy('id')->last();

            return $this->pagar();
        }

        \Log::info('Atingiu o usuario empresa!');

        return false;
    }

    /**
     * insere pontos no unilevel.
     *
     * @return bool
     */
    private function inserirPontosRedeUnilevel()
    {
        if ($this->profundidadePaga < $this->profundidade) {
            \Log::info("Pagando profundidade {$this->profundidadePaga} - GMilhas de Equipe");

            $this->profundidadePaga++;

            return self::inserirPontosRede('pontosUnilevel');
        }

        \Log::info('Chegou a profundidade maxima!');

        return false;
    }
}
