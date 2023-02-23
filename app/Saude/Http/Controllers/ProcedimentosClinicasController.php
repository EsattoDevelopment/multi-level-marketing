<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Http\Controllers;

use Illuminate\Http\Request;
use App\Saude\Domains\ProcedimentoClinica;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProcedimentosClinicasController extends BaseController
{
    public function __construct()
    {
        $this->middleware('manipularOutro', ['only' => 'index']);
    }

    public function getFromClinica(Request $request)
    {
        $dados = ProcedimentoClinica::with('procedimento')->where('user_id', $request->get('clinica'))->get();

        if (! $dados) {
            $dados = false;
        }

        return \Response::json($dados);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user)
    {
        return $this->view('procedimentos_clinicas.index', [
            'title' => 'Lista de procedimentos',
            'dados' => ProcedimentoClinica::whereUserId($user)->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($user, $procedimento)
    {
        try {
            $procedimento = ProcedimentoClinica::whereUserId($user)->whereProcedimentoId($procedimento)->first();

            return $this->view('procedimentos_clinicas.edit', [
                'title' => ($procedimento->name ?: $procedimento->procedimento->name).' - Edição de procedimento',
                'dados' => $procedimento,
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('saude.procedimentos_clinicas.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user, $procedimento)
    {
        try {
            ProcedimentoClinica::whereUserId($user)->whereProcedimentoId($procedimento)->update(['name' => $request->get('name'), 'valor' => $request->get('valor')]);

            flash()->success('Procedimento atualizado com sucesso!');

            return redirect()->route('saude.procedimentos_clinica.index', [$user]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, houve um erro na operação!');

            return redirect()->back()->withInput();
        }
    }
}
