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

class VerificarContratosFinalizado
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
     * @param  RodarSistema  $event
     * @return void
     */
    public function handle(RodarSistema $event)
    {
        if ($event->sistema->sistema_saude) {
            Log::info("\n ============================ VERIFICAÇÃO DE CONTRATOS EM FINALIZAÇÃO ============================");

            $usuarios = $event
                ->getObjUsuario()
                ->with([
                    'contratos' => function ($query) {
                        $query->where('status', 5)
                            ->select([
                                'id',
                                'dt_fim',
                                'user_id',
                                'status',
                            ]);
                    },
                ])
                ->where('empresa_id', null)
                ->select([
                    'id',
                    'name',
                    'status',
                ])
                ->whereHas('contratos', function ($query) {
                    $query->where('status', 5);
                })
                ->get();

            $now = Carbon::now();
            $diasAposFinalContrato = 30;

            foreach ($usuarios as $key => $usuario) {
                $contrato = $usuario->getRelation('contratos')->first();

                $dataFinal = new Carbon($contrato->getOriginal()['dt_fim']);

                if ($dataFinal->diff($now)->days > $diasAposFinalContrato) {
                    $usuario->update(['status' => 3]);
                    $contrato->update(['status' => 6]);
                    Log::info("Usuário #$usuario->id - $usuario->name, esta com o contrato finalizado");
                }
            }
        }
    }
}
