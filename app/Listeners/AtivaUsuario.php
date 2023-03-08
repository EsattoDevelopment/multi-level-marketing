<?php

namespace App\Listeners;

use Log;
use App\Events\PedidoFoiPago;

class AtivaUsuario
{
    /**
     * Handle the event.
     *
     * @param  PedidoFoiPago  $event
     * @return void
     */
    public function handle(PedidoFoiPago $event)
    {
        $usuario = $event->getUsuario();
        Log::info("Ativando usuario # $usuario->id, status atual $usuario->status");
        if (in_array((integer) $usuario->status, [0, 3, 4, 5])) {
            $usuario->status = 1;
            $usuario->save();
            Log::info("Usuário # $usuario->id ativo, novo status: $usuario->status");
        } else {
            Log::info("Usuário # $usuario->id já estava ativado");
        }
        return true;
    }
}
