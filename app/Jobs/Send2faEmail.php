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

class Send2faEmail extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @user User $user
     */
    protected $user;
    protected $ip;

    /**
     * @var string
     */
    protected $nome_usuario;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $ip)
    {
        $this->user = $user;
        $this->ip = $ip;
        $this->nome_usuario = ucfirst(explode(' ', $user->name)[0]);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $mailer->send('default.emails.confirmacoes.2fa', [
          'nome_usuario' => $this->nome_usuario,
          'email_usuario' => $this->user->email,
          'ip' => $this->ip,
        ], function ($message) {
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $message->to($this->user->email, $this->user->name)->subject('Confirmação de login');
        });
    }
}
