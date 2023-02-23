<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendDocumentacaoAprovacaoEmail extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $user;
    protected $mensagem;
    protected $headerMsg;
    protected $subject;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $mensagem, $headerMensagem, $subject)
    {
        $this->user = $user;
        $this->mensagem = $mensagem;
        $this->headerMsg = $headerMensagem;
        $this->subject = $subject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $mailer->send('default.emails.confirmacoes.comprovante_pedido_user_verificado', [
            'nome_usuario' => $this->user->name,
            'headerMsg' => $this->headerMsg,
            'documento' => $this->mensagem,
        ], function ($message) {
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $message->to($this->user->email, $this->user->name)->subject($this->subject);
        });
    }
}
