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
use App\Services\PagamentoPontosBinarios;

class PagaBinarios
{
    private $redeBinaria;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->redeBinaria = new RedeBinaria();
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
        Log::info('Entrou pagamento pontos');
        if ($event->sistema->rede_binaria) {
            try {
                if ($event->getUsuario()->id > 2) {
                    Log::info('Usuario: #'.$event->getUsuario()->id.' - '.$event->getUsuario()->name);

                    //instancia rede binária
                    $redeBinariaSuperior = $this->redeBinaria->redeSuperior($event->getUsuario()->id);

                    $lado = $event->getUsuario()->id == $redeBinariaSuperior->esquerda ? 1 : 2;

                    foreach ($event->getItens() as $itemPedido) {
                        (new PagamentoPontosBinarios)
                            ->item($itemPedido)
                            ->user($redeBinariaSuperior->usuario)
                            ->operacao(18)
                            ->lado($lado)
                            ->pagar();
                    }
                }
                Log::info('terminou pagamento pontos');

                return true;
            } catch (ModelNotFoundException $e) {
                Log::info('Falhou posicionamento - Paga pontos');

                return false;
            }
        }

        return true;
    }
}
