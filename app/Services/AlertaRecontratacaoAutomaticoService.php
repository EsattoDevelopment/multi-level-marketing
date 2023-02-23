<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

use Carbon\Carbon;
use App\Models\Pedidos;
use App\Models\Sistema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmailAlertasRecontratacao;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AlertaRecontratacaoAutomaticoService
{
    private $dataProcessamento;
    private $sistema;

    public function __construct(Carbon $dataProcessamento)
    {
        $this->dataProcessamento = $dataProcessamento;
        $this->sistema = Sistema::findOrFail(1);
    }

    public function processar()
    {
        $sucesso = false;

        Log::info('##############################################################################');
        Log::info('#### AlertaRecontratacaoAutomaticoService - Inicio                         ###');
        Log::info('##############################################################################');
        Log::info('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>');

        if (preg_match('/^[ ]*[0-9]+[ ]*$|^[ ]*[0-9]+[ ]*,(?:[ ]*[0-9]+[ ]*,)*?[ ]*[0-9]+$/', $this->sistema->alertas_recontratacao_range_dias)) { //Valida o valor definido no campo Ex1: 1  Ex2: 5, 3, 1
            $objetos = DB::table('pedidos as p')
                ->join('itens_pedido as ip', 'ip.pedido_id', '=', 'p.id')
                ->join('dados_pagamento as dp', 'dp.pedido_id', '=', 'p.id')
                ->whereRaw("p.status in (2, 7) and ip.finaliza_contrato_automatico = 1 and ip.total_dias_contrato > 0 and ip.modo_recontratacao_automatica > 0 and ip.total_dias_contrato - TIMESTAMPDIFF(DAY,cast(dp.data_pagamento_efetivo as date), '{$this->dataProcessamento->format('Y-m-d')}') in ({$this->sistema->alertas_recontratacao_range_dias})")
                ->select(['p.id as pedido_id', DB::Raw("ip.total_dias_contrato - TIMESTAMPDIFF(DAY,cast(dp.data_pagamento_efetivo as date), '{$this->dataProcessamento->format('Y-m-d')}') as diasParaRecontratacao"), 'ip.modo_recontratacao_automatica as modoRecontratacaoAutomatica'])->get();

            Log::info('------------------------------------------------------------------------------');
            Log::info('$objetos: ', $objetos);
            Log::info('Total de pedidos: '.count($objetos));
            Log::info('------------------------------------------------------------------------------');

            $contadorTotalEmailsEnviados = 0;
            $contadorTotalEmailsEnviadosSucesso = 0;

            try {
                foreach ($objetos as $objeto) {
                    $contadorTotalEmailsEnviados++;
                    try {
                        $pedido = Pedidos::find($objeto->pedido_id);
                        $usuario = $pedido->user;

                        if ($objeto->diasParaRecontratacao > 0) {
                            Notification::send($usuario, new EmailAlertasRecontratacao($pedido, $objeto->diasParaRecontratacao, $objeto->modoRecontratacaoAutomatica));
                            $contadorTotalEmailsEnviadosSucesso++;
                        }
                    } catch (ModelNotFoundException $e) {
                        Log::info('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>');
                        Log::error('erro ao processar criar job'.$e);
                        Log::info('<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
                    }
                }

                $sucesso = true;
                Log::info("Total de e-mails enviados: {$contadorTotalEmailsEnviados}");
                Log::info("                       Sucesso: {$contadorTotalEmailsEnviadosSucesso}");
                Log::info('                        Falhas: '.($contadorTotalEmailsEnviados - $contadorTotalEmailsEnviadosSucesso));
            } catch (ModelNotFoundException $e) {
                Log::info('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>');
                Log::error('erro ao processar AlertaRecontratacaoAutomaticoService'.$e);
                Log::info('<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
            }
        }

        Log::info('<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
        Log::info('##############################################################################');
        Log::info('#### AlertaRecontratacaoAutomaticoService - Fim                            ###');
        Log::info('##############################################################################');

        return $sucesso;
    }
}
