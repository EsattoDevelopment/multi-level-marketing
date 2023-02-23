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

class EmailTransferenciaValorMinimo extends Notification implements ShouldQueue
{
    use Queueable;

    private $pedido;
    private $percentual;
    private $valor;
    private $resgate;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Pedidos $pedidos, $dadosPagamento)
    {
        $this->pedido = $pedidos;
        $this->valor = $dadosPagamento->saldoRentabilizado;
        $this->percentual = $dadosPagamento->percentual;
        $this->resgate = $dadosPagamento->resgate;
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
            'valor' => $this->valor,
            'percentual' => $this->percentual,
            'resgate' => $this->resgate,
        ];

        return (new MailMessage)
            ->subject('Aviso de transferência automática de capitalização - '.env('COMPANY_NAME', 'Empresa'))
            ->view('default.emails.confirmacoes.emailTransferenciaValorMinimo', [
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
