<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners;

use Log;
use App\Models\RedeBinaria;
use App\Events\PedidoFoiPago;
use App\Services\PosicionaRedeBinaria;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PosicionaRede
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
    public function handle(PedidoFoiPago $event)
    {
        if ($event->sistema->rede_binaria) {
            Log::info('Entrou posiciona rede user #'.$event->getUsuario()->id);
            $redeUsuario = $event->getUsuario()->redeBinario;

            if (! $redeUsuario instanceof RedeBinaria) {
                Log::info('Não tem rede');
                try {

                    //instancia dados necessários
                    $pedido = $event->getPedido();
                    $item = $event->getItens()->first()->itens()->first();

                    if (in_array($pedido->status, [1, 2]) && $item->tipo_pedido_id == 1) {
                        $associado = $event->getUsuario();

                        if ($event->getPatrocinador()) {
                            //instancia rede indicador
                            $redePatrocinador = $event->getPatrocinador()->redeBinario;

                            Log::info('Iniciou posicionamento do ID: '.$pedido->user_id);

                            //verifica de que lado vai ser posicionado
                            if ($associado->equipe_predefinida > 0) {
                                $lado = $associado->equipe_predefinida;
                            } else {
                                $lado = $event->getPatrocinador()->equipe_preferencial;
                            }

                            $lado = $lado == 1 ? 'esquerda' : 'direita';

                            $redeBinaria = new PosicionaRedeBinaria($redePatrocinador, $lado, $associado);

                            $redeBinaria->posicionar();

                            RedeBinaria::create([
                                'user_id' => $associado->id,
                            ]);

                            Log::info("Criado rede binária para ID {$associado->id}");
                            Log::info('#################################');
                        } else {
                            Log::error('Não tem patrocinador!');
                        }
                    }

                    return true;
                } catch (ModelNotFoundException $e) {
                    Log::error('Falhou posicionamento - Posiciona rede');

                    return false;
                }
            } else {
                Log::warning('Já tem rede #'.$event->getUsuario()->id.' - '.$event->getUsuario()->name);
            }

            return true;
        }

        return true;
    }
}
