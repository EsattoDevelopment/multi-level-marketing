<?php

namespace App\Listeners;

use Log;
use App\Events\PedidoFoiPago;
use App\Models\UpgradeTitulo;

class SobeTituloItem
{
    /**
     * Handle the event.
     *
     * @param  PedidoFoiPago $event
     * @return void
     */
    public function handle(PedidoFoiPago $event)
    {
        $usuario = $event->getUsuario();
        $titulo_usuario = $usuario->titulo;
        $titulo_superior = $titulo_usuario->tituloSuperior;
        Log::info('Avançando título pelo item');
        if (!$titulo_superior) {
            Log::info("Não há titulo superior ao atual # $titulo_usuario->id para o usuário # $usuario->id");
            return true;
        }
        $itens_pedido = $event->getItens();
        foreach ($itens_pedido as $item_pedido) {
            if (!$item_pedido->item->avanca_titulo) {
                Log::info("Item {$item_pedido->item->id} não avança titulo ou titulo para avançar igual ingressante");
                continue;
            }
            $titulo_item = $item_pedido->item->titulo;
            if (!$titulo_item->maiorQue($titulo_usuario)) {
                Log::info("Titulo # $titulo_item->id do item # {$item_pedido->item->id} é inferior ou igual ao título atual # $titulo_usuario->id");
                continue;
            }
            UpgradeTitulo::create(['user_id' =>$usuario->id, 'titulo_id' => $titulo_item->id]);
            $usuario->titulo_id = $titulo_item->id;
            $usuario->save();
            Log::info("Usuario # $usuario->id - $usuario->name, subiu do título # $titulo_usuario->id - $titulo_usuario->name para o titulo: # $titulo_usuario->id - $titulo_item->name");
        }
        Log::info('Avanço do título pelo item concluído');
        return true;
    }
}
