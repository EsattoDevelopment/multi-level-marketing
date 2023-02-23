<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners;

use Log;
use App\Models\Sistema;
use App\Events\PedidoFoiPago;
use App\Services\EquiparacaoServices;

class Equiparacao
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
     * @return bool
     */
    public function handle(PedidoFoiPago $event)
    {
        Log::info("\n");
        Log::info('====================== Inicio pagamento equiparacao ============================');
        $sistema = (object) Sistema::findOrFail(1);
        if($sistema->pagar_bonus_equiparacao == 1) {
            $usuario = $event->getUsuario()->indicador;
            // começa a pagar a equiparação após o patrocinador
            // patrocinador não recebe equiparação pq já recebeu o bonus direto

            if ($usuario->id > 2 && in_array($event->getPedido()->tipo_pedido, [1, 2])) {
                $patrocinador = $usuario->indicador;

                $listaItens = $event->getItens();
                foreach ($listaItens as $itemLista) {
                    $item = $itemLista->item;
                    $valorTotal = $itemLista->quantidade * $item->bonus_equiparacao;

                    \Log::info('$sistema->bonus_equiparacao = ' . $item->bonus_equiparacao);
                    \Log::info('$itemLista->quantidade = ' . $itemLista->quantidade);
                    \Log::info('$valorTotal = $itemLista->quantidade * $item->bonus_equiparacao');
                    \Log::info('$valorTotal: $' . $valorTotal);

                    (new EquiparacaoServices($sistema))
                        ->pedido($event->getPedido())
                        ->usuario($patrocinador)
                        ->valor($valorTotal)
                        ->equiparar();

                    \Log::info("\n");
                }
            }
        }else{
            Log::info('Pagamento de bônus de equiparação desativado no sistema');
        }
        Log::info('====================== Fim pagamento equiparação ============================');

        return true;
    }
}
