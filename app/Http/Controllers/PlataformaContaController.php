<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Plataforma;
use App\Models\PlataformaConta;
use Illuminate\Support\Facades\Log;

class PlataformaContaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($plataforma_id)
    {
        return view('default.plataforma_conta.create', [
            'title'                 => 'Cadastro de Conta por Plataforma',
            'plataforma' => Plataforma::where('id', $plataforma_id)->first(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\PlataformaContaRequest $request)
    {
        try {
            //dd($request->all());
            PlataformaConta::create($request->all());
            Log::info('Conta de Plataforma Cadastrada');
            flash()->success('Conta <strong>'.$request->nome.'</strong> cadastrada com sucesso!');

            return redirect()->route('plataforma-conta.show', $request->plataforma_id);
        } catch (ModelNotFoundException $e) {
            Log::info('Erro ao cadastrar conta de plataforma');
            flash()->error('Desculpe, erro ao salvar a conta de plataforma');

            return redirect()->route('plataforma-conta.show', $request->plataforma_id);
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
        return view('default.plataforma_conta.index', [
            'title'                 => 'Lista de Contas',
            'dados'                 => PlataformaConta::with('plataforma')->where('plataforma_id', $id)->where('status', 1)->get(),
            'dados_desativados'     => PlataformaConta::with('plataforma')->where('plataforma_id', $id)->where('status', 0)->get(), //Desativados
            'plataforma' => Plataforma::where('id', $id)->first(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($plataforma_id, $id)
    {
        try {
            return view('default.plataforma_conta.edit', [
                'dados' => PlataformaConta::where('id', $id)->first(),
                'title' => 'Edição de Conta ',
                'plataforma' => Plataforma::where('id', $plataforma_id)->first(),
            ]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, ocorreu um erro ao buscar a conta!');

            return redirect()->route('plataforma-conta.show', $plataforma_id);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\PlataformaContaRequest $request, $id)
    {
        try {
            $plataformaConta = PlataformaConta::where('id', $id)->first();
            $plataformaConta->update($request->all());

            Log::info('Conta de Plataforma alterada');
            flash()->success('Conta <strong>'.$request->nome.'</strong> alterada com sucesso!');

            return redirect()->route('plataforma-conta.show', $request->plataforma_id);
        } catch (ModelNotFoundException $e) {
            Log::info('Erro ao alterar conta');
            flash()->error('Desculpe, erro ao alterar a conta');

            return redirect()->route('plataforma-conta.show', $request->plataforma_id);
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

    public function desativar($plataforma_id, $id)
    {
        try {
            $plataformaConta = PlataformaConta::where('id', $id)->first();
            $plataformaConta->status = 0;
            $plataformaConta->update();

            flash()->success('Conta de Plataforma desativada com sucesso!');

            return redirect()->route('plataforma-conta.show', $plataforma_id);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao desativar a conta de plataforma.');

            return redirect()->route('plataforma-conta.show', $plataforma_id);
        }
    }

    public function ativar($plataforma_id, $id)
    {
        try {
            $plataformaConta = PlataformaConta::where('id', $id)->first();
            $plataformaConta->status = 1;
            $plataformaConta->update();

            flash()->success('Conta de Plataforma ativada com sucesso!');

            return redirect()->route('plataforma-conta.show', $plataforma_id);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao ativar a conta de plataforma.');

            return redirect()->route('plataforma-conta.show', $plataforma_id);
        }
    }
}
