<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Jobs;

use App\Models\Pedidos;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class SendContratoEmail.
 */
class SendContratoEmail extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var string
     */
    protected $contrato;
    /**
     * @var Pedidos
     */
    protected $pedido;

    /**
     * Create a new job instance.
     *
     * @param string $contrato
     * @param Pedidos $pedido
     */
    public function __construct(string $contrato, Pedidos $pedido)
    {
        $this->contrato = $contrato;
        $this->pedido = $pedido;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $mailer->send('default.emails.confirmacoes.contrato', [
            'n_contrato' => $this->pedido->id,
            'nome_usuario' => $this->pedido->usuario->primeiro_nome,
            'item' => $this->pedido->itens->first()->name_item,
        ], function ($message) {
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $message->attach(storage_path('app/contratos/'.$this->contrato), [
                'as' => $this->contrato,
                'mime' => 'application/pdf',
            ]);
            $message->to($this->pedido->usuario->email, $this->pedido->usuario->name)->subject('Seu contrato nº '.$this->pedido->id.' '.$this->pedido->itens->first()->name_item);
        });
    }
}
