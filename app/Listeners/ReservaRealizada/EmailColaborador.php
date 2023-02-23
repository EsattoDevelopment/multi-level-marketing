<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners\ReservaRealizada;

use Log;
use App\Events\ReservaRealizada;
use Illuminate\Support\Facades\Mail;

class EmailColaborador
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
     * @param  ReservaRealizada  $event
     * @return void
     */
    public function handle(ReservaRealizada $event)
    {
        try {
            $reserva = $event->getReserva();

            Mail::send('emails.reserva', [
                'reserva' => $reserva,
            ], function ($message) use ($reserva) {
                $message
                    ->to($reserva->getRelation('usuario')->email, $reserva->getRelation('usuario')->name)
                    ->bcc('naoresponda@galaxyclube.com.br', 'Email de verificação')
                    ->replyTo('reservas@galaxyclube.com.br', 'Galaxy Clube - Reservas')
                    ->subject('Reserva Galaxy Clube');
            });

            return true;
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }
}
