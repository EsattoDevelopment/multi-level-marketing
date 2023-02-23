<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Jobs;

use App\Models\User;
use App\Models\Pedidos;
use App\Models\Sistema;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class SendPagamentoEmail.
 */
class SendPagamentoEmail extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var Pedido
     */
    protected $pedido;
    /**
     * @var User
     */
    protected $user;
    /**
     * @var string
     */
    protected $nome_usuario;

    /**
     * @var
     */
    protected $sistema;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Pedidos $pedido, User $user)
    {
        $this->sistema = Sistema::find(1);
        $this->pedido = $pedido;
        $this->user = $user;
        $this->nome_usuario = ucfirst(explode(' ', $user->name)[0]);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $mailer->send('default.emails.confirmacoes.comprovante_pedido_user', [
            'nome_usuario' => $this->nome_usuario,
            'pedido' => $this->pedido->id,
        ], function ($message) {
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $message->to($this->user->email, $this->user->name)->subject('Recebemos seu comprovante!');
        });

        if (! empty($this->sistema->emails_comprovante_pagamento)) {
            //envio um e-mail para o administrador para ele saber que fizeram uma transferencia bancaria
            $mailer->send('default.emails.confirmacoes.comprovante_pedido_user_adm', [
                'nome_usuario' => $this->nome_usuario,
                'pedido' => $this->pedido->id,
            ], function ($message) {
                $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                $message->to($this->sistema->emails_comprovante_pagamento, env('COMPANY_NAME'))
                    ->subject("Comprovante de pagamento - Pedido {$this->pedido->id} {$this->user->name}")
                    ->bcc('joseluiz@mastermundi.com.br');
            });
        }
    }
}
