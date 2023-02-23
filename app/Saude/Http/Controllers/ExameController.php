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
use App\Saude\Http\Requests\ExamesRequest;
use App\Saude\Repositories\UserRepository;
use App\Saude\Repositories\ExameRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ExameController extends BaseController
{
    private $exameRepository;

    public function __construct()
    {
        $this->exameRepository = new ExameRepository();
        $this->userRepository = new UserRepository();

        //$this->middleware('manipularOutro', ['only' => ['impressao','impressaoConsultor']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('exames.index', [
            'title' => 'Lista de Exames',
        ]);
    }

    public function getFromUser(Request $request)
    {
        $user = $this->userRepository->getUsers($request->get('user'))->first();

        if ($user->empresa_id != null) {
            $user->load('empresa');
            $empresa = $user->getRelation('empresa');
            $dados = $empresa->contratoVigenteOnly();
        } else {
            $dados = $user->contratoVigenteOnly();
        }

        ///dd($dados);

        if ($dados) {
            $dados = $dados->item->exames()->select(['id', 'nome as text'])->get();
        } else {
            $dados = false;
        }

        //dd($user->first()->contratos->first()->item->exames()->select(['id', 'nome'])->get());
        //terminar
        return \Response::json($dados);
    }

    public function getAll()
    {
        $datatables = Datatables::of($this->exameRepository->fillDatatables(['id', 'nome', 'codigo']));

        $datatables->addColumn('action', function ($exame) {
            return '<div class="btn-group" role="group" aria-label="Botões de Ação">
                            <a title="Editar" class="btn btn-default btn-sm" href="'.route('saude.exames.edit', $exame->id).'">
                                <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"></span> Editar
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
        return $this->view('exames.create', [
            'title' => 'Cadastro de Exames',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ExamesRequest $request
     * @return void
     */
    public function store(ExamesRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->exameRepository->create($request->all());

            DB::commit();

            flash()->success('Registro inserido com sucesso!');

            return redirect()->route('saude.exames.index');
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
        return $this->view('exames.edit', [
            'dados' => $this->exameRepository->getExame($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ExamesRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExamesRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $this->exameRepository->update($request->all(), $id);

            DB::commit();

            flash()->success('Registro atualizado com sucesso!');

            return redirect()->route('saude.exames.index');
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
                $this->exameRepository->destroy($id, true);

                flash()->success('Registro deletado da base de dados com sucesso!');

                return redirect()->route('saude.exames.index');
            } catch (ModelNotFoundException $e) {
                flash()->error('Erro ao deletar o registro da base de dados!');

                return redirect()->route('saude.exames.index');
            }
        } else {
            flash()->error('Você não tem privilégios suficientes para esta operação!');

            return redirect()->route('saude.exames.index');
        }
    }

    public function delete($id)
    {
        try {
            $this->exameRepository->destroy($id);

            flash()->success('Registro desativado com sucesso!');

            return redirect()->route('saude.exames.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Erro ao desativar o registro da base de dados!');

            return redirect()->route('saude.exames.index');
        }
    }

    public function recovery($id)
    {
        try {
            $this->exameRepository->recovery($id);

            flash()->success('Registro ativado com sucesso!');

            return redirect()->route('saude.exames.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Erro ao ativar o registro da base de dados!');

            return redirect()->route('saude.exames.index');
        }
    }
}
