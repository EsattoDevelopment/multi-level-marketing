<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Notifications;

use App\Models\Pedidos;
use App\Models\Sistema;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailRecontratacao extends Notification implements ShouldQueue
{
    use Queueable;

    private $pedido;
    private $pedidoNovo;

    private $sistema;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Pedidos $pedido, Pedidos $pedidoNovo)
    {
        $this->pedido = $pedido;
        $this->pedidoNovo = $pedidoNovo;

        $this->sistema = Sistema::findOrFail(1);
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
            'idContratoNovo' => $this->pedidoNovo->id,
            'nomeContratoNovo' => $this->pedidoNovo->itens()->first()->name_item,
            'valorContratoNovo' => mascaraMoeda($this->sistema->moeda, $this->pedidoNovo->valor_total, 2, true),
            'email' => $notifiable->email,
        ];

        return (new MailMessage)
            ->subject("Aviso de novo contrato automático - #{$dados->idContrato} - {$dados->nomeContrato} - ".env('COMPANY_NAME'))
            ->view('default.emails.confirmacoes.emailRecontratacao', [
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
