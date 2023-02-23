<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use App\Models\DownloadTipo;
use Illuminate\Http\Request;

class DownloadTipoController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master|admin', [
            'except' => [
                'download',
                'show',
            ],
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('default.downloads_tipo.index', [
            'title'                 => 'Lista de Downloads ',
            'dados'                 => DownloadTipo::all(),
            'dados_desativados'     => DownloadTipo::onlyTrashed()->get()->sortBy('created_at'), //Desativados
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('default.downloads_tipo.create', [
            'title' => 'Cadastro de Downloads ',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DownloadTipo::create($request->all());

            $request['habilita_rede'] = isset($request['habilita_rede']) ? 1 : 0;

            flash()->success('Tipo Download <strong>'.$request->name.'</strong> adicionado com sucesso!');

            return redirect()->route('download-tipo.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao salvar o registro');

            return redirect()->route('download-tipo.index');
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
        //
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
            return view('default.downloads_tipo.edit', [
                'dados' => DownloadTipo::findOrFail($id),
                'title' => 'Edição de Tipo Download ',
            ]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, ocorreu um erro ao buscar o registro!');

            return redirect()->route('download-tipo.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $download = DownloadTipo::findOrFail($id);

            $request['habilita_rede'] = isset($request['habilita_rede']) ? 1 : 0;

            $download->update($request->all());

            flash()->success('Registro  '.$request->get('name').' editado com sucesso!');

            return redirect()->route('download-tipo.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao editar o registro.');

            return redirect()->route('download-tipo.index');
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
        if (\Auth::user()->can('master')) {
            try {
                $download = DownloadTipo::find($id);

                $download->forceDelete();

                flash()->success('Download deletado da base de dados com sucesso!');

                return redirect()->route('download-tipo.index');
            } catch (ModelNotFoundException $e) {
                flash()->error('Erro ao deletar o Download da base de dados!');

                return redirect()->route('download-tipo.index');
            }
        } else {
            flash()->error('Você não tem privilégios suficientes para esta operação!');

            return redirect()->route('download-tipo.index');
        }
    }

    public function delete($id)
    {
        try {
            DownloadTipo::destroy($id);

            flash()->warning(sprintf('Registro desativado com sucesso. Caso queira reativar o registro <a href="%s">clique aqui</a>.', route('download-tipo.recovery', $id)));

            return redirect()->route('download-tipo.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao desativar o Download.');

            return redirect()->route('download-tipo.index');
        }
    }

    public function recovery($id)
    {
        try {
            DownloadTipo::onlyTrashed()->findOrFail($id)->restore();

            flash()->success('Registro ativado com sucesso!');

            return redirect()->route('download-tipo.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, ocorreu um erro ao ativar o registro.');

            return redirect()->route('download-tipo.index');
        }
    }
}
