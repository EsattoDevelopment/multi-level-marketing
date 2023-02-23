<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Rede;
use Illuminate\Support\Facades\Auth;

class MinhaRedeController extends Controller
{
    public function consultores()
    {
        return view('default.minha-rede.consultores', [
            'title' => 'Agentes',
            'dados' => User::whereIndicadorId(Auth::user()->id)
                //->whereStatus(1)
                ->with(['titulo' => function ($query) {
                    $query->select(['id', 'name']);
                }])
                ->whereHas('titulo', function ($query) {
                    $query->where('habilita_rede', 1);
                })
                ->select('id', 'name', 'username', 'indicador_id', 'email', 'titulo_id', 'telefone', 'celular', 'cpf', 'empresa')
                ->get(),
        ]);
    }

    public function consultoresInadimplente()
    {
        return view('default.minha-rede.consultores-inadimplentes', [
            'title' => 'Agentes inadimplentes',
            'dados' => User::with('endereco')
                ->whereIndicadorId(Auth::user()->id)
                ->whereStatus(2)
                ->select('id', 'name', 'username', 'indicador_id', 'email', 'telefone', 'celular', 'cpf', 'empresa')
                ->get(),
        ]);
    }

    public function contratos()
    {
        return view('default.minha-rede.contratos', [
            'title' => 'Cadastros diretos',
            'dados' => User::whereIndicadorId(Auth::user()->id)
                ->whereStatus(1)
                ->select('id', 'name', 'username', 'indicador_id', 'email', 'telefone', 'celular', 'cpf', 'empresa')
                ->get(),
        ]);
    }

    public function rede()
    {
        return view('default.minha-rede.rede', [
            'title' => 'Cadastros diretos',
            'dados' => User::with(['titulo' => function ($query) {
                $query->select(['id', 'name']);
            }])
                ->whereIndicadorId(Auth::user()->id)
                ->select('id', 'name', 'username', 'indicador_id', 'email', 'status', 'titulo_id', 'conta', 'telefone', 'celular', 'cpf', 'empresa')
                ->get(),
        ]);
    }

    public function visualizar()
    {
        return view('default.minha-rede.treeview', [
            'title' => 'Visualização de Rede',
            'dados' => User::with('diretos', 'titulo')->where('id', Auth::user()->id)->first(),
        ]);
    }
}
