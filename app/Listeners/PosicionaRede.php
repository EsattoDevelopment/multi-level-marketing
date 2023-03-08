<?php

namespace App\Listeners;

use Log;
use App\Models\RedeBinaria;
use App\Events\PedidoFoiPago;
use App\Services\PosicionaRedeBinaria;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PosicionaRede
{
    /**
     * Handle the event.
     *
     * @param  PedidoFoiPago  $event
     * @return void
     */
    public function handle(PedidoFoiPago $event)
    {
        if ($event->sistema->rede_binaria) {
            return true;
        }
        $usuario = $event->getUsuario();
        Log::info("Posicionando usuário # $usuario->id na rede");
        $rede_binaria_usuario = $event->getUsuario()->redeBinario;
        if ($rede_binaria_usuario instanceof RedeBinaria) {
            Log::warning("Usuário # $usuario->id já tem rede");
            return true;
        }
        Log::info("Usuário # $usuario->id ainda não tem rede");
        try {
            $pedido = $event->getPedido();
            $item = $event->getItens()->first()->itens()->first();
            if (!($item->tipo_pedido_id == 1 && in_array($pedido->status, [1, 2]))) {
                return true;
            }
            $associado = $event->getUsuario();
            if ($event->getPatrocinador()) {
                Log::error('Não tem patrocinador');
                return true;
            }
            Log::info("Iniciando posicionamento do usuário # $pedido->user_id");
            $lado = $associado->equipe_predefinida > 0
                ? $associado->equipe_predefinida
                : $event->getPatrocinador()->equipe_preferencial;
            $lado = $lado == 1
                ? 'esquerda'
                : 'direita';
            $rede_binaria_patrocinador = $event->getPatrocinador()->redeBinario;
            $rede_binaria = new PosicionaRedeBinaria($rede_binaria_patrocinador, $lado, $associado);
            $rede_binaria->posicionar();
            RedeBinaria::create(['user_id' => $associado->id]);
            Log::info("Criada a rede binária para o usuário $associado->id");
            Log::info('#################################');
            return true;
        } catch (ModelNotFoundException $e) {
            Log::error('Falhou posicionamento - Posiciona rede');
            return false;
        }
    }
}
