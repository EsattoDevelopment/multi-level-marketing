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
use Illuminate\Notifications\Messages\SlackMessage;

class LogSlack extends Notification implements ShouldQueue
{
    use Queueable;

    private $notificacao = [];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notificacao)
    {
        $this->notificacao['contexto'] = $notificacao['contexto'] ?? '';
        $this->notificacao['titulo'] = $notificacao['titulo'] ?? '';
        $this->notificacao['mensagem'] = $notificacao['mensagem'] ?? '';
        $this->notificacao['detalhes'] = $notificacao['detalhes'] ?? [];
        $this->notificacao['arquivo'] = $notificacao['arquivo'] ?? '';
        $this->notificacao['usuario'] = $notificacao['usuario'] ?? '';
        $this->notificacao['tipo'] = $notificacao['tipo'] ?? '';
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
        $this->validarUsuario($notifiable);
        if ($this->notificacao['tipo'] == 'sucesso') {
            return (new SlackMessage)
                ->success()
                ->content($this->notificacao['contexto'])
                ->attachment(function ($attachment) {
                    $attachment
                        ->title($this->notificacao['titulo'])
                        ->content($this->notificacao['mensagem'])
                        ->fields($this->notificacao['detalhes']);
                });
        } else {
            return (new SlackMessage)
                ->error()
                ->content($this->notificacao['contexto'])
                ->attachment(function ($attachment) {
                    $attachment
                        ->title($this->notificacao['titulo'])
                        ->content($this->notificacao['mensagem'])
                        ->fields($this->notificacao['detalhes']);
                });
        }
    }

    public function teste()
    {
        //Notification::send(User::findOrFail(2), new self());
        $msg = ['contexto' => 'Contexto', 'titulo' => 'Titulo', 'mensagem'=>'Mensagem', 'detalhes'=>['Propriedade 1'=>'Valor 1', 'Propriedade 2' => 'Valor 2']];
        \Illuminate\Support\Facades\Notification::send(User::findOrFail(2), new self($msg));
        //\Illuminate\Support\Facades\Notification::send(User::findOrFail(2), new self(''));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $this->validarUsuario($notifiable);

        return $this->notificacao;
    }

    /**
     * @param $notifiable
     */
    private function validarUsuario($notifiable)
    {
        if ($notifiable instanceof User) {
            $this->notificacao['usuario'] = "#{$notifiable->id} - {$notifiable->name}";
        } elseif ($this->notificacao['usuario'] && is_numeric($this->notificacao['usuario'])) {
            $usuario = User::find($this->notificacao['usuario']);
            $this->notificacao['usuario'] = "{$usuario->id} - {$usuario->name}";
        } else {
            $this->notificacao['usuario'] = 'Usuario nao identificado';
        }
    }
}
