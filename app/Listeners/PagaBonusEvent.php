<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners;

use App\Models\User;
use App\Models\Sistema;
use App\Events\PedidoFoiPago;
use Illuminate\Support\Facades\Auth;
use App\Services\PagaBonusSetupService;

class PagaBonusEvent
{
    private $sistema;

    public function __construct()
    {
        $this->sistema = Sistema::findOrFail(1);
    }

    public function handle(PedidoFoiPago $event)
    {
        $sucesso = true;

        try {
            $associado = $event->getUsuario();

            if ($associado->id > 2 && in_array($event->getPedido()->tipo_pedido, [1, 2, 3])) {
                if ($event->getPedido()->item()->pagar_bonus) {
                    $usuarioNivel1 = $associado->indicador;
                    \Log::warning("Valor pedido: R$ {$event->getPedido()->valor_total}");

                    if ($usuarioNivel1) {
                        $pagaBonusSetup = (new PagaBonusSetupService())
                            ->pedido($event->getPedido())
                            ->usuarioPedido($associado)
                            ->usuarioNivel1($usuarioNivel1)
                            ->usuarioResponsavel(Auth::user() ?? User::find(1))
                            ->nivelMaximoPagamentoBonus($this->sistema->profundidade_pagamento_matriz)
                            ->taxaPercentualEmpresa($this->sistema->royalties_porcentagem)
                            ->valorMinimoBonusCobrancaTaxa($this->sistema->royalties_valor_minimo_bonus)
                            ->processar();
                    }
                }
            }
        } catch (ModelNotFoundException $e) {
            $sucesso = false;
        }

        return $sucesso;
    }
}
