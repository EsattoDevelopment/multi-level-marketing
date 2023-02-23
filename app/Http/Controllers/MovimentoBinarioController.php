<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Operacoes;
use App\Services\PagamentoPontosBinarios;
use App\Http\Requests\MovimentoBinarioRequest;

class MovimentoBinarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master|admin');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('default.movimento_binario.create', [
            'title' => 'Movimentos Binários',
            'operacoes' => Operacoes::whereIn('id', [24, 25])->get(),
            'usuarios' => User::whereStatus(1)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MovimentoBinarioRequest $request)
    {
        $user = User::findOrFail($request['user_id']);

        $movimento = (new PagamentoPontosBinarios)->user($user)->operacao($request->operacao_id)->descricao($request->descricao)->pontos($request->valor_manipulado)->lado($request->lado)->inserirPontos();

        if (! $movimento) {
            flash()->error('Desculpe, ocorreu um erro ao salvar o movimento.');

            return redirect()->route('movimento.binario.create');
        }

        flash()->success('Binário pago com sucesso!');

        return redirect()->route('movimento.binario.create');
    }
}
