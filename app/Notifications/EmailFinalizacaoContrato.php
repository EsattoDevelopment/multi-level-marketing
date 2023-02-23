<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Notifications;

use App\Models\Pedidos;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailFinalizacaoContrato extends Notification implements ShouldQueue
{
    use Queueable;

    private $pedido;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Pedidos $pedidos)
    {
        $this->pedido = $pedidos;
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
        $dados = (object) [
            'nomeUsuario' => ucfirst(explode(' ', $notifiable->name)[0]),
            'nomeUsuarioCompleto' => $notifiable->name,
            'idContrato' => $this->pedido->id,
            'nomeContrato' => $this->pedido->itens()->first()->name_item,
            'email' => $notifiable->email,
        ];

        return (new MailMessage)
            ->subject("Aviso de finalização de contrato - #{$dados->idContrato} - {$dados->nomeContrato} - ".env('COMPANY_NAME', 'Empresa'))
            ->view('default.emails.confirmacoes.emailFinalizacaoContrato', [
                'dados' => $dados,
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
