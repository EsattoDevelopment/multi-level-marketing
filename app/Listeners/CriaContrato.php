<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners;

use Carbon\Carbon;
use App\Models\Contrato;
use App\Events\PedidoFoiPago;
use Illuminate\Support\Facades\Log;

class CriaContrato
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
     * @param  PedidoFoiPago $event
     *
     * @return void
     */
    public function handle(PedidoFoiPago $event)
    {
        Log::info('Entrou Criar contrato');

        $itensPedido = $event->getItens();

        foreach ($itensPedido as $itemPedido) {
            $item = $itemPedido->itens()->first();

            if ($item->qtd_parcelas > 0) {
                $dadosContrato = [
                            'dt_inicio'        => Carbon::now(),
                            'dt_parcela'       => Carbon::now()->addMonth(),
                            'dt_fim'           => Carbon::now()->addDays($item->temp_contrato + 30),
                            'item_id'          => $item->id,
                            'user_id'          => $event->getUsuario()->id,
                            'status'           => 1,
                            'pedido_id'        => $event->getPedido()->id,
                            'qtd_mensalidades' => $item->qtd_parcelas,
                            'vl_mensalidades'  => $item->vl_parcelas,
                            'temp_contrato'    => $item->temp_contrato,
                        ];

                Contrato::create($dadosContrato);

                Log::info('Contrato gerado com sucesso!');
            } else {
                Log::info('Item não precisa de contrato!');
            }
        }

        return true;
    }
}
