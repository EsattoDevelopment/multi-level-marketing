<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Jobs;

use App\Models\Sistema;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class SendCapitalizacaoEmail.
 */
class SendCapitalizacaoEmail extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var array
     */
    protected $data;
    protected $sistema;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->sistema = Sistema::findOrFail(1);
        $data['percentual'] = mascaraMoeda($this->sistema->moeda, $data['percentual'] * 100, 2);
        $data['nome_usuario'] = ucfirst(explode(' ', $data['nome_usuario'])[0]);

        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $mailer->send('default.emails.confirmacoes.capitalizacao', $this->data, function ($message) {
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $message->to($this->data['email_usuario'], $this->data['nome_usuario'])->subject('Parabéns! Você acaba de receber mais uma atualização da '.env('COMPANY_NAME', 'Empresa'));
        });
    }
}
