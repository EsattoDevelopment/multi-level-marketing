<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners;

use App\Models\Movimentos;
use App\Events\BonusMensalidade;

class MensalidadePaga
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BonusMensalidade $event
     *
     * @return void
     */
    public function handle(BonusMensalidade $event)
    {
        \Log::error("\n");
        \Log::error('___________________________Bonus $ Mensalidade__________________________');

        $indicador = $event->getUser()->getRelation('indicador');

        $qtdPago = 0;

        \Log::error('Pagamendo bonus de mensalidade #'.$event->getMensalidade()->id.' de #'.$event->getUser()->id.' '.$event->getUser()->name);

        //contador de pagamento, usado somente na mensagem
        $nParcela = 1;

        while ($qtdPago < 2) {

                //TODO Só entra se o status for 1 (Ativo)
            if (in_array($indicador->status, [1])) {
                $indicador->load([
                        'titulo' => function ($query) {
                            $query->select(['id', 'recebe_pontuacao']);
                        },
                    ]);

                if ($indicador->getRelation('titulo')->recebe_pontuacao == 1) {
                    //TODO resgata ultima movimentação
                    $ultimoMovimento = Movimentos::ultimoMovimentoUserId($indicador->id);

                    $valorBonus = round($event->getMensalidade()->valor * 0.05, 2);

                    //TODO pagamento dos bonus
                    $dadosMovimento = [
                            'valor_manipulado'    => $valorBonus,
                            'saldo_anterior'      => ! $ultimoMovimento ? 0 : $ultimoMovimento->saldo,
                            'saldo'               => ! $ultimoMovimento ? $valorBonus : $valorBonus + $ultimoMovimento->saldo,
                            'mensalidade_id'      => $event->getMensalidade()->id,
                            'documento'           => $event->getMensalidade()->numero_documento,
                            'descricao'           => "{$nParcela}º Bônus sobre mensalidade {$event->getMensalidade()->parcela} do contrato {$event->getMensalidade()->contrato_id} de {$event->getUser()->name}",
                            'responsavel_user_id' => \Auth::user()->id,
                            'user_id'             => $indicador->id,
                            'operacao_id'         => $qtdPago == 0 ? 18 : 19,
                        ];

                    Movimentos::create($dadosMovimento);

                    \Log::error("Pago bonus {$nParcela}º mensalidade para { $indicador->id } - { $indicador->name }", $dadosMovimento);
                } else {
                    \Log::error("O titulo do ID #{ $indicador->id } - { $indicador->name }, não recebe bonus");
                }
            } else {
                \Log::error("O status do ID #{ $indicador->id } - { $indicador->name }, não recebe bonus");
            }
            $qtdPago++;
            $nParcela++;
            $indicador->load('indicador');
            $indicador = $indicador->getRelation('indicador');

            if (is_null($indicador)) {
                \Log::error('Não há mais niveis acima!');

                break;
            }
        }

        return true;
    }
}
