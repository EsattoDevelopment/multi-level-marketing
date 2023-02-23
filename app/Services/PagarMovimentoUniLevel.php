<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

use App\Models\User;
use App\Models\Pedidos;
use App\Models\Movimentos;
use Illuminate\Support\Facades\Auth;

class PagarMovimentoUniLevel
{
    private $usuario;
    private $valor;
    private $profundidade;
    private $operacao;
    private $pedidoReferencia;
    private $item;
    private $profundidadeAtual;
    private $descricaoMovimento;

    public function usuario(User $usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function valor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    public function profundidade($profundidade)
    {
        $this->profundidade = $profundidade;

        return $this;
    }

    public function operacao($operacao)
    {
        $this->operacao = $operacao;

        return $this;
    }

    public function pedidoReferencia(Pedidos $pedido)
    {
        $this->pedidoReferencia = $pedido;
        $this->item = $this->pedidoReferencia->item();

        return $this;
    }

    public function descricaoMovimento($descricaoMovimento)
    {
        $this->descricaoMovimento = $descricaoMovimento;

        return $this;
    }

    public function __construct()
    {
        \Log::info('00000000000000000 Inicio do pagamento UniLevel 00000000000000000');
        $this->valor = 0;
        $this->profundidade = 0;
        $this->operacao = 0;
        $this->pedidoReferencia = null;
        $this->item = null;
        $this->profundidadeAtual = 0;
    }

    public function __destruct()
    {
        \Log::info('00000000000000000 Fim do pagamento UniLevel 00000000000000000');
    }

    public function pagar()
    {
        $this->profundidadeAtual++;
        if ($this->profundidadeAtual <= $this->profundidade) {
            if ($this->usuario->id > 2) {
                if ($this->usuario->titulo->recebe_pontuacao == 1 && $this->usuario->titulo->habilita_rede == 1) {
                    $result = (new CalculoTetoRecebimento())
                        ->usuario($this->usuario)
                        ->valor($this->valor)
                        ->calcular();

                    if ($result['valor'] > 0) {

                        //resgata ultima movimentação
                        $ultimoMovimento = Movimentos::ultimoMovimentoUserId($this->usuario->id);

                        $dadosMovimento = [
                            'valor_manipulado' => $result['valor'],
                            'saldo_anterior' => ! $ultimoMovimento ? 0 : $ultimoMovimento->saldo,
                            'saldo' => ! $ultimoMovimento ? $result['valor'] : $result['valor'] + $ultimoMovimento->saldo,
                            'pedido_id' => $this->pedidoReferencia->id,
                            //'documento' => $dadospagamento->id,
                            'descricao' => sprintf($this->descricaoMovimento, $this->profundidadeAtual, $this->profundidade, $this->pedidoReferencia->id),
                            'responsavel_user_id' => Auth::user() ? Auth::user()->id : 1,
                            'user_id' => $this->usuario->id,
                            'item_id' => $this->item->id,
                            'titulo_id' => $this->usuario->titulo->id,
                            'operacao_id' => $this->operacao,
                        ];

                        \log::info('Pago Royalties para #'.$this->usuario->id.' - '.$this->usuario->name.' - Profundidade: '.$this->profundidadeAtual, $dadosMovimento);

                        Movimentos::create($dadosMovimento);
                    }
                }

                //carrega usuário novo e seu movimento
                $this->usuario = $this->usuario->indicador;

                return $this->pagar();
            }

            \Log::info('Atingiu o usuario empresa!');
        }

        return false;
    }
}
