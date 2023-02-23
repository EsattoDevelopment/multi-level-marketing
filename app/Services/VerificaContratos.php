<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

class VerificaContratos
{
    public function __construct($event)
    {
        \Log::info('Verificando contrato.......');

        $mensalidade = $event->getMensalidade()
            ->load([
                'contrato' => function ($query) {
                    $query->select([
                        'id',
                        'status',
                        'aguarda_mensalidade',
                    ]);
                }, ]);

        \Log::info("Evento da mensalidade #{$mensalidade->id}");

        $contrato = $mensalidade->getRelation('contrato');

        // verifica se contrato esta em atraso
        if ($contrato->status == 3) {

            //verifica se o contrato esta em atraso por causa dessa mensalidade
            //if ($contrato->aguarda_mensalidade == $mensalidade->id) {

            //verifica se há mais mensalidade pendentes nesse contrato fora essa
            $contrato->load(['mensalidades' => function ($query) use ($mensalidade) {
                $query->whereIn('status', [1, 2, 3])
                        ->where('id', '<>', $mensalidade->id)
                        ->select(['contrato_id', 'id', 'status']);
            }]);

            // verifica se há mais mensalidade em atraso
            if ($contrato->getRelation('mensalidades')->where('status', 'Atrasada')->count() > 0) {

                    //verifica se há mensalidades com status aguardando para colocar como dependencia do contrato
                if ($contrato->getRelation('mensalidades')->where('status', 'Aguardando')->count() > 0) {
                    $mensalidadesAguardando = $contrato->getRelation('mensalidades')->where('status', 'Aguardando')->min();

                    if ($contrato->getRelation('mensalidades')->where('status', 'Proxima')->count() == 0) {
                        //seta mensalidade como proxima
                        $mensalidadesAguardando->update(['status' => 2]);
                    }
                } else {
                    $mensalidadesAguardando = $contrato->getRelation('mensalidades')->where('status', 'Atrasada')->max();
                }

                $contrato->aguarda_mensalidade = $mensalidadesAguardando->id;

                \Log::info("Há mais mensalidade em atraso no contrato #{$contrato->id}");
            } else { //caso não haja mensalidade em atraso
                \Log::info('Não há mais pendencias no contrato!');

                //Verifica se ha mensalidades aguardando
                if ($contrato->getRelation('mensalidades')->where('status', 'Aguardando')->count() == 0) {

                        // se o usuário não for empresarial
                    if (1 == $event->getUser()->tipo) {

                            // caso não tenho mais mensalidade significa que o contrato foi finalizado
                        //Status 5 é finalizado
                        $contrato->status = 5;
                        \Log::info('*Não há mais mensalidades, contrato setado como finalizando*');
                    } else {

                            //contrato de empresarial sem mensalidades pendentes torna-se ativo
                        $contrato->status = 2;
                        \Log::info('Contrato empresarial, setado como ativo!');
                    }
                } else { //Ainda há mensalidades mas nenhuma em atraso

                    $contrato->status = 2;
                    $contrato->aguarda_mensalidade = $mensalidade->proxima;
                    \Log::info('Contrato setado como ativo');
                }

                //altera status do usuário
                $event->getUser()->update(['status' => 1]);

                \Log::info('Usuário setado como ativo');
            }

            //salva alterações feita no contrato
            $contrato->save();

        /* } else {
             \Log::info("Mensalidade paga não corresponde a mensalidade necessária para mudar o status do contrato");
         }*/
        } else {
            // seta a proxima mensalidade como proxima
            $nextMensalidade = $mensalidade->nextMensalidade()->first();

            if ($nextMensalidade) {
                $nextMensalidade->status = 2;
                $nextMensalidade->save();

                $contrato->aguarda_mensalidade = $nextMensalidade->id;
            }
        }
    }
}
