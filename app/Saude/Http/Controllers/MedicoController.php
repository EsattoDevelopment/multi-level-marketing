<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use App\Saude\Repositories\UserRepository;
use App\Saude\Http\Requests\MedicosRequest;
use App\Saude\Repositories\MedicoRepository;
use App\Saude\Repositories\EspecialidadeRepository;

class MedicoController extends BaseController
{
    private $medicoRepository;

    public function __construct()
    {
        $this->medicoRepository = new MedicoRepository();
        $this->userRepository = new UserRepository();
        $this->especialidadeRepository = new EspecialidadeRepository();

        $this->middleware('permission:master|admin', [
            'except' => [
                'index',
                'indexClinica',
                'getAll',
                'fromClinica',
                'getAllDisabled',
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
        return $this->view('medicos.index', [
            'title'  => 'Lista de Medicos',
            'name'   => 'Medicos',
            'getAll' => route('saude.medicos.all'),
            'dados' => $this->medicoRepository->getAll('especialidades'),
        ]);
    }

    public function indexClinica()
    {
        return $this->view('medicos.index-clinica', [
            'title'  => 'Lista de Medicos',
            'name'   => 'Medicos',
            'getAll' => route('saude.medicos.all'),
            'dados' => $this->medicoRepository->fromClinica()->load('especialidades'),
        ]);
    }

    public function desativados()
    {
        return $this->view('medicos.index', [
            'title'  => 'Lista de Medicos desativados',
            'name'   => 'Medicos desativados',
            'getAll' => route('saude.medicos.all.disabled'),
        ]);
    }

    private function all($disabled = false)
    {
        $datatables = Datatables::of($this->medicoRepository->fillDatatables(['id', 'name', 'crm', 'telefone1', 'telefone2'], $disabled));

        $datatables->addColumn('action', function ($medico) use ($disabled) {
            $retorno = '<div class="btn-group" role="group" aria-label="Botões de Ação">';

            if (! $disabled) {
                $retorno .= '<a title="Editar" class="btn btn-default btn-sm" href="'.route('saude.medicos.edit', $medico->id).'">
                            <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"></span> Editar
                        </a>
                        <a title="Desativar" class="btn btn-default btn-sm" href="'.route('saude.medicos.delete', $medico->id).'">
                            <span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"> </span> Desativar
                        </a>';
            } else {
                $retorno .= '<a title="Restaurar" class="btn btn-default btn-sm" href="'.route('saude.medicos.recovery', $medico->id).'">
                            <span class="glyphicon glyphicon-remove text-warning" aria-hidden="true"> </span> Restaurar
                        </a>';
            }

            $retorno .= '</div>';

            return $retorno;
        });

        return $datatables->make(true);
    }

    public function getAll()
    {
        return $this->all();
    }

    public function fromClinica($clinica)
    {
        return $this->medicoRepository->getBy('clinica_id', $clinica, ['id', 'name']);
    }

    public function getAllDisabled()
    {
        return $this->all(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->view('medicos.create', [
            'title' => 'Cadastro de Medicos',
            'users' => $this->userRepository->getUsers(null, 3, ['id', 'name']),
            'especialidades' => $this->especialidadeRepository->getAll(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MedicosRequest $request
     * @return void
     */
    public function store(MedicosRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->medicoRepository->save($request);

            DB::commit();

            flash()->success('Registro inserido com sucesso!');

            return redirect()->route('saude.medicos.index');
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
        return $this->view('medicos.edit', [
            'dados' => $this->medicoRepository->getMedico($id, ['clinicas']),
            'users' => $this->userRepository->getUsers(null, 3, ['id', 'name']),
            'especialidades' => $this->especialidadeRepository->getAll(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MedicosRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(MedicosRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $this->medicoRepository->save($request, $id);

            DB::commit();

            flash()->success('Registro atualizado com sucesso!');

            return redirect()->route('saude.medicos.index');
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
                $this->medicoRepository->destroy($id, true);

                flash()->success('Registro deletado da base de dados com sucesso!');

                return redirect()->route('saude.medicos.index');
            } catch (ModelNotFoundException $e) {
                flash()->error('Erro ao deletar o registro da base de dados!');

                return redirect()->route('saude.medicos.index');
            }
        } else {
            flash()->error('Você não tem privilégios suficientes para esta operação!');

            return redirect()->route('saude.medicos.index');
        }
    }

    public function delete($id)
    {
        try {
            $this->medicoRepository->destroy($id);

            flash()->success('Registro desativado com sucesso!');

            return redirect()->route('saude.medicos.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Erro ao desativar o registro da base de dados!');

            return redirect()->route('saude.medicos.index');
        }
    }

    public function recovery($id)
    {
        try {
            $this->medicoRepository->recovery($id);

            flash()->success('Registro ativado com sucesso!');

            return redirect()->route('saude.medicos.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Erro ao ativar o registro da base de dados!');

            return redirect()->route('saude.medicos.index');
        }
    }
}
