<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use App\Saude\Repositories\UserRepository;
use App\Saude\Http\Requests\EspecialidadeRequest;
use App\Saude\Repositories\EspecialidadeRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EspecialidadeController extends BaseController
{
    private $especialidadeRepository;

    public function __construct()
    {
        $this->especialidadeRepository = new EspecialidadeRepository();
        $this->userRepository = new UserRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('especialidade.index', [
            'title' => 'Lista de Especialidade',
        ]);
    }

    public function getFromUser(Request $request)
    {
        $user = $this->userRepository->getUsers($request->get('user'));

        $dados = $user->first()->contratoVigente();

        if ($dados) {
            $dados = $dados->item->especialidade()->select(['id', 'nome as text'])->get();
        } else {
            $dados = false;
        }

        //dd($user->first()->contratos->first()->item->especialidade()->select(['id', 'nome'])->get());
        //terminar
        return \Response::json($dados);
    }

    public function getAll()
    {
        $datatables = Datatables::of($this->especialidadeRepository->fillDatatables(['id', 'name']));

        $datatables->addColumn('action', function ($especialidade) {
            return '<div class="btn-group" role="group" aria-label="Botões de Ação">
                            <a title="Editar" class="btn btn-default btn-sm" href="'.route('saude.especialidade.edit', $especialidade->id).'">
                                <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"></span> Editar
                            </a>
                            <a title="Desativar" class="btn btn-default btn-sm" href="'.route('saude.especialidade.delete', $especialidade->id).'">
                            <span class="glyphicon glyphicon-remove text-red" aria-hidden="true"> </span> Cancelar
                            </a>
                           </div>';
        });

        return $datatables->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->view('especialidade.create', [
            'title' => 'Cadastro de Especialidade',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EspecialidadeRequest $request
     * @return void
     */
    public function store(EspecialidadeRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->especialidadeRepository->create($request->all());

            DB::commit();

            flash()->success('Registro inserido com sucesso!');

            return redirect()->route('saude.especialidade.index');
        } catch (ModelNotFoundException $e) {
            DB::rollback();

            flash()->error('Desculpe, houve um erro na operação!');

            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return $this->view('especialidade.edit', [
            'dados' => $this->especialidadeRepository->getEspecialidade($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EspecialidadeRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(EspecialidadeRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $this->especialidadeRepository->update($request->all(), $id);

            DB::commit();

            flash()->success('Registro atualizado com sucesso!');

            return redirect()->route('saude.especialidade.index');
        } catch (ModelNotFoundException $e) {
            DB::rollback();

            flash()->error('Desculpe, houve um erro na operação!');

            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->can('master')) {
            try {
                $this->especialidadeRepository->destroy($id, true);

                flash()->success('Registro deletado da base de dados com sucesso!');

                return redirect()->route('saude.especialidade.index');
            } catch (ModelNotFoundException $e) {
                flash()->error('Erro ao deletar o registro da base de dados!');

                return redirect()->route('saude.especialidade.index');
            }
        } else {
            flash()->error('Você não tem privilégios suficientes para esta operação!');

            return redirect()->route('saude.especialidade.index');
        }
    }

    public function delete($id)
    {
        try {
            $this->especialidadeRepository->destroy($id);

            flash()->success('Registro desativado com sucesso!');

            return redirect()->route('saude.especialidade.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Erro ao desativar o registro da base de dados!');

            return redirect()->route('saude.especialidade.index');
        }
    }

    public function recovery($id)
    {
        try {
            $this->especialidadeRepository->recovery($id);

            flash()->success('Registro ativado com sucesso!');

            return redirect()->route('saude.especialidade.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Erro ao ativar o registro da base de dados!');

            return redirect()->route('saude.especialidade.index');
        }
    }
}
