<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners;

use Log;
use App\Models\Titulos;
use App\Events\PedidoFoiPago;
use App\Models\UpgradeTitulo;

class SobeTituloItem
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
        Log::info('Avança titulo pelo item');
        $usuario = $event->getUsuario();
        $tituloUsuario = $usuario->titulo;
        $tituloSuperior = $tituloUsuario->tituloSuperior;

        if ($tituloSuperior) {
            $objTitulo = new Titulos();

            $itensPedido = $event->getItens();

            foreach ($itensPedido as $itemPedido) {
                $item = $itemPedido->itens;

                if ($item->avanca_titulo) {
                    $titulo = $item->titulo;

                    $subirPara = $objTitulo->tituloMaiorQueDoUsuario($titulo, $tituloUsuario);

                    if ($subirPara) {
                        UpgradeTitulo::create(['user_id' =>$usuario->id, 'titulo_id' =>$titulo->id]);
                        $usuario->titulo_id = $titulo->id;
                        $usuario->save();
                        Log::info('Usuario '.$usuario->name.' - '.$usuario->id.', subiu para titulo:'.$titulo->name);
                    } else {
                        Log::info('Titulo inferior ou igual');
                    }
                } else {
                    Log::info('Não avança titulo ou titulo para avançar igual ingressante');
                }
            }
        } else {
            Log::info('Não há titulo superior ao atual');
        }
        Log::info('sai - Avança titulo pelo item');

        return true;
    }
}
