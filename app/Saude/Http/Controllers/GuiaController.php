<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Http\Controllers;

use Carbon\Carbon;
use App\Models\Empresa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;
use App\Saude\Http\Requests\GuiasRequest;
use App\Saude\Domains\ProcedimentoClinica;
use App\Saude\Repositories\GuiaRepository;
use App\Saude\Repositories\UserRepository;
use App\Saude\Repositories\ExameRepository;
use App\Saude\Repositories\MedicoRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GuiaController extends BaseController
{
    private $guiaRepository;

    public function __construct()
    {
        $this->guiaRepository = new GuiaRepository();
        $this->examesRepository = new ExameRepository();
        $this->userRepository = new UserRepository();
        $this->medicoRepository = new MedicoRepository();

        $this->middleware('manipularOutro', ['only' => ['imprimir', 'edit', 'update', 'autorizar']]);

        $this->middleware('permission:master|admin', [
            'only' => [
                'desativados',
                'getAllDisabled',
                'destroy',
                'recovery',
            ],
        ]);

        $this->middleware('permission:master|admin|guia-autorizar', [
            'only' => [
                'autorizar',
            ],
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function aguardando()
    {
        return $this->view('guias.index', [
            'title'  => 'Lista de Guias aguardando autorização',
            'name'   => 'Guias aguardando autorização',
            'getAll' => route('saude.guias.json.aguardando'),
        ]);
    }

    public function autorizadas()
    {
        return $this->view('guias.index', [
            'title'  => 'Lista de Guias autorizadas',
            'name'   => 'Guias autorizadas',
            'getAll' => route('saude.guias.json.autorizadas'),
        ]);
    }

    public function desativados()
    {
        return $this->view('guias.index', [
            'title'  => 'Lista de Guias cancelados',
            'name'   => 'Guias cancelados',
            'getAll' => route('saude.guias.all.disabled'),
        ]);
    }

    public function autorizar($id)
    {
        DB::beginTransaction();

        try {
            $guia = $this->guiaRepository->getGuia($id);

            $usuario = $guia->usuario;

            if ($usuario->status == 1) {
                $guia->update([
                        'autorizado'     => 1,
                        'dt_autorizado'  => date('Y-m-d H:i:s'),
                        'autorizado_por' => Auth::user()->id,
                    ]);

                DB::commit();
                flash()->success('Guia autorizada com sucesso!');
            } else {
                flash()->error('A guia não pode ser autorizada devido a pendencias na Empresa de '.env('COMPANY_NAME_SHORT').'!');
            }

            return redirect()->route('saude.guias.autorizadas');
        } catch (ModelNotFoundException $e) {
            DB::rollback();

            flash()->danger('Desculpe, houve ao autorizar guia!');

            return redirect()->back()->withInput();
        }

        return redirect()->route('saude.guias.aguardando');
    }

    private function all($disabled = false, $autorizadas = false, $aguardando = false)
    {
        $clinica = Auth::user()->id;

        if (Auth::user()->can(['master', 'admin', 'guia-visualizar-todas'])) {
            $clinica = false;
        }

        $sqlBuilder = $this->guiaRepository->fillDatatables($clinica, $disabled, $autorizadas, $aguardando);

        //dd($sqlBuilder->ToSql());

        $datatables = Datatables::of($sqlBuilder);

        $datatables->editColumn('tipo_atendimento', function ($guia) {
            return config('constants.tipo_atendimento')[$guia->tipo_atendimento];
        });

        $datatables->addColumn('action', function ($guia) use ($disabled) {
            $retorno = '<div class="btn-group" role="group" aria-label="Botões de Ação">';

            if (! $disabled) {
                if (! $disabled && $guia->autorizado == 0) {
                    $retorno .= '<a title="Editar" class="btn btn-default btn-sm" href="'.route('saude.guias.edit', $guia->id).'">
                            <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"></span> Editar
                        </a>';
                }

                if ($guia->autorizado == 0 && ! Auth::user()->hasRole('user-callcenter')) {
                    $retorno .= '<a title="Autorizar" class="btn btn-default btn-sm" href="'.route('saude.guias.autorizar', $guia->id).'">
                            <span class="glyphicon glyphicon-check text-success" aria-hidden="true"> </span> Autorizar
                        </a>';
                }

                $dt_autorizado = Carbon::parse($guia->dt_autorizado);

                /*             if($guia->id == 5242){
                                 dd($guia);
                             }*/

                if ($guia->autorizado == 1 && $guia->tipo_atendimento == 2 && $guia->referencia == 0 && $dt_autorizado->diffInDays(Carbon::now()) < 22) {
                    $retorno .= '<a title="Retorno" class="btn btn-default btn-sm" href="'.route('saude.guias.retorno', $guia->id).'">
                            <span class="fa fa-share-square-o text-success" aria-hidden="true"> </span> Solicitar Retorno
                        </a>';
                } elseif (Auth::user()->can(['master', 'admin']) && $dt_autorizado->diffInDays(Carbon::now()) > 21) {
                    $retorno .= '<a title="Retorno" class="btn btn-default btn-sm" href="'.route('saude.guias.retorno', $guia->id).'">
                            <span class="fa fa-share-square-o text-warning" aria-hidden="true"> </span> Retorno retroativo
                        </a>';
                }

                if ($guia->autorizado == 1) {
                    $retorno .= '<a title="Imprimir" target="_blank" class="btn btn-default btn-sm" href="'.route('saude.guias.imprimir', $guia->id).'">
                            <span class="glyphicon glyphicon-print text-warning" aria-hidden="true"> </span> Imprimir
                        </a>';
                }
            } else {
                $retorno .= '<a title="Restaurar" class="btn btn-default btn-sm" href="'.route('saude.guias.recovery', $guia->id).'">
                            <span class="glyphicon glyphicon-remove text-warning" aria-hidden="true"> </span> Restaurar
                        </a>';
            }

            if (Auth::user()->can(['master', 'admin', 'guia-cancelar']) && ! $disabled && $guia->autorizado == 0) {
                $retorno .= '<a title="Desativar" class="btn btn-default btn-sm" href="'.route('saude.guias.delete', $guia->id).'">
                            <span class="glyphicon glyphicon-remove text-red" aria-hidden="true"> </span> Cancelar
                        </a>';
            } elseif (Auth::user()->can(['master']) && ! isset($guia->deleted_at)) {
                $retorno .= '<a title="Desativar" class="btn btn-default btn-sm" href="'.route('saude.guias.delete', $guia->id).'">
                            <span class="glyphicon glyphicon-remove text-red" aria-hidden="true"> </span> Cancelar
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

    public function getAllDisabled()
    {
        return $this->all(true);
    }

    public function getAutorizadas()
    {
        return $this->all(false, true);
    }

    public function getAguardando()
    {
        return $this->all(false, false, true);
    }

    public function imprimir($id)
    {
        try {
            $clinica = false;

            if (! Auth::user()->can(['master', 'admin'])) {
                $clinica = Auth::user()->id;
            }

            $guia = $this->guiaRepository->getGuia($id, $clinica);
            $empresa = Empresa::find(1);

            if ($guia->autorizado == 1) {
                return $this->view('guias.impressao', compact('guia', 'empresa'));
            } else {
                flash()->error('Guia não esta autorizada!');

                return redirect()->route('saude.guias.aguardando');
            }
        } catch (\Exception $e) {
            flash()->error('Erro interno!');

            return redirect()->route('saude.guias.aguardando');
        }
    }

    public function retorno($guia)
    {
        $guia = $this->guiaRepository->getGuia($guia);

        if ($guia->dt_autorizado->diffInDays(Carbon::now()) <= 21 || Auth::user()->can(['master', 'admin'])) {
            return $this->view('guias.retorno', compact('guia'));
        } else {
            flash()->danger('Você não tem permissão para gerar guia de retorno em consulta com mais de 21 dias. Contate o administrador!');

            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->view('guias.create', [
            'title'   => 'Cadastro de Guias',
            'medicos' => Auth::user()->medicos,
            'clinicas' => $this->userRepository->getUsers(null, 3, ['id', 'name']),
            'procedimentos' => Auth::user()->procedimentos()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GuiasRequest $request
     * @return void
     */
    public function store(GuiasRequest $request)
    {
//        dd($request->all());
        DB::beginTransaction();
        try {
            $dados = $request->all();

            $user = $this->userRepository
                ->getUsers($request->get('user_id'), null, ['id', 'name', 'empresa_id'])->first();

            if ($user->empresa_id != null) {
                $empresa = $user->empresa;
                $idUserItem = $empresa->id;
            } else {
                $idUserItem = $request->get('user_id');
            }

            $item = $this->userRepository
                ->getUsers($idUserItem, null, ['id', 'name'])->first()
                ->contratoVigenteOnly()
                ->item;
            $dados['plano_id'] = $item->id;

            if (in_array($dados['tipo_atendimento'], [2, 4])) {
                $dados['valor_consulta'] = $item->valor_consulta;
            } elseif ($dados['tipo_atendimento'] == 5) {
                $dados['valor_fisioterapia'] = $item->valor_fisioterapia;
            }

            $this->guiaRepository->save($dados);

            DB::commit();

            flash()->success('Registro inserido com sucesso!');

            return redirect()->route('saude.guias.aguardando');
        } catch (ModelNotFoundException $e) {
            DB::rollback();

            flash()->error('Desculpe, houve um erro na operação!');

            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $guia = $this->guiaRepository->getGuia($id);

        return $this->view('guias.edit', [
            'dados' => $guia,
            'users' => $this->userRepository->getUsers($guia->user_id, false, ['id', 'name'], 'dependentes'),
            'clinicas' => $this->userRepository->getUsers(null, 3, ['id', 'name']),
            'procedimentos_clinicas' => ProcedimentoClinica::whereUserId($guia->clinica_id)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param GuiasRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(GuiasRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $dados = $request->all();

            $item = $this->userRepository
                ->getUsers($request->get('user_id'), null, ['id', 'name'])->first()
                ->contratoVigente()
                ->item;

            $dados['plano_id'] = $item->id;

            if ($dados['tipo_atendimento'] == 2) {
                $dados['valor_consulta'] = $item->valor_consulta;
            }

            if (! isset($dados['dependente_id'])) {
                $dados['dependente_id'] = null;
            }

            if (! isset($dados['medico_id'])) {
                $dados['medico_id'] = null;
            }

            $this->guiaRepository->save($dados, $id);

            DB::commit();

            flash()->success('Registro atualizado com sucesso!');

            return redirect()->route('saude.guias.aguardando');
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
                $this->guiaRepository->destroy($id, true);

                flash()->success('Registro deletado da base de dados com sucesso!');

                return redirect()->route('saude.guias.aguardando');
            } catch (ModelNotFoundException $e) {
                flash()->error('Erro ao deletar o registro da base de dados!');

                return redirect()->route('saude.guias.aguardando');
            }
        } else {
            flash()->error('Você não tem privilégios suficientes para esta operação!');

            return redirect()->route('saude.guias.aguardando');
        }
    }

    public function delete($id)
    {
        try {
            $guia = $this->guiaRepository->getGuia($id);

            if (Auth::user()->can(['master']) || $guia->autorizado == 0) {
                $this->guiaRepository->destroy($id);
                flash()->warning('Registro desativado com sucesso! Caso queira reativar a Regra <a href="'.route('saude.guias.recovery', $id).'">clique aqui</a>');
            } else {
                flash()->error('Registro não pode ser cancelado');
            }

            return redirect()->route('saude.guias.aguardando');
        } catch (ModelNotFoundException $e) {
            flash()->error('Erro ao desativar o registro da base de dados!');

            return redirect()->route('saude.guias.aguardando');
        }
    }

    public function recovery($id)
    {
        try {
            $this->guiaRepository->recovery($id);

            flash()->success('Registro ativado com sucesso!');

            return redirect()->route('saude.guias.aguardando');
        } catch (ModelNotFoundException $e) {
            flash()->error('Erro ao ativar o registro da base de dados!');

            return redirect()->route('saude.guias.aguardando');
        }
    }
}
