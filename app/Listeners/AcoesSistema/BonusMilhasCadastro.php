<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners\AcoesSistema;

use Log;
use Carbon\Carbon;
use App\Models\Milhas;
use App\Events\AcoesSistema;

class BonusMilhasCadastro
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
     * @param  AcoesSistema  $event
     * @return void
     */
    public function handle(AcoesSistema $event)
    {
        Log::info('Bonus milhas cadastro');

        $usuario = $event->getUsuario();

        //TODO pagamento das milhas
        $dadosMilhas = [
            'quantidade' => 2000,
            'descricao' => 'Bônus Milhas Cadastro',
            'user_id' => $usuario->id,
            'validade' => Carbon::now()->addDays(1250),
        ];

        Milhas::create($dadosMilhas);
        Log::info('Inserido milhas: ', $dadosMilhas);

        Log::info('Saiu bonus milhas cadastro');

        return true;
    }
}
