<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Jobs;

use App\Models\Sistema;
use App\Models\DadosBancarios;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class SendDadosBancariosEmail.
 */
class SendDadosBancariosEmail extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var
     */
    protected $sistema;

    /**
     * @var DadosBancarios
     */
    protected $conta;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(DadosBancarios $conta)
    {
        $this->sistema = Sistema::find(1);
        $this->conta = $conta;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        if (! empty($this->sistema->emails_dados_bancarios)) {
            $mailer->send('default.emails.confirmacoes.dadosbancarios_adm', [
                'conta' => $this->conta,
            ], function ($message) {
                $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                $message->to($this->sistema->emails_dados_bancarios, env('COMPANY_NAME'))->subject("Novo comprovante bancário #{$this->conta->id}");
            });
        }

        //para usuário
        $name = explode(' ', $this->conta->usuario->name);
        array_map('ucfirst', $name);

        $mailer->send('default.emails.confirmacoes.dadosbancarios', [
            'conta' => $this->conta,
            'name' => $name,
        ], function ($message) use ($name) {
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $message->to($this->conta->usuario->email, $name[0])->subject('Comprovante recebido');
        });
    }
}
