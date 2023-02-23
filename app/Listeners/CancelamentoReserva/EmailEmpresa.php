<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners\CancelamentoReserva;

use Log;
use App\Events\CancelamentoReserva;
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
     * @param  CancelamentoReserva  $event
     * @return void
     */
    public function handle(CancelamentoReserva $event)
    {
        try {
            $reserva = $event->getReserva();

            $reserva->load('pacote', 'usuario', 'acomodacao');

            Mail::send('emails.cancelamento-reserva', [
                'reserva' => $reserva,
            ], function ($message) use ($reserva) {
                $message
                    ->to('reservas@galaxyclube.com.br', 'Galaxy Clube Reserva')
                    ->bcc('josejlpp@hotmail.com', 'Email de verificação')
                    ->subject('Solicitação de cancelamento Reserva | Galaxy Clube');
            });

            return true;
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }
}
