<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
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
        return view('default.roles.index', [
                'title' => 'Lista de Regras ',
                'dados'  => Role::all(),
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('default.roles.create', [
                'title' => 'Cadastro de Regras ',
                'permissions' => Permission::all(),
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
            $role = Role::create($request->all());

            if ($request->has('permissions')) {
                $role->attachPermissions($request->get('permissions'));
            }

            flash()->success('Regra <strong>'.$request->name.'</strong> adicionado com sucesso!');

            return redirect()->route('role.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao salvar Regra');

            return redirect()->route('role.index');
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
            return view('default.roles.edit', [
                    'dados' => Role::findOrFail($id),
                    'title' => 'Edição de Regra ',
                    'permissions' => Permission::all(),
                ]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, ocorreu um erro ao buscar a Regra!');

            return redirect()->route('role.index');
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
            $role = Role::findOrFail($id);

            $role->update($request->all());

            $role->savePermissions($request->get('permissions'));

            $role->save();

            flash()->success('Regra  '.$request->get('name').' editado com sucesso!');

            return redirect()->route('role.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao editar a Regra.');

            return redirect()->route('role.index');
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
                $role = Role::findOrFail($id);

                $role->forceDelete();

                flash()->success('Regra deletada da base de dados com sucesso!');

                return redirect()->route('role.index');
            } catch (ModelNotFoundException $e) {
                flash()->error('Erro ao deletar a Regra da base de dados!');

                return redirect()->route('role.index');
            }
        } else {
            flash()->error('Você não tem privilégios suficientes para esta operação!');

            return redirect()->route('role.index');
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
            Role::destroy($id);

            flash()->warning(sprintf('Regra desativada com sucesso. Caso queira reativar a Regra <a href="%s">clique aqui</a>.', route('role.recovery', $id)));

            return redirect()->route('role.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao desativar a Regra.');

            return redirect()->route('role.index');
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function recovery($id)
    {
        try {
            Role::onlyTrashed()->findOrFail($id)->restore();

            flash()->success('Regra ativada com sucesso!');

            return redirect()->route('role.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, ocorreu um erro ao ativar a Regra.');

            return redirect()->route('role.index');
        }
    }
}
