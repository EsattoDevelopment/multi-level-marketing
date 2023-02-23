<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners\RodarSistema;

use Carbon\Carbon;
use App\Events\RodarSistema;
use Illuminate\Support\Facades\Log;

class VerificarMensalidades
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  RodarSistema $event
     *
     * @return void
     */
    public function handle(RodarSistema $event)
    {
        if ($event->sistema->sistema_saude) {
            Log::info("\n ============================ VERIFICAÇÃO DE MENSALIDADES ============================");

            $usuarios = $event
                ->getObjUsuario()
                ->whereIn('status', [1, 2])
                ->where('empresa_id', null)
                ->select([
                    'id',
                    'name',
                    'status',
                ])
                ->whereHas('contratos', function ($query) {
                    $query->whereIn('status', [2, 3]);
                })
                ->get();

            $now = Carbon::now();
            $diasMensalidadePendente = 7;

            foreach ($usuarios as $key => $usuario) {
                Log::info("==========>   Veficando Contrato de #{$usuario->id} - {$usuario->name}");

                //TODO carrega o contrato ativo
                $usuario->load([
                    'contratos' => function ($query) {
                        $query->whereIn('status', [2, 3])
                            ->select([
                                'id',
                                'dt_fim',
                                'user_id',
                                'status',
                                'aguarda_mensalidade',
                            ]);
                    },
                ]);

                $contrato = $usuario->getRelation('contratos')->first();

                Log::info("Contrato: #{$contrato->id}");

                //TODO carrega as mensalidades com status Aguardando e Proxima
                $contrato->load([
                    'mensalidades' => function ($query) {
                        $query->whereIn('status', [1, 2, 3])->select([

                            'id',
                            'user_id',
                            'contrato_id',
                            'status',
                            'dt_pagamento',
                            'proxima',
                        ]);
                    },
                ]);

                $mensalidades = $contrato->getRelation('mensalidades');

                //TODO verifica se mensalidade já foi paga
                if ($mensalidades->count() > 0) {
                    Log::info('Veficando mensalidades........');

                    //flag para verificar se há pendencias
                    $mensalidadesAtrasadas = false;

                    //Id da mensalidade a qual o contrato ira ficar dependente
                    $contratoIraAguardar = null;

                    foreach ($mensalidades as $mensalidade) {
                        $dataPagamento = new Carbon($mensalidade->getOriginal()['dt_pagamento']);

                        //TODO verifica quantos dias do pagamento se passaram
                        if ((int) $dataPagamento->diff($now)->format('%r%a') > $diasMensalidadePendente) {
                            $mensalidadesAtrasadas = true;

                            //status em atraso
                            $mensalidade->update(['status' => 3]);

                            if (! $mensalidade->proxima) {
                                $contratoIraAguardar = $mensalidade->id;
                            } else {
                                $contratoIraAguardar = $mensalidade->proxima;
                            }
                            Log::info("Mensalidade #{$mensalidade->id} em atraso, vencida em {$dataPagamento->format('d/m/Y')}!");
                        }
                    } //endforeach

                    //coloca mensalidade, contrato e usuario como atrasados/inadimplentes
                    if ($mensalidadesAtrasadas) {
                        //status inadimplente
                        $usuario->update(['status' => 2]);

                        //status em atraso
                        $contrato->update([
                            'status' => 3,
                            'aguarda_mensalidade' => $contratoIraAguardar,
                        ]);

                        Log::info('Contrato setado como atrasados/inadimplentes');
                    } else {

                        //se usuario setado como inadimplente, coloca ele como ativo
                        if ($usuario->status == 2) {
                            $usuario->update(['status' => 1]);
                            Log::info('Mensalidades em dia apartir agora');

                            $contrato->status = 2;
                            $contrato->save();
                        }

                        //dd($mensalidades->toArray());

                        //seta a mensalidade dependente
                        /*              $contrato->aguarda_mensalidade = $mensalidades->where('status', 2)->first()->id;
                                      $contrato->save();*/

                        Log::info('Não houve mudança de status');
                    }
                    Log::info("=======> Fim - #$usuario->id - $usuario->name \n");
                } else {
                    Log::info("Usuário #$usuario->id - $usuario->name, é empresa e ainda não foi gerado uma nova mensalidade");
                    Log::info("=======> Fim - #$usuario->id - $usuario->name \n");
                }//endif
            }
        }
    }
}
