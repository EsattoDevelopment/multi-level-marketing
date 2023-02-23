<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Listeners\Contrato;

use App\Saude\Events\CancelamentoContrato;
use App\Services\CancelarMensalidadesService;

class CancelarMensalidade
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
     * @param  CancelamentoContrato  $event
     * @return void
     */
    public function handle(CancelamentoContrato $event)
    {
        $mensalidades = $event->getContato()->mensalidades()->whereNotIn('status', [4, 5])->get();

        $mensalidadeService = new CancelarMensalidadesService($mensalidades);

        $mensalidadeService->cancelar();

        $user = $event->getContato()->usuario;
        $user->update(['status' => '4']);

        \Log::info("Status do usuário mudado para 'Contrato cancelado' \n");
    }
}
