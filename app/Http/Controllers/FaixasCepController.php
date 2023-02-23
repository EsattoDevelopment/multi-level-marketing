<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FaixasCep;
use App\Http\Requests\FaixasCepRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FaixasCepController extends Controller
{
    public function __construct()
    {
        $this->middleware('manipularOutro', ['except' => 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user)
    {
        try {
            $user = User::findOrFail($user);

            return view('default.faixas_cep.index', [
                'title' => 'Lista de Faixas de CEP',
                'user' => $user,
                'dados' => FaixasCep::whereUserId($user->id)->get(),
            ]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, ocorreu um erro ao buscar o Usuário!');

            return redirect()->route('user.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($user)
    {
        try {
            $user = User::findOrFail($user);

            return view('default.faixas_cep.create', [
                'title' => 'Cadastrar faixa de CEP',
                'user' => $user,
            ]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, ocorreu um erro ao buscar o Usuário!');

            return redirect()->route('user.index');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($user, FaixasCepRequest $request)
    {
        try {
            $request['user_id'] = $user;
            $request['inicio'] = preg_replace('/[^0-9]/', '', $request->get('inicio'));
            $request['fim'] = preg_replace('/[^0-9]/', '', $request->get('fim'));

            FaixasCep::create($request->all());

            flash()->success("Faixa <b>{$request->get('inicio')}</b> - <b>{$request->get('fim')}</b> adicionada com sucesso!");

            return redirect()->route('user.{user}.faixas-cep.index', $user);
        } catch (ModelNotFoundException $e) {
            flash()->error('Não foi possível cadastrar a faixa, tente novamente');

            return redirect()->route('user.{user}.faixas-cep.index', $user);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user, $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($user, $id)
    {
        try {
            $user = User::findOrFail($user);

            return view('default.faixas_cep.edit', [
                'title' => 'Edição Faixa de CEP',
                'user' => $user,
                'dados' => FaixasCep::findOrFail($id),
            ]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, ocorreu um erro ao buscar o Usuário!');

            return redirect()->route('user.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($user, $id, FaixasCepRequest $request)
    {
        try {
            $request['inicio'] = preg_replace('/[^0-9]/', '', $request->get('inicio'));
            $request['fim'] = preg_replace('/[^0-9]/', '', $request->get('fim'));

            FaixasCep::findOrFail($id)->update($request->all());

            flash()->success('Faixa atualizada com sucesso!');

            return redirect()->route('user.{user}.faixas-cep.index', $user);
        } catch (ModelNotFoundException $e) {
            flash()->error('Não foi possível cadastrar a faixa, tente novamente');

            return redirect()->route('user.{user}.faixas-cep.index', $user);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user, $id)
    {
        try {
            FaixasCep::destroy($id);

            flash()->warning(sprintf('Faixa excluida com sucesso.'));

            return redirect()->route('user.{user}.faixas-cep.index', $user);
        } catch (ModelNotFoundException $e) {
            flash()->error('Não foi possível excluir a faixa, tente novamente');

            return redirect()->route('user.{user}.faixas-cep.index', $user);
        }
    }
}
