<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

use App\Models\User;
use App\Models\Pedidos;
use App\Models\ConfiguracaoBonus;
use Illuminate\Support\Facades\DB;
use App\Repositories\MovimentosRepository;

class PagaBonusSetupService
{
    private $pedido;
    private $usuarioNivel1;
    private $nivelAtual;
    private $nivelMaximoPagamentoBonus;
    private $usuarioAtual;
    private $configuracaoPagamentoBonusSetupItem;
    private $usuarioResponsavel;
    private $taxaPercentualEmpresa;
    private $valorMinimoBonusCobrancaTaxa;
    private $usuarioPedido;

    public function pedido(Pedidos $pedido):self
    {
        $this->pedido = $pedido;
        $this->configuracaoPagamentoBonusSetupItem = ConfiguracaoBonus::find($this->pedido->item()->configuracao_bonus_adesao_id);

        return $this;
    }

    public function usuarioNivel1(User $usuarioNivel1):self
    {
        $this->usuarioNivel1 = $usuarioNivel1;

        return $this;
    }

    public function usuarioPedido(User $usuarioPedido)
    {
        $this->usuarioPedido = $usuarioPedido;

        return $this;
    }

    public function usuarioResponsavel(User $usuarioResponsavel):self
    {
        $this->usuarioResponsavel = $usuarioResponsavel;

        return $this;
    }

    public function nivelMaximoPagamentoBonus($nivelMaximoPagamentoBonus):self
    {
        $this->nivelMaximoPagamentoBonus = $nivelMaximoPagamentoBonus;

        return $this;
    }

    public function taxaPercentualEmpresa($taxaPercentualEmpresa):self
    {
        $this->taxaPercentualEmpresa = $taxaPercentualEmpresa;

        return $this;
    }

    public function valorMinimoBonusCobrancaTaxa($valorMinimoBonusCobrancaTaxa):self
    {
        $this->valorMinimoBonusCobrancaTaxa = decimal($valorMinimoBonusCobrancaTaxa);

        return $this;
    }

    public function processar():bool
    {
        $sucesso = false;

        if ($this->pedido->item()->pagar_bonus) {
            $this->usuarioAtual = $this->usuarioNivel1;
            $this->nivelAtual = 1;

            DB::beginTransaction();

            try {
                while ($this->nivelAtual <= $this->nivelMaximoPagamentoBonus && $this->usuarioAtual->id > 2) {
                    $valorBonus = self::calcularValorBonus();
                    $valorTaxaEmpresa = self::calcularValorTaxaEmpresa($valorBonus);

                    if ($valorBonus > 0) {
                        \Log::warning("Valor Bônus: {$valorBonus}");
                        $valorBonus = $this->calcularValorBonusTeto($valorBonus);

                        $tipoPagamentoBonus = $this->pedido->tipo_pedido == 3 ? config('constants.pagamento_bonus_tipo')[1] : config('constants.pagamento_bonus_tipo')[2];

                        $mensagem = "Bônus de nível {$this->nivelAtual} sobre ".($this->pedido->tipo_pedido == 3 ? 'credenciamento de Agente' : 'Taxa ou Credenciamento')." #{$this->pedido->id} de {$this->usuarioPedido->name}";

                        $bonusParametros = (new PagaBonusParametros())
                            ->setPedidoOrigemBonus($this->pedido)
                            ->setUsuario($this->usuarioAtual)
                            ->setUsuarioResponsavel($this->usuarioResponsavel)
                            ->setValorBonus($valorBonus)
                            ->setValorTaxaEmpresa($valorTaxaEmpresa)
                            ->setNivel($this->nivelAtual)
                            ->setOperacaoMovimento($this->pedido->tipo_pedido == 3 ? 20 : 1)
                            ->setDescricaoMovimento($mensagem)
                            ->setTipoPagamentoBonus($tipoPagamentoBonus);

                        $pagaBonusService = new PagaBonusService($bonusParametros);
                        $pagaBonusService->processar();
                    }

                    $this->nivelAtual++;
                    $this->usuarioAtual = $this->usuarioAtual->indicador;
                }

                $sucesso = true;

                DB::commit();
            } catch (ModelNotFoundException $e) {
                DB::rollBack();
            }
        }

        return false;
    }

    private function calcularValorBonus():float
    {
        $valorBonus = 0;

        $configuracaoPagamentoBonusSetupTitulo = ConfiguracaoBonus::find($this->usuarioAtual->titulo->configuracao_bonus_adesao_id);

        if ($this->configuracaoPagamentoBonusSetupItem && $this->configuracaoPagamentoBonusSetupItem->status == 1) {
            $valorBonus = 0;

            if (isset($this->configuracaoPagamentoBonusSetupItem->itens[$this->nivelAtual])) {
                $valorBonus = decimal($this->pedido->valor_total * ($this->configuracaoPagamentoBonusSetupItem->itens[$this->nivelAtual]['percentual'] / 100));

                $valorBonus = decimal($valorBonus + $this->configuracaoPagamentoBonusSetupItem->itens[$this->nivelAtual]['valor_fixo']);
            }
        }

        if ($this->pedido->item()->pagar_bonus_titulo) {
            if ($configuracaoPagamentoBonusSetupTitulo && $configuracaoPagamentoBonusSetupTitulo->status == 1) {
                $valorBonus = 0;

                if (isset($configuracaoPagamentoBonusSetupTitulo->itens[$this->nivelAtual])) {
                    $valorBonus = decimal($this->pedido->valor_total * ($configuracaoPagamentoBonusSetupTitulo->itens[$this->nivelAtual]['percentual'] / 100));

                    $valorBonus = decimal($valorBonus + $configuracaoPagamentoBonusSetupTitulo->itens[$this->nivelAtual]['valor_fixo']);
                }
            }
        }

        return $valorBonus;
    }

    private function calcularValorTaxaEmpresa($valorBonus):float
    {
        $valorTaxaEmpresa = 0;

        if ($this->taxaPercentualEmpresa > 0 && $valorBonus >= $this->valorMinimoBonusCobrancaTaxa) {
            $valorTaxaEmpresa = decimal($valorBonus * ($this->taxaPercentualEmpresa / 100));
        }

        return $valorTaxaEmpresa;
    }

    private function calcularValorBonusTeto($valorBonus):float
    {
        $valorBonusTetoCalculado = 0;

        if ($this->usuarioAtual->status == 1 && $this->usuarioAtual->titulo->habilita_rede == 1) {
            if ($valorBonus > 0) {
                $totalGanhos = (new MovimentosRepository($this->usuarioAtual))->movimentoMensal();

                $valotAtualizado = (new CalculoTetoRecebimento())
                    ->usuario($this->usuarioAtual)
                    ->valor($valorBonus)
                    ->totalGanho($totalGanhos)
                    ->calcular();

                $valorBonusTetoCalculado = $valotAtualizado['valor'];
            }
        }

        return $valorBonusTetoCalculado;
    }
}
