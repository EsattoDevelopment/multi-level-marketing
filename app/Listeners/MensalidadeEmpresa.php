<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners;

use App\Services\BoletoService;
use App\Events\GerarMensalidadeEmpresa;

class MensalidadeEmpresa
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
     * @param  GerarMensalidadeEmpresa  $event
     * @return void
     */
    public function handle(GerarMensalidadeEmpresa $event)
    {
        foreach ($event->getEmpresas() as $empresa) {
            if ($empresa->funcionarios()->count() > 0) {
                $empresa->load(['contratos' =>function ($query) {
                    $query->where('status', 2)
                        ->select([
                            'id',
                            'user_id',
                            'pedido_id',
                            'item_id',
                            'status',
                            'aguarda_mensalidade',
                            'dt_parcela',
                        ])
                        ->with(['item' => function ($query) {
                            $query->select([
                                'id',
                                'qtd_parcelas',
                                'valor',
                                'vl_parcelas',
                            ]);
                        }]);
                }]);

                $serviceBoleto = new BoletoService($empresa->id);

                if (! $serviceBoleto->haveBanco()) {
                    flash()->error('Não há banco selecionado no sistema para gerar boletos, verifique as configurações de contas!');

                    return redirect()->back();
                }

                if ($empresa->getRelation('contratos')->first()) {
                    $serviceBoleto->carne($empresa->getRelation('contratos')->first());
                }
            }
        }
    }
}
