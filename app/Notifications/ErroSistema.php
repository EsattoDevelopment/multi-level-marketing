<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Notifications;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class ErroSistema extends Notification implements ShouldQueue
{
    use Queueable;
    private $exception;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($e)
    {
        $exception = [
            'mensagem' => $e->getMessage(),
            'arquivo' => $e->getFile(),
            'linha' => $e->getLine(),
            'status' => $e->getCode(),
            'text' => $e->getTraceAsString(),
            'url' => \Request::url() ?? '',
            'user' => Auth::user() ? '#'.Auth::user()->id.' - '.Auth::user()->name : null,
        ];

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
        return ['slack', 'database'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->error()
            ->content($this->exception['mensagem'])
            ->attachment(function ($attachment) {
                $attachment
                    ->title('ErrrrroouuuuuUU', ':exclamações:')
                    ->content("\n Arquivo: {$this->exception['arquivo']} \n\n {$this->exception['text']}")
                    ->fields([
                        'Linha' => $this->exception['linha'],
                        'Status' => $this->exception['status'],
                        'Data' => Carbon::now()->format('d/m/Y H:i:s'),
                        'Status Code' => $this->exception['status_code'] ?? '',
                        'Usuario' => $this->exception['user'] ?? 'Usuario nao identificado',
                        'url' => $this->exception['url'] ?? 'Url nao identificada',
                    ]);
            });
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', 'https://laravel.com')
                    ->line('Thank you for using our application!');
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
            'error' => $this->exception['text'],
            'mensagem' => $this->exception['mensagem'],
            'arquivo' => $this->exception['arquivo'],
            'url' => $this->exception['url'] ?? 'Url nao identificada',
            'usuario' => $notifiable instanceof User ? "#{$notifiable->id} - {$notifiable->name}" : 'Usuario nao identificado',
        ];
    }
}
