<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners;

use Log;
use Carbon\Carbon;
use App\Models\Milhas;
use App\Models\RedeBinaria;
use App\Events\PedidoFoiPago;
use Illuminate\Support\Facades\DB;

class PagaMilhas
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
     * @param PedidoFoiPago $event
     * @return void
     */
    public function handle(PedidoFoiPago $event)
    {
        if ($event->sistema->sistema_viagem) {
            Log::info('Entrou pagamento milhas');
            try {
                //TODO instancia dados necessários
                $pedido = $event->getPedido();
                $listaItens = $event->getItens();
                $userIndicado = $event->getUsuario();

                if ($userIndicado->id > 2) {

                    //TODO instancia indicador
                    $userIndicador = $userIndicado->indicador()->first();

                    //TODO instancia titulo
                    $tituloIndicador = $userIndicador->titulo()->first();

                    if ($userIndicador && $tituloIndicador->recebe_pontuacao) {
                        foreach ($listaItens as $itemLista) {
                            $item = $itemLista->itens()->first();

                            if ($item->bonus_milhas_indicador > 0) {

                                //TODO pagamento das milhas
                                $dadosMilhas = [
                                    'quantidade' => $item->bonus_milhas_indicador,
                                    'descricao' => 'Milhas de pedido',
                                    'user_id' => $userIndicador->id,
                                    'validade' => Carbon::now()->addDays($item->validade_milhas),
                                    'pedido_id' => $pedido->id,
                                ];

                                Milhas::create($dadosMilhas);
                                Log::info('Inserido milhas: ', $dadosMilhas);
                            } else {
                                Log::info('Milhas igual a 0');
                            }
                        }

                        //DB::commit();
                    } else {
                        Log::info('Titulo não pontua ou sem indicador');
                    }
                }

                //TODO paga bonus ao comprador
                foreach ($listaItens as $itemLista) {
                    $item = $itemLista->itens()->first();

                    //TODO pagamento das milhas
                    $dadosMilhas = [
                        'quantidade' => $item->milhas,
                        'descricao' => 'Milhas de pedido',
                        'user_id' => $userIndicado->id,
                        'validade' => Carbon::now()->addDays($item->validade_milhas),
                        'pedido_id' => $pedido->id,
                    ];

                    Milhas::create($dadosMilhas);
                    Log::info('Inserido milhas: ', $dadosMilhas);
                }

                //TODO paga milhas binarias
                foreach ($listaItens as $itemLista) {
                    Log::info('entrou pagamento milhas binarias');
                    $item = $itemLista->itens()->first();

                    //TODO instancia upline
                    $rede = RedeBinaria::whereEsquerda($userIndicado->id)->orWhere('direita', $userIndicado->id)->first();

                    if ($item->milhas_binaria > 0) {
                        $rede->pontuaRedeMilhas($item, $pedido->id, $item->milhas_binaria_max_altura);
                    }
                }

                Log::info('terminou pagamento milhas');

                return true;
            } catch (ModelNotFoundException $e) {
                Log::info('Falhou pagamento de milhas');

                return false;
            }

            return true;
        }

        return true;
    }
}
