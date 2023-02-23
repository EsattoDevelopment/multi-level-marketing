<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners\RodarSistema;

use Log;
use App\Models\Movimentos;
use App\Events\RodarSistema;
use App\Models\ExtratoBinario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RodaBinario
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
     * @param RodarSistema $event
     * @return void
     */
    public function handle(RodarSistema $event)
    {
        if ($event->sistema->rede_binaria) {
            Log::info('%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%');
            Log::info('entrou no rodar binario');
            $usuarios = $event->getObjUsuario()->with('titulo')->whereNotIn('status', [0])->where('username', '<>', 'admin')->get();

            try {
                foreach ($usuarios as $usuario) {
                    Log::info('Usuario: #'.$usuario->id.' - '.$usuario->name);
                    $extratoBinario = $usuario->extratoBinario()->sortByDesc('id')->take(1)->first();

                    if ($extratoBinario) {

                        // verifica se há saldo para rodar o binario
                        if (($extratoBinario->saldo_esquerda > 0) && ($extratoBinario->saldo_direita > 0)) {
                            Log::info($extratoBinario->saldo_esquerda.' - '.$extratoBinario->saldo_direita);

                            if ($extratoBinario->saldo_esquerda < $extratoBinario->saldo_direita) {
                                Log::info('Esquerdo menor');
                                $saldodireita = $extratoBinario->saldo_direita - $extratoBinario->saldo_esquerda;
                                $pontosUtilizados = $extratoBinario->saldo_esquerda;
                                $saldoEsquerda = 0;
                            } else {
                                Log::info('Direto menor');
                                $saldoEsquerda = $extratoBinario->saldo_esquerda - $extratoBinario->saldo_direita;
                                $pontosUtilizados = $extratoBinario->saldo_direita;
                                $saldodireita = 0;
                            }

                            Log::info('extrato binario antes upgrade', $extratoBinario->toArray());
                            $dadosExtratoBinario = [
                                'pontos' => $pontosUtilizados,
                                'saldo_anterior' => $extratoBinario->saldo,
                                'saldo' => $extratoBinario->saldo,
                                'referencia' => $extratoBinario->id,
                                'acumulado_direita' => $extratoBinario->acumulado_direita,
                                'acumulado_esquerda' => $extratoBinario->acumulado_esquerda,
                                'acumulado_total' => $extratoBinario->acumulado_total,
                                'saldo_direita' => $saldodireita,
                                'saldo_esquerda' => $saldoEsquerda,

                                'user_id' => $usuario->id,
                                'operacao_id' => 10,
                            ];

                            ExtratoBinario::create($dadosExtratoBinario);
                            Log::info('Update extrato binario', $dadosExtratoBinario);

                            //TODO bonus vindo do calculo binario
                            $bonusBinario = ($usuario->getRelation('titulo')->percentual_binario * $pontosUtilizados) / 100;
                            Log::info('Quantidade de binario utilizado: '.$pontosUtilizados);
                            Log::info('Saldo a receber: '.$bonusBinario);

                            //TODO resgata ganhos mensais
                            $ganhosMes = Movimentos::whereUserId($usuario->id)->whereNotIn('operacao_id', [4, 5, 6, 7, 8])->where(DB::raw('MONTH(created_at)'), '=', date('m'))->sum('valor_manipulado');

                            //TODO verifica se ganhos mensais ultrapassaram o teto
                            if ($ganhosMes < $usuario->getRelation('titulo')->teto_mensal_financeiro) {

                                //TODO resgata ultima movimentação
                                $ultimoMovimento = Movimentos::whereUserId($usuario->id)->get()->last();

                                //TODO pagamento dos bonus
                                $dadosMovimento = [
                                    'valor_manipulado' => $bonusBinario,
                                    'saldo_anterior' => ! $ultimoMovimento ? 0 : $ultimoMovimento->saldo,
                                    'saldo' => ! $ultimoMovimento ? $bonusBinario : $bonusBinario + $ultimoMovimento->saldo,
                                    'descricao' => 'Bônus de binário '.date('d-m-Y'),
                                    'referencia' => 0,
                                    'responsavel_user_id' => ! Auth::user() ? 1 : ! Auth::user()->id,

                                    'user_id' => $usuario->id,
                                    'operacao_id' => 10,
                                ];

                                $movimentoAtual = Movimentos::create($dadosMovimento);
                                Log::info('Inserido movimento: ', $dadosMovimento);

                                //TODO resgata ganhos mensais
                                $objMovimento = new Movimentos();
                                $ganhosMes = $objMovimento->ganhosDoMes($usuario->id);

                                //TODO verifica se a soma do bonus com os bonus já ganhados no mês, não ultrapassam o teto do titulo
                                if ($ganhosMes > $usuario->getRelation('titulo')->teto_mensal_financeiro) {
                                    $totalPayback = $movimentoAtual->saldo - $usuario->getRelation('titulo')->teto_mensal_financeiro;

                                    //TODO pagamento dos bonus
                                    $dadosMovimentoPayBack = [
                                        'valor_manipulado' => $totalPayback,
                                        'saldo_anterior' => $movimentoAtual->saldo,
                                        'saldo' => $movimentoAtual->saldo - $totalPayback,
                                        'referencia' => 0,
                                        'descricao' => 'Teto de ganhos do titulo '.$usuario->getRelation('titulo')->name,
                                        'responsavel_user_id' => ! Auth::user() ? 1 : ! Auth::user()->idd,
                                        'user_id' => $usuario->id,
                                        'operacao_id' => 11,
                                    ];

                                    Movimentos::create($dadosMovimentoPayBack);
                                    Log::info('Inserido movimento payback: ', $dadosMovimentoPayBack);
                                }
                            } else {
                                /*
                                 * Colocar mensagem para usuario e para sistema
                                 */
                                Log::info('Os ganhos do usuario: '.$usuario->id.', ultrapassam os ganhos mensais do titulo');
                            }
                        } else {
                            Log::info('Sem saldo suficiente');
                        }
                    } else {
                        Log::info('Não tem extrato binario');
                    }
                }

                Log::info('saiu do rodar binario');

                return true;
            } catch (ModelNotFoundException $e) {
                Log::info('Falhou pagamento bonus do binario');
            }

            return true;
        }
    }
}
