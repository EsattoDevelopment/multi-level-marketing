<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Plataforma;
use Illuminate\Support\Facades\Log;

class PlataformaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('default.plataforma.index', [
            'title'                 => 'Lista de Plataforma',
            'dados'                 => Plataforma::where('status', 1)->get(),
            'dados_desativados'     => Plataforma::where('status', 0)->get(), //Desativados
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('default.plataforma.create', [
            'title' => 'Cadastro de Plataforma',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\PlataformaRequest $request)
    {
        try {
            Plataforma::create($request->all());
            Log::info('Plataforma Cadastrada');
            flash()->success('Plataforma <strong>'.$request->nome.'</strong> cadastrada com sucesso!');

            return redirect()->route('plataforma.index');
        } catch (ModelNotFoundException $e) {
            Log::info('Erro ao cadastrar plataforma');
            flash()->error('Desculpe, erro ao salvar a plataforma');

            return redirect()->route('plataforma.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            return view('default.plataforma.edit', [
                'dados' => Plataforma::findOrFail($id),
                'title' => 'Edição do Histórico de Rentabilidade ',
            ]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, ocorreu um erro ao buscar a plataforma!');

            return redirect()->route('plataforma.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\PlataformaRequest $request, $id)
    {
        try {
            $plataforma = Plataforma::find($id);
            $plataforma->update($request->all());

            Log::info('Plataforma alterada');
            flash()->success('Plataforma <strong>'.$request->nome.'</strong> alterada com sucesso!');

            return redirect()->route('plataforma.index');
        } catch (ModelNotFoundException $e) {
            Log::info('Erro ao alterar plataforma');
            flash()->error('Desculpe, erro ao alterar a plataforma');

            return redirect()->route('plataforma.index');
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
        //
    }

    public function desativar($id)
    {
        try {
            $plataforma = Plataforma::find($id);
            $plataforma->status = 0;
            $plataforma->update();

            flash()->success('Plataforma desativada com sucesso!');

            return redirect()->route('plataforma.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao desativar a plataforma.');

            return redirect()->route('plataforma.index');
        }
    }

    public function ativar($id)
    {
        try {
            $plataforma = Plataforma::find($id);
            $plataforma->status = 1;
            $plataforma->update();

            flash()->success('Plataforma ativada com sucesso!');

            return redirect()->route('plataforma.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao ativar a plataforma.');

            return redirect()->route('plataforma.index');
        }
    }
}
