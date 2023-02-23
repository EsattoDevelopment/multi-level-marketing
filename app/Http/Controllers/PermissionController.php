<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PermissionRequest;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('default.permission.index', [
            'title' => 'Lista de Permissões ',
            'dados'  => Permission::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('default.permission.create', [
            'title' => 'Cadastro de Permissões ',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PermissionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionRequest $request)
    {
        try {
            Permission::create($request->all());

            flash()->success('Permissão <strong>'.$request->name.'</strong> adicionado com sucesso!');

            return redirect()->route('permission.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao salvar Permissão');

            return redirect()->route('permission.index');
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
            return view('default.permission.edit', [
                'dados' => Permission::findOrFail($id),
                'title' => 'Edição de Permissão ',
            ]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, ocorreu um erro ao buscar a Permissão!');

            return redirect()->route('permission.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PermissionRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionRequest $request, $id)
    {
        try {
            $permission = Permission::findOrFail($id);

            $permission->update($request->all());

            $permission->save();

            flash()->success('Permissão  '.$request->get('name').' editado com sucesso!');

            return redirect()->route('permission.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao editar a Permissão.');

            return redirect()->route('permission.index');
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
        if (Auth::user()->can('master')) {
            try {
                $permission = Permission::findOrFail($id);

                $permission->forceDelete();

                flash()->success('Permissão deletada da base de dados com sucesso!');

                return redirect()->route('permission.index');
            } catch (ModelNotFoundException $e) {
                flash()->error('Erro ao deletar a permissão da base de dados!');

                return redirect()->route('permission.index');
            }
        } else {
            flash()->error('Você não tem privilégios suficientes para esta operação!');

            return redirect()->route('permission.index');
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
            Permission::destroy($id);

            flash()->warning(sprintf('Permissão desativada com sucesso. Caso queira reativar a Permissão <a href="%s">clique aqui</a>.', route('permission.recovery', $id)));

            return redirect()->route('permission.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao desativar a Permissão.');

            return redirect()->route('permission.index');
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function recovery($id)
    {
        try {
            Permission::onlyTrashed()->findOrFail($id)->restore();

            flash()->success('Permissão ativada com sucesso!');

            return redirect()->route('permission.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, ocorreu um erro ao ativar a Permissão.');

            return redirect()->route('permission.index');
        }
    }
}
