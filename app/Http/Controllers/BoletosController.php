<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use App\Models\Pedidos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Facades\Datatables;

class BoletosController extends Controller
{
    public function mensalidades()
    {
        return view('default.boletos.mensalidade', [
                'title' => 'Lista de Boletos de mensalidade',
            ]);
    }

    public function adesoes()
    {
        return view('default.boletos.adesao', [
                'title' => 'Lista de Boletos de adesões',
            ]);
    }

    public function getBoletosMensalidades()
    {
        $boletos = DB::table('boletos as b')
                ->Join('mensalidades as m', 'm.boleto_id', '=', 'b.id')
                ->Join('users as u', 'u.id', '=', 'm.user_id')
                ->select([
                    'm.id',
                    'u.name',
                    'b.vencimento',
                    'b.nosso_numero',
                    'm.valor',
                ]);

        return $this->getBoletos($boletos, true);
    }

    public function getBoletosAdesoes()
    {
        $boletos = DB::table('boletos as b')
                ->Join('pedidos as p', 'p.boleto_id', '=', 'b.id')
                ->Join('users as u', 'u.id', '=', 'p.user_id')
                ->select([
                    'p.id',
                    'u.name',
                    'b.vencimento',
                    'b.nosso_numero',
                    'p.valor_total',
                ]);

        return $this->getBoletos($boletos, false);
    }

    private function getBoletos($boletos, $mensalidade = true)
    {
        $boletos->orderBy('b.id', 'desc');

        $datatables = Datatables::of($boletos);

        $datatables->addColumn('action', function ($boleto) use ($mensalidade) {
            $retorno = '';

            if ($mensalidade) {
                $retorno .= '<a title="Mensalidade" class="btn btn-default btn-sm" href="'.route('mensalidade.edit', $boleto->id).'">
                            <span class="glyphicon glyphicon-edit text-blue" aria-hidden="true"> </span>Visuzalizar
                        </a>';
            } else {
                $retorno .= '<a title="Adesão" class="btn btn-default btn-sm" href="'.route('pedido.show', $boleto->id).'">
                            <span class="glyphicon glyphicon-edit text-blue" aria-hidden="true"> </span>Visuzalizar
                        </a>';
            }

            return $retorno;
        })->editColumn('vencimento', function ($boleto) {
            return implode('/', array_reverse(explode('-', explode(' ', $boleto->vencimento)[0])));
        });

        return $datatables->make(true);
    }

    public function abrirBoleto($user, $id)
    {
        $pedido = Pedidos::with('dadosPagamento')->whereId($id)->first();

        if ($pedido->boleto_id) {
            $boleto = $pedido->boleto()->nosso_numero.'.pdf';

            return response()->make(Storage::get('boletos/'.$boleto), 200,
                    [
                        'Content-Type'        => Storage::mimeType('boletos/'.$boleto),
                        'Content-Disposition' => 'inline; filename="'.$boleto.'"',
                    ]
                );
        }
    }
}
