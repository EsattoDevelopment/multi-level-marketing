<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Log;
use App\Models\PedidoPacote;
use Illuminate\Http\Request;
use App\Events\CancelamentoReserva;
use Illuminate\Support\Facades\Auth;

class ReservasController extends Controller
{
    public function hospedagem()
    {
        return view('default.reservas.hospedagem', [
                'title' => 'Reservas - Hospedagens ',
                'dados' => PedidoPacote::whereHas('pacote', function ($query) {
                    $query->where('tipo_pacote_id', 1);
                })
                    ->with('pacote', 'statusPedidoPacote', 'acomodacao')
                    ->where('user_id', Auth::user()->id)
                    ->get(),
            ]);
    }

    public function pacotes()
    {
        return view('default.reservas.pacotes', [
                'title' => 'Reservas - Pacotes ',
                'dados' => PedidoPacote::whereHas('pacote', function ($query) {
                    $query->where('tipo_pacote_id', 2);
                })
                    ->with('pacote', 'statusPedidoPacote', 'acomodacao')
                    ->where('user_id', Auth::user()->id)
                    ->get(),
            ]);
    }

    public function cruzeiros()
    {
        return view('default.reservas.cruzeiros', [
                'title' => 'Reservas - Cruzeiros ',
                'dados' => PedidoPacote::whereHas('pacote', function ($query) {
                    $query->where('tipo_pacote_id', 3);
                })
                    ->with('pacote', 'statusPedidoPacote', 'acomodacao')
                    ->where('user_id', Auth::user()->id)
                    ->get(),
            ]);
    }

    public function visualizar($pacote)
    {
        try {
            $reserva = PedidoPacote::with('usuario', 'acomodacao', 'statusPedidoPacote', 'pacote.galeria.imagens', 'pacote.tipoPacote')->where('user_id', Auth::user()->id)->where('id', $pacote)->first();

            return view('default.reservas.visualizar', [
                    'title'   => 'Reserva ',
                    'dados'   => $reserva,
                ]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, houve um erro ao abrir a reserva');

            return redirect()->back();
        }
    }

    public function cancelamento(Request $request)
    {
        try {
            if ($request->has('reserva')) {
                $reserva = PedidoPacote::where('id', $request->get('reserva'))->where('user_id', Auth::user()->id)->first();
                $reserva->status_id = 3;
                $reserva->save();

                \Event::fire(new CancelamentoReserva($reserva));

                Log::info('Solicitado cancelamento da reserva :'.$reserva->id);

                flash()->success('Solicitação de cancelamento realizada com sucesso, entraremos em contato em breve!');

                return redirect()->back();
            }
        } catch (\Exception $e) {
            flash()->error('Desculpe, houve algum erro ao solicitar o cancelamento!');

            return redirect()->back();
        }
    }
}
