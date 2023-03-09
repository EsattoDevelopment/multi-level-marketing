<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FinalizaContratoAutomaticoService
{
    private $dataProcessamento;

    public function __construct(Carbon $dataProcessamento)
    {
        $this->dataProcessamento = $dataProcessamento;
    }

    public function processar(): bool
    {
        $sucesso = false;

        Log::info('##############################################################################');
        Log::info('#### FinalizaContratoAutomaticoService - Inicio                            ###');
        Log::info('##############################################################################');
        Log::info('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>');

        $objetos = DB::table('pedidos as p')
                        ->join('itens_pedido as ip', 'ip.pedido_id', '=', 'p.id')
                        ->join('dados_pagamento as dp', 'dp.pedido_id', '=', 'p.id')
                        ->whereRaw("p.status in (2, 7) and ip.finaliza_contrato_automatico = 1 and ip.total_dias_contrato > 0 and TIMESTAMPDIFF(DAY,cast(dp.data_pagamento_efetivo as date), '{$this->dataProcessamento->format('Y-m-d')}') > ip.total_dias_contrato")
                        ->select(['p.id as pedido_id'])->get();

        Log::info('------------------------------------------------------------------------------');
        Log::info('$objetos: ', $objetos);
        Log::info('Total de pedidos: '.count($objetos));
        Log::info('------------------------------------------------------------------------------');

        DB::beginTransaction();
        try {
            $contadorContratosFinalizados = 0;
            $contadorContratosFinalizadosSucesso = 0;

            foreach ($objetos as $objeto) {
                $contadorContratosFinalizados++;

                Log::info('==============================================================================');
                Log::info('pedido_id: '.$objeto->pedido_id);

                $finalizaContratoService = new FinalizaContratoService();
                if (
                    $finalizaContratoService
                        ->pedidoId($objeto->pedido_id)
                        ->finalizarContrato()
                   ) {
                    $contadorContratosFinalizadosSucesso++;
                }

                Log::info('==============================================================================');
            }

            DB::commit();
            $sucesso = true;

            Log::info('******************************************************************************');
            Log::info("Total de contratos Finalizados: {$contadorContratosFinalizados}");
            Log::info("                       Sucesso: {$contadorContratosFinalizadosSucesso}");
            Log::info('                        Falhas: '.($contadorContratosFinalizados - $contadorContratosFinalizadosSucesso));
            Log::info('******************************************************************************');
        } catch (ModelNotFoundException $e) {
            Log::error('erro ao processar FinalizaContratoAutomaticoService'.$e);
            DB::rollback();
        }

        Log::info('<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
        Log::info('##############################################################################');
        Log::info('#### FinalizaContratoAutomaticoService - Fim                               ###');
        Log::info('##############################################################################');

        return $sucesso;
    }
}
