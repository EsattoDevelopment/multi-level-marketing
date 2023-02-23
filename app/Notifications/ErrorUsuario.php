<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ErrorUsuario extends Notification implements ShouldQueue
{
    use Queueable;
    private $exception;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($exception)
    {
        $this->exception = $exception;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray(User $notifiable)
    {
        return [
            'error' => $this->exception['text'],
            'mensagem' => $this->exception['mensagem'],
            'arquivo' => $this->exception['arquivo'],
            'url' => $this->exception['url'] ?? '',
            'usuario' => "#{$notifiable->id} - {$notifiable->name}",
        ];
    }
}
