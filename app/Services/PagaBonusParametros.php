<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 *
 */
namespace App\Services;

use App\Models\Itens;
use App\Models\Pedidos;
use App\Models\Titulos;
use App\Models\User;

class PagaBonusParametros
{
    private $pedidoOrigemBonus;
    private $itemOrigemBonus;
    private $usuarioOrigemBonus;
    private $titulo;
    private $usuario;
    private $valorBonus;
    private $valorTaxaEmpresa;
    private $usuarioResponsavel;
    private $tipoPagamentoBonus;
    private $nivel;
    private $operacaoMovimento;
    private $descricaoMovimento;

    public function getPedidoOrigemBonus():Pedidos
    {
        return $this->pedidoOrigemBonus;
    }

    public function setPedidoOrigemBonus(Pedidos $pedidoOrigemBonus):self
    {
        $this->pedidoOrigemBonus = $pedidoOrigemBonus;

        $this->itemOrigemBonus = $this->pedidoOrigemBonus->item();

        $this->usuarioOrigemBonus = $this->pedidoOrigemBonus->user();

        return  $this;
    }

    public function setPedidoOrigemBonusId($pedidoOrigemBonusId):self
    {
        self::setPedidoOrigemBonus(Pedidos::find($pedidoOrigemBonusId));

        return  $this;
    }

    public function getItemOrigemBonus():Itens
    {
        return $this->itemOrigemBonus;
    }

    public function getUsuarioOrigemBonus($usuarioOrigemBonus):User
    {
        return $this->usuarioOrigemBonus;
    }

    public function getUsuario():User
    {
        return $this->usuario;
    }

    public function setUsuario(User $usuario):self
    {
        $this->usuario = $usuario;

        $this->titulo = $this->usuario->titulo;

        return $this;
    }

    public function setUsuarioId($usuarioId):self
    {
        self::setUsuario(User::find($usuarioId));

        return $this;
    }

    public function getTitulo():Titulos
    {
        return $this->titulo;
    }

    public  function getValorBonus():float
    {
        return $this->valorBonus;
    }

    public  function  setValorBonus($valorBonus):self
    {
        $this->valorBonus = decimal($valorBonus);

        return $this;
    }

    public function getValorTaxaEmpresa():float
    {
        return $this->valorTaxaEmpresa;
    }

    public function setValorTaxaEmpresa($valorTaxaEmpresa):self
    {
        $this->valorTaxaEmpresa = decimal($valorTaxaEmpresa);

        return $this;
    }

    public function getUsuarioResponsavel():User
    {
        return $this->usuarioResponsavel;
    }

    public function setUsuarioResponsavel(User $usuarioResponsavel):self
    {
        $this->usuarioResponsavel = $usuarioResponsavel;

        return $this;
    }

    public function setUsuarioResponsavelId(User $usuarioResponsavelId):self
    {
        self::setUsuarioResponsavel(User::find($usuarioResponsavelId));

        return $this;
    }

    public function getTipoPagamentoBonus():string
    {
        return $this->tipoPagamentoBonus;
    }

    public function setTipoPagamentoBonus($tipoPagamentoBonus)
    {
        $this->tipoPagamentoBonus = $tipoPagamentoBonus;

        return $this;
    }

    public function getNivel():int
    {
        return $this->nivel;
    }

    public function setNivel($nivel):self
    {
        $this->nivel = (int)$nivel;

        return $this;
    }

    public function getOperacaoMovimento():int
    {
        return $this->operacaoMovimento;
    }

    public function setOperacaoMovimento($operacaoMovimento):self
    {
        $this->operacaoMovimento = $operacaoMovimento;

        return  $this;
    }

    public function getDescricaoMovimento():string
    {
        return $this->descricaoMovimento;
    }

    public function setDescricaoMovimento($descricaoMovimento):self
    {
        $this->descricaoMovimento = $descricaoMovimento;

        return $this;
    }
}
