<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners;

use App\Events\BonusMensalidade;
use App\Services\PagamentoPontosBinarios;

class Pontuacao
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
     * @param  BonusMensalidade  $event
     * @return void
     */
    public function handle(BonusMensalidade $event)
    {
        $dados = [
            'valor' =>  $event->getMensalidade()->valor,
            'id' =>  $event->getMensalidade()->id,
            'documento' =>  $event->getMensalidade()->numero_documento,
            'descricao' =>  "{$event->getMensalidade()->parcela} do contrato {$event->getMensalidade()->contrato_id} de {$event->getUser()->name}",
            'user_name' =>  $event->getUser()->name,
        ];

        $pontos = new PagamentoPontosBinarios($event->getUser(), $dados);

        $pontos->pagar();

        return $pontos;
    }
}
