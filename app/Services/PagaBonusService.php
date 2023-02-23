<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

use App\Models\Movimentos;
use Illuminate\Support\Facades\DB;

class PagaBonusService
{
    private $bonusParametros;

    public function __construct(PagaBonusParametros $bonusParametros)
    {
        $this->bonusParametros = $bonusParametros;
    }

    public function processar():bool
    {
        $sucesso = false;

        try {
            DB::beginTransaction();

            if (self::pagarBonus()) {
                $sucesso = true;

                if ($this->bonusParametros->getValorTaxaEmpresa() > 0) {
                    $sucesso = self::coletarTaxaEmpresa();
                }
            }

            if ($sucesso) {
                DB::commit();
            } else {
                DB::rollBack();
            }
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
        }

        return $sucesso;
    }

    public function pagarBonus()
    {
        $sucesso = false;

        if ($this->bonusParametros->getValorBonus() > 0) {
            try {
                $ultimoMovimento = Movimentos::ultimoMovimentoUserId($this->bonusParametros->getUsuario()->id);

                $valorManipulado = $this->bonusParametros->getValorBonus();
                \Log::info("Valor da bonus: R$ {$valorManipulado}");

                $saldoAnterior = 0;
                $saldo = 0;

                if ($ultimoMovimento) {
                    $saldoAnterior = $ultimoMovimento->saldo;
                    $saldo = $ultimoMovimento->saldo;
                }

                $saldo += $valorManipulado;

                $dadosMovimento = [
                    'valor_manipulado' => decimal($valorManipulado),
                    'saldo_anterior' => decimal($saldoAnterior),
                    'saldo' => decimal($saldo),
                    'pedido_id' => $this->bonusParametros->getPedidoOrigemBonus()->id,
                    'descricao' => $this->bonusParametros->getDescricaoMovimento(),
                    'responsavel_user_id' => $this->bonusParametros->getUsuarioResponsavel()->id,
                    'user_id' => $this->bonusParametros->getUsuario()->id,
                    'item_id' => $this->bonusParametros->getItemOrigemBonus()->id,
                    'titulo_id' => $this->bonusParametros->getTitulo()->id,
                    'operacao_id' => $this->bonusParametros->getOperacaoMovimento(),
                ];
                Movimentos::create($dadosMovimento);
                \Log::notice('Movimento inserido - bônus!', $dadosMovimento);
                $sucesso = true;
            } catch (ModelNotFoundException $e) {
            }
        }

        return $sucesso;
    }

    public function coletarTaxaEmpresa()
    {
        $sucesso = false;

        if ($this->bonusParametros->getValorTaxaEmpresa() > 0) {
            try {
                $ultimoMovimento = Movimentos::ultimoMovimentoUserId($this->bonusParametros->getUsuario()->id);

                if ($ultimoMovimento && decimal($ultimoMovimento->saldo) > $this->bonusParametros->getValorTaxaEmpresa()) {
                    $dadosMovimentoRoyalties = [
                        'valor_manipulado' => $this->bonusParametros->getValorTaxaEmpresa(),
                        'valor_excedente' => 0,
                        'saldo_anterior' => decimal($ultimoMovimento->saldo),
                        'saldo' => decimal($ultimoMovimento->saldo - $this->bonusParametros->getValorTaxaEmpresa()),
                        'descricao' => 'Coleta de Royalties',
                        'responsavel_user_id' => $this->bonusParametros->getUsuarioResponsavel()->id,
                        'user_id' => $this->bonusParametros->getUsuario()->id,
                        'operacao_id' => 31,
                        'pedido_id' => $this->bonusParametros->getPedidoOrigemBonus()->id,
                        'item_id' => $this->bonusParametros->getItemOrigemBonus()->id,
                        'titulo_id' => $this->bonusParametros->getTitulo()->id,
                    ];

                    Movimentos::create($dadosMovimentoRoyalties);
                    \Log::notice('Movimento inserido royalties!', $dadosMovimentoRoyalties);

                    $sucesso = true;
                }
            } catch (ModelNotFoundException $e) {
            }
        }

        return $sucesso;
    }
}
