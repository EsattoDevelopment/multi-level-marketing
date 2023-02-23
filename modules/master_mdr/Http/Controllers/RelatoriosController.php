<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace MasterMdr\Http\Controllers;

use App\Models\User;
use App\Models\Itens;
use App\Models\Sistema;
use Illuminate\Http\Request;
use App\Models\MetodoPagamento;
use MasterMdr\Services\Relatorios\SaquesRelatorioService;
use MasterMdr\Services\Relatorios\DepositosRelatorioService;
use MasterMdr\Services\Relatorios\PedidosPagosRelatorioService;

class RelatoriosController extends BaseController
{
    private $sistema;

    public function __construct()
    {
        $this->middleware('permission:master|admin');
        $this->sistema = Sistema::findOrFail(1);
    }

    public function pedidosPagos()
    {
        $metodosPagamentos = MetodoPagamento::whereStatus(1)->get();
        $itens = Itens::all();

        return view('master::Relatorios.pedidospagos', [
            'metodosPagamentos' => $metodosPagamentos,
            'itens' => $itens,
        ]);
    }

    public function saques()
    {
        $usuarios = User::whereStatus(1)->get();

        return view('master::Relatorios.saques', [
            'usuarios' =>  $usuarios,
        ]);
    }

    public function depositos()
    {
        $metodosPagamentos = MetodoPagamento::whereStatus(1)->get();
        $usuarios = User::whereStatus(1)->get();

        return view('master::Relatorios.depositos', [
            'metodosPagamentos' => $metodosPagamentos,
            'usuarios' =>  $usuarios,
        ]);
    }

    public function relatorioPedidosPagos(Request $request)
    {
        $pedidosPagos = new PedidosPagosRelatorioService($request->all());

        return $pedidosPagos->gerar();
    }

    public function relatorioSaques(Request $request)
    {
        $saques = new SaquesRelatorioService($request->all());

        return $saques->gerar();
    }

    public function relatorioDepositos(Request $request)
    {
        $depositos = new DepositosRelatorioService($request->all());

        return $depositos->gerar();
    }
}
