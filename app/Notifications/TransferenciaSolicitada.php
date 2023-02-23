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

class TransferenciaSolicitada extends Notification implements ShouldQueue
{
    use Queueable;
    private $transferencia;
    private $ip;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($transferencia)
    {
        $this->transferencia = $transferencia;
        $this->ip = \Request::ip();
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
            ->subject('Solicitação de transferência')
            ->view('default.emails.transferencias.solicitacao', [
                'user' => $notifiable,
                'transferencia' => $this->transferencia,
                'ip' => $this->ip,
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
