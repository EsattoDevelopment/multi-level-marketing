<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners;

use Log;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Milhas;
use App\Models\Hospedes;
use App\Models\Movimentos;
use App\Events\PedidoFoiPago;
use Illuminate\Support\Facades\Auth;

class PedidoGeraHotel
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
        if ($event->sistema->rede_binaria) {
            Log::info('Gera hotel pelo item');
            $usuario = $event->getUsuario();
            $hotel = $usuario->hotel();

            if (! $hotel) {
                $itensPedido = $event->getItens();

                foreach ($itensPedido as $itemPedido) {
                    $item = $itemPedido->itens()->first();

                    if ($item->libera_hotel) {
                        return $this->criarHotel($usuario, $event);
                    }
                }
            } else {
                Log::info('Já tem hotel');
            }

            Log::info('sai - Gera hotel pelo item');

            return true;
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
        //$usuario->update(['qualificado' => 1]);
        //Log::info('Usuario qualificado', $usuario->toArray());

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

            Log::info('inserir hospede/hotel #'.$quarto->hotel_id);
            $dadosInsercao = $quarto->inserirHotel($hotelCriado->id);

            log::info($dadosInsercao['totalHospedes'].' quartos preenchidos no H.VIP #'.$dadosInsercao['quarto']->hotel_id);

            //TODO hotel concluido
            if ($dadosInsercao['totalHospedes'] == 30) {
                Log::info('Hotel atingiu ciclo #'.$dadosInsercao['quarto']->hotel_id);

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
        Log::info('Ciclando hotel #'.$dadosInsercao['quarto']->hotel_id);
        //TODO hotel concluido
        if ($dadosInsercao['totalHospedes'] == 30) {
            //TODO cicla hotel
            $hotelACliclar = $dadosInsercao['quarto']->hotel()->first();
            $hotelACliclar->fecharHotel();
            Log::info('Hotel fechado #'.$hotelACliclar->id);

            $usuarioHotelCiclado = $hotelACliclar->usuario()->first();

            $ultimoMovimento = Movimentos::whereUserId($usuarioHotelCiclado->id)->get()->last();

            //TODO adicionar as configurações de sistema (pendente)
            $bonusClico = $event->getConfiguracao()->bonus_ciclo_hotel;
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
            Log::info('Inserido movimento ciclo: ', $dadosMovimentoClico);

            //TODO pagamento das milhas
            $milhasCiclo = $event->getConfiguracao()->milhas_ciclo_hotel;
            $validadeMilhasCiclo = $event->getConfiguracao()->milhas_ciclo_hotel1825;
            $dadosMilhas = [
                'quantidade' => $milhasCiclo,
                'descricao' => $usuarioHotelCiclado->id,
                'descricao' => 'Milhas de pedidos referente a ciclo de H.VIP',
                'user_id' => $usuarioHotelCiclado->id,
                'validade' => Carbon::now()->addDays($validadeMilhasCiclo),
                'pedido_id' => $event->getPedido()->id,
            ];
            Milhas::create($dadosMilhas);
            Log::info('Inserido milhas do ciclo: ', $dadosMilhas);

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
                Log::info('Inserido movimento Payback: ', $dadosMovimentoPayBack);
            }

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

            //TODO cria hotel
            $hotelCriadoAposCiclo = Hotel::create([
                'user_id' => $usuarioHotelCiclado->id,
                'fechado' => 0,
            ]);
            Log::info('Criado novo hotel #'.$hotelCriadoAposCiclo->id);

            if ($usuarioHotelCiclado->id == 2) {
                Log::info('Ciclo do galaxy');
                $hotelIndicador = Hotel::with('quarto')->where('user_id', '<>', $usuarioHotelCiclado->id)->whereFechado(0)->get()->first();
            } else {
                Log::info('Ciclo usuario comum');
                $hotelIndicador = Hotel::with('quarto')->where('user_id', $usuarioHotelCiclado->indicador_id)->whereFechado(0)->first();
                Log::warning('quarto/hotel aberto do indicador, hotel #'.$hotelIndicador->id);
            }

            $quarto = $hotelIndicador->getRelation('quarto')->first();

            $dadosInsercao = $quarto->inserirHotel($hotelCriadoAposCiclo->id);

            //TODO insere outro hotel após ciclo
            if ($dadosInsercao['totalHospedes'] == 30) {
                Log::info('Ciclando hotel #'.$dadosInsercao['quarto']->hotel_id);

                return $this->ciclar($dadosInsercao, $event);
            }

            return true;
        }
    }
}
