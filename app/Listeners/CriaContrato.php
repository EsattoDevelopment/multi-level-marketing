<?php

namespace App\Listeners;

use Carbon\Carbon;
use App\Models\Contrato;
use App\Events\PedidoFoiPago;
use Illuminate\Support\Facades\Log;

class CriaContrato
{
    /**
     * Handle the event.
     *
     * @param  PedidoFoiPago $event
     * @return void
     */
    public function handle(PedidoFoiPago $event)
    {
        Log::info('Gerando contrato para os itens do pedido');
        $itens_pedido = $event->getItens();
        foreach ($itens_pedido as $item_pedido) {
            $item = $item_pedido->itens()->first();
            if ($item->qtd_parcelas > 0) {
                $dados_contrato = [
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
                Contrato::create($dados_contrato);
                Log::info("Contrato para o item # $item->id gerado com sucesso");
            } else {
                Log::info("Item # $item->id não precisa de contrato");
            }
        }
        return true;
    }
}
