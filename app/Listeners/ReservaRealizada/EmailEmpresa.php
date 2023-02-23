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

class EmailEmpresa
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

            Mail::send('emails.reserva-interna', [
                'reserva' => $reserva,
            ], function ($message) use ($reserva) {
                $message
                    ->to('reservas@galaxyclube.com.br', 'Galaxy Clube Reserva')
                    ->bcc('josejlpp@hotmail.com', 'Email de verificação')
                    ->subject('Reserva Galaxy Clube');
            });

            return true;
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }
}
