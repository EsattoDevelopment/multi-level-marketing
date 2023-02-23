<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Jobs;

use App\Models\Pedidos;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Knp\Snappy\Exception\FileAlreadyExistsException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class GerarContrato.
 */
class GerarContrato extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var Pedidos
     */
    protected $pedido;
    /**
     * @var bool
     */
    protected $sendMail;

    /**
     * Create a new job instance.
     *
     * @param Pedidos $pedido
     * @param bool $sendMail
     */
    public function __construct(Pedidos $pedido, bool $sendMail = false)
    {
        $this->sendMail = $sendMail;
        $this->pedido = $pedido;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            if (in_array($this->pedido->tipo_pedido, [3, 4])) {
                throw new ModelNotFoundException;
            }

            $user = $this->pedido->usuario;
            $responsavel = false;

            if ($user->idade < 18) {
                $responsavel = $user->responsavel->where('status', 1)->first();

                if (! $responsavel) {
                    $responsavel = $user->responsavel->where('status', 0)->first();
                }
            }

            $endereco = collect($user->endereco)->map(function ($a) {
                return trim($a, " \t\n\r\0\x0B\xc2\xa0");
            });

            $teto = $this->pedido->itens->first()->item->potencial_mensal_teto;
            $mesesContrato = $this->pedido->itens->first()->item->meses;
            $teto_porcentagem = explode('.', $teto);

            $view = ($this->pedido->itens->first()->name_item == 'CAP-30' ? 'contrato-cap30' : ($this->pedido->itens->first()->name_item == 'CAP-60' ? 'contrato-cap60' : 'contrato'));

            $pedido = $this->pedido;

            $pdf = \PDF::loadView('default.pedidos.'.$view, compact('pedido', 'user', 'endereco', 'teto', 'teto_porcentagem', 'mesesContrato', 'responsavel'));

            $contrato = 'licenca-n-'.$this->pedido->id.'-'.str_slug($this->pedido->itens->first()->name_item, '-').'.pdf';

            if ($pdf->setPaper('a4')
                ->setOption('enable-javascript', true)
                ->setOption('margin-bottom', 0)
                ->setOption('margin-top', 20)
                ->save(storage_path('app/contratos/'.$contrato))) {
                $pedido->contrato = $contrato;
                $pedido->save();

                if ($this->sendMail) {
                    dispatch(new SendContratoEmail($contrato, $this->pedido));
                }
            }
        } catch (FileAlreadyExistsException $e) {
        } catch (ModelNotFoundException $e) {
        }
    }
}
