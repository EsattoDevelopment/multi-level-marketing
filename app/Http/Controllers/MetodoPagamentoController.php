<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Log;
use Illuminate\Http\Request;
use App\Models\MetodoPagamento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TitulosRequest;
use App\Http\Requests\MetodoPagamentoRequest;

class MetodoPagamentoController extends Controller
{
    /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('permission:master', ['only' => ['destroy', 'delete']]);
        //$this->middleware('permission:admin', ['except' => ['destroy', 'delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('default.metodo_pagamento.index', [
            'title' => 'Método de Pagamento ',
            'data' => MetodoPagamento::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('default.metodo_pagamento.create', [
            'title' => 'Cadastro de Método de Pagamento ',
            'metodo_pagamento' => MetodoPagamento::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TitulosRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(MetodoPagamentoRequest $request)
    {
        try {
            MetodoPagamento::create($request->all());

            flash()->success('Método de Pagamento <strong>'.$request->name.'</strong> adicionado com sucesso!');

            Log::info('Método de Pagamento Cadastrado', $request->except('_token'));

            return redirect()->route('metodo_pagamento.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao salvar o Métoodo de Pagamento');

            Log::info('Erro ao cadastrar ', ['user' => Auth::user()->id]);

            return redirect()->route('metodo_pagamento.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $saldosBoletos = [];
        if ($id == 1) {
            //é boleto bancario então eu falo o calculo dos tetos
        }

        try {
            return view('default.metodo_pagamento.edit', [
                'title' => 'Edição de Método de Pagamento ',
                'dados' => MetodoPagamento::findOrFail($id),
            ]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao editar o Método de Pagamento');

            Log::info('Erro ao editar Método de Pagamento :'.$id, ['user' => Auth::user()->id]);

            return redirect()->route('metodo_pagamento.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TitulosRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(MetodoPagamentoRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $metodoPagamento = MetodoPagamento::findOrFail($id);

            //$request->merge(['user_id' => Auth::user()->id]);

            Log::info('Metodo Pagamento', $metodoPagamento->toArray());

            /*            if($request->get('titulo_inicial') == 1){
                            Titulos::whereTituloInicial(1)->where('id', '<>', $id)->update(['titulo_inicial' => 0]);
                            Log::info('Atualizado Titulo inicial para:'.$id, ['user' => Auth::user()->id]);
                        }*/
            //dd($request->all());
            $metodoPagamento->update($request->all());

            $metodoPagamento->save();

            DB::commit();

            flash()->success('Método de Pagamento <strong>'.$request->name.'</strong> atualizado com sucesso!');

            Log::info('Método de Pagamento Atualizado', $request->except('_token'));

            return redirect()->route('metodo_pagamento.index');
        } catch (ModelNotFoundException $e) {
            DB::rollback();

            flash()->error('Desculpe, erro ao editar o Método de Pagamento');

            Log::info('Erro ao editar o Método de Pagamento: '.$id, ['user' => Auth::user()->id]);

            return redirect()->route('metodo_pagamento.index');
        }
    }

    public function inativar($id)
    {
        DB::beginTransaction();
        try {
            $metodoPagamento = MetodoPagamento::findOrFail($id);

            Log::info('Metodo Pagamento', $metodoPagamento->toArray());

            $metodoPagamento->update(['status' => 0]);

            $metodoPagamento->save();

            DB::commit();
            //dd($metodoPagamento);

            flash()->success('Método de Pagamento inativado com sucesso!');

            Log::info('Método de Pagamento Inativado', $metodoPagamento->toArray());

            return redirect()->route('metodo_pagamento.index');
        } catch (ModelNotFoundException $e) {
            DB::rollback();

            flash()->error('Desculpe, erro ao desativar o Método de Pagamento');

            Log::info('Erro ao desativar o Método de Pagamento: '.$id, ['user' => Auth::user()->id]);

            return redirect()->route('metodo_pagamento.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Titulos::withTrashed()->findOrFail($id)->forceDelete();

            flash()->success('Titulo deletado da base de dados com sucesso!');

            return redirect()->route('titulo.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Erro ao deletar o Titulo da base de dados!');

            return redirect()->route('titulo.index');
        }
    }
}
