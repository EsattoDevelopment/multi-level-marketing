<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TransferenciaSolicitadaAdmin extends Notification implements ShouldQueue
{
    use Queueable;
    private $transferencia;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($transferencia)
    {
        $this->transferencia = $transferencia;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Aviso - Solicitação de transferência #{$this->transferencia->id}")
            ->view('default.emails.transferencias.solicitacao-admin', [
                'user' => $this->transferencia->usuario,
                'transferencia' => $this->transferencia,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
