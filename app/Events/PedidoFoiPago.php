<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Events;

use App\Models\Itens;
use App\Models\Pedidos;
use App\Models\Sistema;
use App\Models\DadosPagamento;
use Illuminate\Queue\SerializesModels;
use App\Domains\Configuracao\ConfiguracaoRepository;

class PedidoFoiPago extends Event
{
    use SerializesModels;

    private $pedido;
    private $dadosPagamento;
    private $itens;
    private $usuario;
    private $configuracao;
    private $equiparacaoPago = 0;
    private $equiparacaoNivel = 4;
    private $patrocinador;
    public $sistema;

    /**
     * @return mixed
     */
    public function getPatrocinador()
    {
        return $this->patrocinador;
    }

    /**
     * @param mixed $patrocinador
     */
    public function setPatrocinador($patrocinador)
    {
        $this->patrocinador = $patrocinador;
    }

    /**
     * @return int
     */
    public function getEquiparacaoNivel()
    {
        return $this->equiparacaoNivel;
    }

    /**
     * @param int $equiparacaoNivel
     */
    public function setEquiparacaoNivel($equiparacaoNivel)
    {
        $this->equiparacaoNivel = $equiparacaoNivel;
    }

    /**
     * @return mixed
     */
    public function getEquiparacaoPago()
    {
        return $this->equiparacaoPago;
    }

    /**
     * @param mixed $equiparacaoPago
     */
    public function setEquiparacaoPago($equiparacaoPago)
    {
        $this->equiparacaoPago += $equiparacaoPago;
    }

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Pedidos $pedido)
    {
        $configuracao = new ConfiguracaoRepository();

        $this->pedido = $pedido;

        $this->setConfiguracao($configuracao->getAll()->first());

        $this->dadosPagamento = $this->pedido->getRelation('dadosPagamento');

        $this->itens = $this->pedido->itens;

        $this->usuario = $this->pedido->user;

        $this->patrocinador = $this->usuario->indicador;

        $this->sistema = Sistema::findOrFail(1);
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }

    /**
     * @return mixed
     */
    public function getConfiguracao()
    {
        return $this->configuracao;
    }

    /**
     * @param mixed $configuracao
     */
    public function setConfiguracao($configuracao)
    {
        $this->configuracao = $configuracao;
    }

    /**
     * @return Pedidos
     */
    public function getPedido()
    {
        return $this->pedido;
    }

    /**
     * @return DadosPagamento
     */
    public function getDadosPagamento()
    {
        return $this->dadosPagamento;
    }

    /**
     * @return Itens
     */
    public function getItens()
    {
        return $this->itens;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}
