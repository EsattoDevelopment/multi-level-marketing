<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners;

use Log;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Titulos;
use App\Models\Hospedes;
use App\Models\Movimentos;
use App\Events\PedidoFoiPago;
use App\Models\UpgradeTitulo;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class QualificaUsuarioPosicionaHotel
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
     * @param  PedidoFoiPago  $event
     * @return void
     */
    public function handle(PedidoFoiPago $event)
    {
        if ($event->sistema->sistema_viagem) {
            try {
                Log::info('Entrou no qualifica usuario');
                $usuarioPedido = $event->getUsuario();

                $usuario = $event->getUsuario()->indicador()->first();

                if ($usuario) {
                    Log::info('tem indicador #'.$usuario->id);
                    if (! $usuario->qualificado) {
                        Log::info('Não esta qualificado');
                        //TODO colocar isso via sistema (pendente)
                        $diretosNecessarios = 2;

                        $diretos_aprovados = $usuario->diretosAprovados()->count();

                        //TODO verifica diretos suficientes para ser qualificado
                        if ($diretos_aprovados >= $diretosNecessarios) {
                            Log::info('Tem usuario para qualificar');

                            $usuario->update(['qualificado' => 1]);
                            Log::info('Usuario qualificado', $usuario->toArray());

                            $titulo = Titulos::find(5);
                            if ($titulo->maiorQue($usuario->titulo()->first())) {
                                UpgradeTitulo::create(['user_id' => $usuario->id, 'titulo_id' => $titulo->id]);
                                $usuario->titulo_id = $titulo->id;
                                $usuario->save();
                                Log::info('Usuario '.$usuario->name.' - '.$usuario->id.', subiu para titulo:'.$titulo->name);
                            } else {
                                Log::info('Titulo inferior ou igual');
                            }

                            //verifica se ja tem hotel
                            $hotel = $usuario->hotel();
                            if (! $hotel) {
                                return $this->criarHotel($usuario, $event);
                            } else {
                                Log::info('Já tem hotel');
                            }
                        } else {
                            Log::info('Sem diretos minimos');
                        }
                    } else {
                        Log::info('Indicador já qualificado');
                    }
                } else {
                    Log::info('Galaxy clube');
                    $itensPedido = $event->getItens()->first()->itens()->get();

                    foreach ($itensPedido as $item) {
                        if ($item->libera_hotel) {
                            Log::info('Item comprado da direito a H.VIP');

                            return $this->criarHotel($usuarioPedido, $event);
                        }
                    }
                }
                Log::info('saiu qualifica usuario');

                return true;
            } catch (ModelNotFoundException $e) {
                Log::info('Erro no Qualifica usuario');

                return false;
            }
        }

        return true;
    }

    /**
     * @param User $usuario
     * @param PedidoFoiPago $event
     * @return bool
     */
    private function criarHotel(User $usuario, PedidoFoiPago $event)
    {
        //TODO cria hotel
        $hotelCriado = Hotel::create([
            'user_id' => $usuario->id,
            'fechado' => 0,
        ]);
        Log::info('Criado Hotel', $hotelCriado->toArray());

        //TODO verifica se o usuario tem indicador para posicionar hotel
        if ($usuario->indicador_id) {
            $objHotel = new Hotel();
            $hotelIndicador = $objHotel->hotelIndicador($usuario->indicador_id);

            $quarto = $hotelIndicador->getRelation('quarto');

            Log::info('inserir hospede/hotel #'.$hotelCriado->id);
            $dadosInsercao = $quarto->inserirHotel($hotelCriado->id);

            log::info($dadosInsercao['totalHospedes'].' quartos preenchidos no H.VIP #'.$dadosInsercao['quarto']->hotel_id);

            //TODO hotel concluido
            if ($dadosInsercao['totalHospedes'] == 30) {
                return $this->ciclar($dadosInsercao, $event);
            }

            return true;
        } else {
            $todosQuartos = Hospedes::all()->count();
            if ($todosQuartos == 0) {
                Hospedes::create([
                    'hotel_id' => $hotelCriado->id,
                ]);
                Log::info('inserir hospede/hotel para Galaxy clube');
            }

            return true;
        }
    }

    /**
     * @param array $dadosInsercao
     * @param PedidoFoiPago $event
     * @return bool
     */
    private function ciclar(array $dadosInsercao, PedidoFoiPago $event)
    {
        //TODO hotel concluido
        if ($dadosInsercao['totalHospedes'] == 30) {
            //TODO cicla hotel
            $hotelACliclar = $dadosInsercao['quarto']->hotel()->first();
            $hotelACliclar->fecharHotel();
            $usuarioHotelCiclado = $hotelACliclar->usuario()->first();

            $ultimoMovimento = Movimentos::whereUserId($usuarioHotelCiclado->id)->get()->last();

            //TODO adicionar as configurações de sistema (pendente)
            $bonusClico = 600;
            $dadosMovimentoClico = [
                'valor_manipulado' => $bonusClico,
                'saldo_anterior' => $ultimoMovimento->saldo,
                'saldo' => $ultimoMovimento->saldo + $bonusClico,
                'referencia' => $event->getPedido()->id,
                'documento' => $event->getDadosPagamento()->first()->id,
                'descricao' => 'Bônus de ciclo de Hotel',
                'responsavel_user_id' => Auth::user()->id,
                'user_id' => $usuarioHotelCiclado->id,
                'operacao_id' => 2,
            ];
            Movimentos::create($dadosMovimentoClico);
            Log::notice('Inserido movimento ciclo: ', $dadosMovimentoClico);

            //TODO pagamento das milhas
            $milhasCiclo = 20000;
            $validadeMilhasCiclo = 1825;
            $dadosMilhas = [
                'quantidade' => $milhasCiclo,
                'descricao' => $usuarioHotelCiclado->id,
                'user_id' => 'Milhas de pedidos referente a ciclo de H.VIP',
                'validade' => Carbon::now()->addDays($validadeMilhasCiclo),
                'pedido_id' => $event->getPedido()->id,
            ];
            Milhas::create($dadosMilhas);
            Log::notice('Inserido milhas do ciclo: ', $dadosMilhas);

            //TODO taxa de novo hotel
            $valorNovoHotel = 200;
            $dadosMovimentoClicoNovoHotel = [
                'valor_manipulado' => $valorNovoHotel,
                'saldo_anterior' => $ultimoMovimento->saldo,
                'saldo' => $ultimoMovimento->saldo - $valorNovoHotel,
                'referencia' => $event->getPedido()->id,
                'documento' => $event->getDadosPagamento()->first()->id,
                'descricao' => 'Taxa de novo Hotel',
                'responsavel_user_id' => Auth::user()->id,
                'user_id' => $usuarioHotelCiclado->id,
                'operacao_id' => 8,
            ];
            $movimentoAtual = Movimentos::create($dadosMovimentoClicoNovoHotel);

            //TODO resgata ganhos mensais
            $objMovimento = new Movimentos();
            $ganhosMes = $objMovimento->ganhosDoMes($usuarioHotelCiclado->id);

            //TODO instancia titulo
            $titulo = $usuarioHotelCiclado->titulo()->first();

            //TODO verifica se a soma do bonus com os bonus já ganhados no mês, não ultrapassam o teto do titulo
            if ($ganhosMes > $titulo->teto_mensal_financeiro) {
                $totalPayback = $ganhosMes - $titulo->teto_mensal_financeiro;
                $dadosMovimentoPayBack = [
                    'valor_manipulado' => $totalPayback,
                    'saldo_anterior' => $movimentoAtual->saldo,
                    'saldo' => $movimentoAtual - $totalPayback,
                    'referencia' => $event->getPedido()->id,
                    'documento' => $event->getDadosPagamento()->first()->id,
                    'descricao' => 'Titulo de ganhos do titulo '.$titulo->name,
                    'responsavel_user_id' => Auth::user()->id,
                    'user_id' => $usuarioHotelCiclado->id,
                    'operacao_id' => 11,
                ];
                Movimentos::create($dadosMovimentoPayBack);
                Log::notice('Inserido movimento Payback: ', $dadosMovimentoPayBack);
            }

            //TODO cria hotel
            $hotelCriadoAposCiclo = Hotel::create([
                'user_id' => $usuarioHotelCiclado->id,
                'fechado' => 0,
            ]);

            Log::notice('Criado Hotel #'.$hotelCriadoAposCiclo->id);

            if ($usuarioHotelCiclado->id == 2) {
                $hotelIndicador = Hotel::with('quarto')->whereUserId($usuarioHotelCiclado->indicador_id)->whereFechado(0)->first();
            } else {
                $hotelIndicador = Hotel::with('quarto')->whereFechado(0)->sortByDesc('created_at')->take(5)->first();
            }

            $quarto = $hotelIndicador->getRelation('quarto')->first();

            $dadosInsercao = $quarto->inserirHotel($hotelCriadoAposCiclo->id);

            $indicador = $usuarioHotelCiclado->indicador()->get()->first();

            if ($indicador->titulo_id == 1) {
                Log::info('Bônus Diamante');
                Log::info('Indicador é Diamante, user #'.$indicador->id);

                $ultimoMovimentoIndicador = Movimentos::whereUserId($indicador->id)->get()->last();

                $bonusDiamante = 400 * 0.1;

                $dadosMovimentoBonusDiamanteCiclo = [
                    'valor_manipulado' => $bonusDiamante,
                    'saldo_anterior' => $ultimoMovimentoIndicador->saldo,
                    'saldo' => $ultimoMovimentoIndicador->saldo + $bonusClico,
                    'referencia' => 0,
                    'documento' => '',
                    'descricao' => 'Bônus liderança 10% Ciclo H.VIP, direto #'.$usuarioHotelCiclado->id,
                    'responsavel_user_id' => Auth::user()->id,
                    'user_id' => $indicador->id,
                    'operacao_id' => 15,
                ];

                Movimentos::create($dadosMovimentoBonusDiamanteCiclo);
                Log::notice('Inserido movimento ciclo: ', $dadosMovimentoBonusDiamanteCiclo);
            }

            //TODO insere outro hotel após ciclo
            if ($dadosInsercao['totalHospedes'] == 30) {
                return $this->ciclar($dadosInsercao, $event);
            }

            return true;
        }
    }
}
