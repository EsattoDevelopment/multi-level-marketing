<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners;

use App\Events\BonusMensalidade;
use App\Models\PontosEquipeUnilevel;
use App\Services\PagarPontosServices;

class PagaPontosUnilevel
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
     * @param  PedidoFoiPago  $event
     * @return void
     */
    public function handle(BonusMensalidade $event)
    {
        \Log::info('Pagando Pontos de Equipe');

        $usuario = $event->getUser();
        $pontos = $event->getMensalidade()->valor_pago;

        $ultimosPontos = $usuario->pontosUnilevel->last();
        $model = new PontosEquipeUnilevel();

        (new PagarPontosServices())
                ->pontos($pontos)
                ->mensalidade($event->getMensalidade())
                ->ultimosPontos($ultimosPontos)
                ->model($model)
                ->usuario($usuario)
                ->operacao(18)
                ->pagar();

        return true;
    }
}
