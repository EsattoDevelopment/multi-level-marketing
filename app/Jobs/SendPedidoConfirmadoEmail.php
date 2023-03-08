<?php

namespace App\Jobs;

use App\Models\Pedidos;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPedidoConfirmadoEmail extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /** @var Pedidos */
    protected $pedido;

    /** @var string */
    protected $nome_usuario;

    public function __construct(Pedidos $pedido)
    {
        $this->pedido = $pedido;
        $primeiro_nome = explode(' ', $this->pedido->user->name)[0];
        $this->nome_usuario = ucfirst($primeiro_nome);
    }

    /**
     * Execute the job.
     */
    public function handle(Mailer $mailer): void
    {
        $mailer->send('default.emails.confirmacoes.pedido_confirmado', [
            'pedido' => $this->pedido->id,
            'nome_usuario' => $this->nome_usuario,
        ], function ($message) {
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $message->to($this->pedido->user->email, $this->pedido->user->name)->subject('Recebemos seu depósito!');
        });
    }
}
