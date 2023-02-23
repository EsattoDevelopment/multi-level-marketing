<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Jobs;

use App\Models\User;
use App\Models\Sistema;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class SendDocumentacaoEmail.
 */
class SendDocumentacaoEmail extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var User
     */
    protected $user;
    /**
     * @var
     */
    protected $sistema;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(user $user)
    {
        $this->sistema = Sistema::find(1);
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        if (! empty($this->sistema->emails_documentacao)) {
            $mailer->send([], [], function ($message) {
                $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                $message->to($this->sistema->emails_documentacao, 'Cadastro '.env('COMPANY_NAME', 'empresa'))->subject('Nova documentação');
                $message->setBody("O usuário {$this->user->name} acabou de solicitar a verificação da documentação. Acesse o sistema para aprovar.", 'text/html');
                $message->attach(storage_path("app/documentos/{$this->user->image_cpf}"));
            });
        }
    }
}
