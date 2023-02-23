<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

use App\Models\Pedidos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmailTransferenciaValorMinimo;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ResgateMinimoContratoService
{
    public function __construct()
    {
    }

    public function processar()
    {
        $sucesso = false;

        Log::info('##############################################################################');
        Log::info('#### ResgateMinimoContratoService - Inicio                                 ###');
        Log::info('##############################################################################');
        Log::info('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>');

        $objetos = DB::table('pedidos as p')
            ->join('pedidos_movimentos as pm', 'pm.pedido_id', '=', 'p.id')
            ->join('itens_pedido as ip', 'ip.pedido_id', '=', 'p.id')
            ->whereRaw('p.status in (2, 7) and ip.resgate_minimo_automatico = 1 and pm.id = (select max(id) from pedidos_movimentos t where t.pedido_id = p.id) and pm.saldo > 0 and pm.saldo >= (p.valor_total * (ip.resgate_minimo / 100))')
            ->select(['p.id as pedido_id', 'pm.saldo as saldoRentabilizado', DB::raw('(pm.saldo * 100) / p.valor_total as percentual'), 'ip.resgate_minimo as resgate'])->get();

        Log::info('------------------------------------------------------------------------------');
        Log::info('$objetos: ', $objetos);
        Log::info('Total de pedidos: '.count($objetos));
        Log::info('------------------------------------------------------------------------------');

        DB::beginTransaction();
        try {
            $contadorTransferencias = 0;
            $contadorTransferenciasSucesso = 0;

            foreach ($objetos as $objeto) {
                Log::info('==============================================================================');
                Log::info('pedido_id: '.$objeto->pedido_id);
                Log::info('saldoRentabilizado: '.$objeto->saldoRentabilizado);

                $contadorTransferencias++;
                $transferenciaRentabilidadeCarteiraService = new TransferenciaRentabilidadeCarteiraService();

                if (
                    $transferenciaRentabilidadeCarteiraService
                        ->pedidoId($objeto->pedido_id)
                        ->valorManipulado($objeto->saldoRentabilizado)
                        ->descricaoPedidoMovimento('Transferência automática do capital corrigido para sua Carteira')
                        ->descicaoMovimento("Transferência automática do capital corrigido para sua Carteira, contrato Nº #{$objeto->pedido_id}")
                        ->transferirParaContaDigital()
                    ) {
                    $contadorTransferenciasSucesso++;
                    $pedido = Pedidos::find($objeto->pedido_id);
                    Notification::send($pedido->user, new EmailTransferenciaValorMinimo($pedido, $objeto));
                }

                Log::info('==============================================================================');
            }

            DB::commit();
            $sucesso = true;

            Log::info('******************************************************************************');
            Log::info("Total transferencias: {$contadorTransferencias}");
            Log::info("             Sucesso: {$contadorTransferenciasSucesso}");
            Log::info('              Falhas: '.($contadorTransferencias - $contadorTransferenciasSucesso));
            Log::info('******************************************************************************');
        } catch (ModelNotFoundException $e) {
            Log::error('erro ao processar ResgateMinimoContratoService'.$e);
            DB::rollback();
        }
        Log::info('<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
        Log::info('##############################################################################');
        Log::info('#### ResgateMinimoContratoService - Fim                                    ###');
        Log::info('##############################################################################');

        return $sucesso;
    }
}
