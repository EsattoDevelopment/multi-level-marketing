<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use App\Models\Modal;
use App\Http\Requests;

class ModalController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master|admin', [
            'except' => [
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
        return view('default.modal.index', [
            'title'                 => 'Lista de Modais',
            'dados'                 => Modal::all(),
            'dados_desativados'     => Modal::onlyTrashed()->get()->sortBy('created_at'), //Desativados
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('default.modal.create', [
            'title' => 'Cadastro de Modal',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\ModalRequest $request)
    {
        try {
            if ($request->hasFile('arquivo')) {
                $nomeArquivo = str_slug($request->get('title'), '_').'.'.strtolower($request->file('arquivo')->getClientOriginalExtension());
                $request->merge(['nomeArquivo' => $nomeArquivo]);
            }

            $modal = Modal::create($request->all());

            if ($request->hasFile('arquivo') && ! $request->file('arquivo')->move(storage_path('/app/modal/'), $nomeArquivo)) {
                flash()->error('Desculpe, erro ao salvar o arquivo. Tente novamente, se o erro persistir contate o Administrador!');

                return redirect()->route('modal.edit', $modal->id);
            }

            flash()->success('Modal <strong>'.$request->name.'</strong> adicionado com sucesso!');

            return redirect()->route('modal.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao salvar Modal');

            return redirect()->route('modal.index');
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
            return view('default.modal.edit', [
                'dados' => Modal::findOrFail($id),
                'title' => 'Edição de Modal ',
            ]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, ocorreu um erro ao buscar o Modal!');

            return redirect()->route('modal.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\ModalRequest $request, $id)
    {
        try {
            $modal = Modal::findOrFail($id);

            if ($request->hasFile('arquivo')) {
                $nomeArquivo = str_slug($request->get('title'), '_').'.'.strtolower($request->file('arquivo')->getClientOriginalExtension());

                $request->merge(['nomeArquivo' => $nomeArquivo]);
            }

            if ($request->hasFile('arquivo') && ! $request->file('arquivo')->move(storage_path('/app/modal/'), $nomeArquivo)) {
                flash()->error('Desculpe, erro ao salvar o arquivo. Tente novamente, se o erro persistir contate o Administrador!');

                return redirect()->route('modal.edit', $modal->id);
            }

            $modal->update($request->all());

            if (getenv('APP_ENV') != 'testing') {
                $modal->save();
            }

            flash()->success('Modal '.$request->get('name').' editado com sucesso!');

            return redirect()->route('modal.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao editar o Modal.');

            return redirect()->route('modal.index');
        }
    }

    /**
     * Remove the specified resource from storage.(with soft deletes).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            Modal::destroy($id);

            flash()->warning(sprintf('Modal desativado com sucesso. Caso queira reativar o Modal <a href="%s">clique aqui</a>.', route('modal.recovery', $id)));

            return redirect()->route('modal.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao desativar o Modal.');

            return redirect()->route('modal.index');
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function recovery($id)
    {
        try {
            Modal::onlyTrashed()->findOrFail($id)->restore();

            flash()->success('Modal ativado com sucesso!');

            return redirect()->route('modal.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, ocorreu um erro ao ativar o Modal.');

            return redirect()->route('modal.index');
        }
    }
}
