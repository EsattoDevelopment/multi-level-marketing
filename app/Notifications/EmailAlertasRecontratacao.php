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

class EmailAlertasRecontratacao extends Notification implements ShouldQueue
{
    use Queueable;

    private $sistema;
    private $pedido;
    private $diasParaRecontratacao;
    private $valorParaRecontratacao;
    private $modoRecontratacaoAutomatica;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Pedidos $pedido, $diasParaRecontratacao, $modoRecontratacaoAutomatica)
    {
        $this->sistema = Sistema::findOrFail(1);
        $this->pedido = $pedido;
        $this->diasParaRecontratacao = $diasParaRecontratacao;

        $this->modoRecontratacaoAutomatica = $modoRecontratacaoAutomatica;

        if ($this->modoRecontratacaoAutomatica == config('constants.modo_recontratacao_automatica')['saldo_final_contrato']) {
            $pedidoItem = $this->pedido->itens->first();
            $this->valorParaRecontratacao = round($this->pedido->valor_total * $pedidoItem->total_meses_contrato * ($pedidoItem->potencial_mensal_teto / 100), 2);
            $this->valorParaRecontratacao += round($this->pedido->valor_total, 2);
        } else {
            $this->valorParaRecontratacao = $this->pedido->valor_total;
        }
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
            'diasParaRecontratacao' => $this->diasParaRecontratacao,
            'valorRecontratacao' => mascaraMoeda($this->sistema->moeda, $this->valorParaRecontratacao, 2, true),
            'modoRecontratacaoAutomatica' => $this->modoRecontratacaoAutomatica,
        ];

        return (new MailMessage)
            ->subject("Alerta de finalização de contrato - #{$dados->idContrato} - {$dados->nomeContrato} - ".env('COMPANY_NAME'))
            ->view('default.emails.confirmacoes.emailAlertasRecontratacao', [
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
