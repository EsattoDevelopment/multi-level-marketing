<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Log;
use App\Models\User;
use App\Models\Milhas;
use App\Models\Estados;
use App\Models\Pacotes;
use App\Models\PedidoPacote;
use App\Events\ReservaRealizada;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UsarMilhasRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UsarGmilhasController extends Controller
{
    public function hospedagemIndex()
    {
        return view('default.usar-gmilhas.hospedagens', [
                'title'                        => 'Usar Gmilhas - Hospedagem',
                'tipo'                         => 1,
                'dados'                        => Pacotes::with('galeria.imagens', 'acomodacao')->whereTipoPacoteId(1)->whereInternacional(0)->whereStatus(1)->wherePromocao(0)->where('quantidade_vagas', '<>', 0)->get(),
                'dados_promocao'               => Pacotes::with('galeria.imagens', 'acomodacao')->whereTipoPacoteId(1)->whereInternacional(0)->whereStatus(1)->wherePromocao(1)->where('quantidade_vagas', '<>', 0)->get(),
                'dados_internacional'          => Pacotes::with('galeria.imagens', 'acomodacao')->whereTipoPacoteId(1)->whereInternacional(1)->whereStatus(1)->wherePromocao(0)->where('quantidade_vagas', '<>', 0)->get(),
                'dados_promocao_internacional' => Pacotes::with('galeria.imagens', 'acomodacao')->whereTipoPacoteId(1)->whereInternacional(1)->whereStatus(1)->wherePromocao(1)->where('quantidade_vagas', '<>', 0)->get(),
            ]);
    }

    public function pacoteIndex()
    {
        return view('default.usar-gmilhas.pacotes', [
                'title'                        => 'Usar Gmilhas - Pacotes',
                'tipo'                         => 1,
                'dados'                        => Pacotes::with('galeria.imagens', 'acomodacao')->whereTipoPacoteId(2)->whereInternacional(0)->whereStatus(1)->wherePromocao(0)->where('quantidade_vagas', '<>', 0)->get(),
                'dados_promocao'               => Pacotes::with('galeria.imagens', 'acomodacao')->whereTipoPacoteId(2)->whereInternacional(0)->whereStatus(1)->wherePromocao(1)->where('quantidade_vagas', '<>', 0)->get(),
                'dados_internacional'          => Pacotes::with('galeria.imagens', 'acomodacao')->whereTipoPacoteId(2)->whereInternacional(1)->whereStatus(1)->wherePromocao(0)->where('quantidade_vagas', '<>', 0)->get(),
                'dados_promocao_internacional' => Pacotes::with('galeria.imagens', 'acomodacao')->whereTipoPacoteId(2)->whereInternacional(1)->whereStatus(1)->wherePromocao(1)->where('quantidade_vagas', '<>', 0)->get(),
            ]);
    }

    public function cruzeiroIndex()
    {
        return view('default.usar-gmilhas.cruzeiros', [
                'title'                        => 'Usar Gmilhas - Cruzeiro',
                'tipo'                         => 3,
                'dados'                        => Pacotes::with('galeria.imagens', 'acomodacao')->whereTipoPacoteId(3)->whereInternacional(0)->whereStatus(1)->wherePromocao(0)->where('quantidade_vagas', '<>', 0)->get(),
                'dados_promocao'               => Pacotes::with('galeria.imagens', 'acomodacao')->whereTipoPacoteId(3)->whereInternacional(0)->whereStatus(1)->wherePromocao(1)->where('quantidade_vagas', '<>', 0)->get(),
                'dados_internacional'          => Pacotes::with('galeria.imagens', 'acomodacao')->whereTipoPacoteId(3)->whereInternacional(1)->whereStatus(1)->wherePromocao(0)->where('quantidade_vagas', '<>', 0)->get(),
                'dados_promocao_internacional' => Pacotes::with('galeria.imagens', 'acomodacao')->whereTipoPacoteId(3)->whereInternacional(1)->whereStatus(1)->wherePromocao(1)->where('quantidade_vagas', '<>', 0)->get(),
            ]);
    }

    public function hospedagemInterna($pacote)
    {
        try {
            return view('default.usar-gmilhas.interna', [
                    'title'   => 'Usar Gmilhas - Hospedagem',
                    'tipo'    => 1,
                    'estados' => Estados::all(),
                    'dados'   => Pacotes::with('galeria.imagens', 'acomodacao')->findOrFail($pacote),
                    'usuario' => User::with([
                        'milhas' => function ($query) {
                            $query->where('milhas.referencia', 0);
                        },
                    ])->find(Auth::user()->id),
                ]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, houve um erro ao abrir a hospedagem');

            return redirect()->route('usar-gmilhas.hospedagem');
        }
    }

    public function pacoteInterna($pacote)
    {
        try {
            return view('default.usar-gmilhas.interna', [
                    'title'   => 'Usar Gmilhas - Pacotes',
                    'tipo'    => 2,
                    'dados'   => Pacotes::with('galeria.imagens', 'acomodacao')->findOrFail($pacote),
                    'usuario' => User::with([
                        'milhas' => function ($query) {
                            $query->where('milhas.referencia', 0);
                        },
                    ])->find(Auth::user()->id),
                ]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, houve um erro ao abrir o pacote');

            return redirect()->route('usar-gmilhas.pacote');
        }
    }

    public function cruzeiroInterna($pacote)
    {
        try {
            return view('default.usar-gmilhas.interna', [
                    'title'   => 'Usar Gmilhas - Cruzeiros',
                    'tipo'    => 3,
                    'dados'   => Pacotes::with('galeria.imagens', 'acomodacao')->findOrFail($pacote),
                    'usuario' => User::with([
                        'milhas' => function ($query) {
                            $query->where('milhas.referencia', 0);
                        },
                    ])->find(Auth::user()->id),
                ]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, houve um erro ao abrir o cruzeiro');

            return redirect()->route('usar-gmilhas.cruzeiro');
        }
    }

    public function reservar(UsarMilhasRequest $request)
    {
        try {
            Log::info('$$$$$$$$$$$$$$$$---------Reserva---------$$$$$$$$$$$$$$$$$$');
            $pacote = Pacotes::with('acomodacao')->findOrFail($request->get('pacote'));
            if ($pacote->quantidade_vagas != 0) {
                DB::beginTransaction();

                $usuario = User::with([
                        'milhas' => function ($query) {
                            $query->where('milhas.referencia', 0);
                        },
                    ])->find(Auth::user()->id);

                $acomodacao = $pacote->getRelation('acomodacao')->where('id', (int) $request->get('acomodacao'))->first();

                if ($pacote->dias > 0) {
                    $valorTotalMilhas = $acomodacao->getRelation('pivot')->valor;
                } else {
                    $valorTotalMilhas = $request->get('total-diarias') * $acomodacao->getRelation('pivot')->valor;
                }

                $qtdMilhasUser = $usuario->getRelation('milhas')->sum('quantidade');

                if ($valorTotalMilhas > $qtdMilhasUser) {
                    flash()->error('Você não tem milhas o suficiente para fazer a reserva.');
                    redirect()->back()->withInput($request->all());
                }

                //Faz o voucher
                $iVoucher = 1;
                do {
                    $voucher = substr(md5(Auth::user()->id.$pacote->id.date('d/m/Y H:i:s').$iVoucher), 0, 6);

                    $verificaVoucher = PedidoPacote::whereVoucher($voucher)->get();

                    $iVoucher++;
                } while (count($verificaVoucher) > 0);

                //Cobra as milhas
                $milhasACobrar = 0;
                $milhasASerUsadas = [];
                foreach ($usuario->getRelation('milhas') as $milhaUser) {
                    if ($milhasACobrar >= $valorTotalMilhas) {
                        break;
                    } else {
                        $milhasASerUsadas[] = $milhaUser;
                        $milhasACobrar += $milhaUser->quantidade;

                        //verifica se ao adicionar ultima milha passou o valor necessario
                        if ($milhasACobrar > $valorTotalMilhas) {
                            $sobraMilhas['valor'] = $milhasACobrar - $valorTotalMilhas;
                            $sobraMilhas['validade'] = $milhaUser->validade;
                        }
                    }
                }

                //registra a reserva do pacote
                $pedidoPacote = PedidoPacote::create([
                        'valor_milhas_dia_compra' => $valorTotalMilhas,
                        'voucher'                 => $voucher,
                        'data_ida'                => $request->get('from'),
                        'data_volta'              => $request->get('to'),
                        'estados'                 => Estados::all(),
                        'acomodacao_valor'        => $acomodacao->getRelation('pivot')->valor,
                        'cidade_id'               => $pacote->local_selecionavel ? $request->get('cidade_id') : $pacote->cidade_id,
                        'status_id'               => 1,
                        'tipo_acomodacao_id'      => $acomodacao->id,
                        'pacote_id'               => $pacote->id,
                        'user_id'                 => Auth::user()->id,
                    ]);

                $pedidoPacote->load('pacote', 'acomodacao', 'usuario');

                Log::info('Realizado reserva', $pedidoPacote->toArray());

                //marca milhas como utilizadas
                foreach ($milhasASerUsadas as $value) {
                    $value->referencia = $pedidoPacote->id;
                    $value->utilizada_onde = 'Reserva: '.$pedidoPacote->id.', Voucher: '.$pedidoPacote->voucher;
                    $value->save();
                    Log::info('Usado milhas', $value->toArray());
                }

                //Se houver sobra de milhas restaura-as
                if (isset($sobraMilhas)) {
                    Milhas::create([
                            'quantidade' => $sobraMilhas['valor'],
                            'descricao'  => 'Sobra do uso na reserva: '.$pedidoPacote->id.', Voucher: '.$pedidoPacote->voucher,
                            'user_id'    => Auth::user()->id,
                            'validade'   => implode('-', array_reverse(explode('/', $sobraMilhas['validade']))),
                        ]);

                    Log::info("Restaurado {$sobraMilhas['valor']} milhas");
                }

                if ($pacote->quantidade_vagas > 0) {
                    $pacote->quantidade_vagas = $pacote->quantidade_vagas - 1;
                    $pacote->save();
                }

                $eventos = \Event::fire(new ReservaRealizada($pedidoPacote));

                foreach ($eventos as $key => $value) {
                    if ($value === false) {
                        DB::rollBack();

                        flash()->error('Desculpe, houve um erro ao realizar a reserva. Se o erro persistir entre em contato conosco!');

                        return redirect()->back();
                    }
                }

                DB::commit();

                flash()->success('Reserva realizada com sucesso!');

                return redirect()->route('home');
            } else {
                flash()->warning('Desculpe, reserva não pode ser efetuada, pois acabaram as vagas!');

                return redirect()->route('home');
            }
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            flash()->error('Desculpe, houve um erro ao realizar a reserva. Se o erro persistir entre em contato conosco!');

            return redirect()->back();
        }
    }
}
