<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;

class UserController extends Controller
{
    private function getUsersJson($empresa = false, $disabled = false, $inativo = false, $inadimplente = false, $contratoFinalizado = false, $consultor = false, $clinica = false, $aprovacaoDoc = false)
    {
        $users = DB::table('users as u')
                ->leftjoin('dependentes as dp', 'dp.titular_id', '=', 'u.id')
                ->leftjoin('titulos as t', 't.id', '=', 'u.titulo_id')
                ->WhereRaw('(select count(*) from role_user ru where ru.user_id = u.id and ru.role_id in (1, 2)) = 0');

        if (! Auth::user()->can(['master'])) {
            $users->whereNotIn('u.id', [1, 2]);
        }

        if ($empresa) {
            $users->where('u.empresa_id', $empresa)
                    ->select([
                        'u.id',
                        'u.name',
                        'u.username',
                        DB::raw('count(dp.id) as dep'),
                    ])->groupBy('u.id');
        } else {
            $users->leftjoin('users as i', 'i.id', '=', 'u.indicador_id')
                    ->leftjoin('users as func', 'func.empresa_id', '=', 'u.id')
                    ->select([
                        'u.id',
                        'u.name',
                        'u.empresa',
                        'u.username',
                        'u.tipo',
                        'u.email',
                        't.name as titulo',
                        'u.cnpj',
                        'u.telefone',
                        'u.celular',
                        'u.cpf',
                        'i.name as indicador',
                        'u.conta',
                        DB::raw('DATE_FORMAT(u.created_at, "%d/%m/%Y") as dt_cadastro'),
                        DB::raw('count(dp.id) as dep'),
                        DB::raw('count(func.id) as funcionarios'),
                    ])->groupBy('u.id');
        }

        if ($consultor) {
            $users->where('t.habilita_rede', true);
        }

        if ($disabled) {
            $users->whereNotNull('u.deleted_at');
        }

        if ($inativo) {
            $users->where('u.status', 0);
        }

        if ($clinica) {
            $users->where('u.tipo', 3);
        }

        if ($aprovacaoDoc) {
            $users->addSelect(['u.cpf', 'u.image_cpf', 'u.status_cpf'])->where('u.status_cpf', '<>', 'validado')->orderBy('u.status_cpf', 'desc');
        }

        if ($inadimplente) {
            $users->where('u.status', 2);
        }

        if ($contratoFinalizado) {
            $users->where('u.status', 3);
        }

        $datatables = Datatables::of($users)->orderColumn('username', 'username $1');

        if ($empresa) {
            $datatables->addColumn('action', function ($users) {
                if (Auth::user()->can(['master', 'admin'])) {
                    $retorno = '
                        <div class="btn-group" role="group" aria-label="Botões de Ação">
                                <a title="Dependentes" class="btn btn-default btn-sm" href="'.route('saude.dependentes.index', $users->id).'">
                                    <span class="fa fa-users text-yellow" aria-hidden="true"> </span> Dependentes <span class="badge">'.$users->dep.'</span>
                                </a>
                        <a title="Editar" class="btn btn-default btn-sm" href="'.route('user.edit', $users->id).'">
                                    <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"></span> Editar
                                </a>
                                </div>';
                } else {
                    $retorno = '
                        <div class="btn-group" role="group" aria-label="Botões de Ação">
                                <a title="Dependentes" class="btn btn-default btn-sm" href="javascript:;">
                                    <span class="fa fa-users text-yellow" aria-hidden="true"> </span> Dependentes <span class="badge">'.$users->dep.'</span>
                                </a>
                        <a title="Editar" class="btn btn-default btn-sm" href="'.route('empresa.user.edit', $users->id).'">
                                    <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"></span> Visualizar
                                </a>
                                </div>';
                }

                return $retorno;
            });
        } elseif ($disabled) {
            $datatables->addColumn('action', function ($users) {
                return '<a title="Ativar" class="btn btn-default btn-sm" href="'.route('user.recovery', $users->id).'">
                            <span class="glyphicon glyphicon-check text-success" aria-hidden="true"> </span> Ativar
                        </a>
                    </div>';
            })->editColumn('tipo', function ($user) {
                $estado = '';
                switch ($user->tipo) {
                    case 1:
                        $estado = 'Comum';
                        break;
                    case 2:
                        $estado = 'Empresa';
                        break;
                    case 3:
                        $estado = 'Clinica';
                        break;
                }

                return $estado;
            })->editColumn('name', function ($user) {
                $tipo = strlen($user->cpf);

                $name = $user->name;

                if ($tipo == 18) {
                    $name = $user->empresa;
                }

                return $name;
            });
        } elseif ($aprovacaoDoc) {
            $datatables->addColumn('status_cpf', function ($users) {
                return $users->status_cpf == 'em_analise' ? '<span class="label label-warning">EM ANALISE</span>' : ($users->status_cpf == 'recusado' ? '<span class="label label-danger">RECUSADO</span>' : '');
            });

            $datatables->addColumn('action', function ($users) {
                $retorno = '
                    <div class="btn-group" role="group" aria-label="Botões de Ação">
                        <a title="Ver Documento" class="btn btn-info btn-sm" href="javascript:;" data-toggle="modal" data-target="#modal-documentacao-'.$users->id.'">
                            <span class="glyphicon glyphicon-eye-open text-white" aria-hidden="true"></span> Ver documento
                        </a>
                        <a title="Aprovar" class="btn btn-default btn-sm aprovaDoc" href="javascript:;" data-id="'.$users->id.'">
                            <span class="glyphicon glyphicon-ok text-white" aria-hidden="true"></span> Aprovar
                        </a>
                        <a title="Recusar" class="btn btn-default btn-sm recusaDoc" href="javascript:;" data-id="'.$users->id.'">
                            <span class="glyphicon glyphicon-remove text-white" aria-hidden="true"></span> Recusar
                        </a>
                    </div>
                    <div class="modal fade" id="modal-documentacao-'.$users->id.'" style="display: none;">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Documentação de <b>'.$users->name.'</b></h4>
                          </div>
                          <div class="modal-body">
                            <img class="img-responsive" src="'.route('images.doc', $users->image_cpf).'" alt="Documentação de '.$users->name.'">
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>
                          </div>
                        </div>
                      </div>
                    </div>
                ';

                return $retorno;
            });
        } elseif (! $inativo) {
            $datatables->addColumn('action', function ($users) use ($consultor) {
                if (Auth::user()->can(['master'])) {
                    $retorno = '<div class="btn-group" role="group" aria-label="Botões de Ação">
                        <a title="Logar" class="btn btn-warning btn-sm" href="'.route('user.logar.como', $users->id).'">
                                    <span class="glyphicon glyphicon-user text-white" aria-hidden="true"></span> Logar
                                </a>
                        <a title="Editar" class="btn btn-default btn-sm" href="'.route('user.edit', $users->id).'">
                            <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"> </span> Editar
                        </a>';

                    //TODO saude
                    /*if($users->tipo == 3){
                        $retorno .= '<a title="Procedimentos" class="btn btn-default btn-sm" href="' . route("saude.procedimentos_clinica.index", $users->id) . '">
                            <span class="fa fa-bars text-default" aria-hidden="true"> </span> Procedimentos
                        </a>';
                        //faixas cep
                        $retorno .= '<a title="Faixas CEP" class="btn btn-default btn-sm" href="' . route("user.{user}.faixas-cep.index", $users->id) . '">
                            <span class="fa fa-bars text-default" aria-hidden="true"> </span> Faixas CEP
                        </a>';
                    }

                    if ($users->funcionarios == 0 && $users->tipo != 3) {
                        $retorno .= '<a title="Dependentes" class="btn btn-default btn-sm" href="' . route("saude.dependentes.index", $users->id) . '">
                            <span class="fa fa-users text-yellow" aria-hidden="true"> </span> Dependentes <span class="badge">' . $users->dep . '</span>
                        </a>';
                    }

                    if ($users->funcionarios > 0) {
                        $retorno .= '<a title="Colaboradores" class="btn btn-default btn-sm" href="' . route("user.colaboradores.id", $users->id) . '">
                            <span class="fa fa-users text-blue" aria-hidden="true"> </span> Colaboradores <span class="badge">' . $users->funcionarios . '</span>
                        </a>';
                    }

                    if($consultor) {
                        $retorno .= '<a title="Impressão contrato"  target="_blank" class="btn btn-default btn-sm  bg-blue" href="' . route("contrato.impressao.consultor", $users->id) . '">
                            <span class="fa fa-print" aria-hidden="true"> </span> Imprimir contrato consultor
                        </a>';
                    }*/

                    $retorno .= '<a title="Desativar" class="btn btn-default btn-sm" href="'.route('user.delete', $users->id).'">
                            <span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"> </span> Desativar
                        </a>
                    </div>';
                } else {
                    $retorno = '<div class="btn-group" role="group" aria-label="Botões de Ação">
                        <a title="Editar" class="btn btn-default btn-sm" href="'.route('user.edit', $users->id).'">
                            <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"> </span> Editar
                        </a>';

                    //TODO saude
                    /*if (!$consultor) {
                        $retorno .= '<a title="Dependentes" class="btn btn-default btn-sm" href="' . route("saude.dependentes.index", $users->id) . '">
                            <span class="fa fa-users text-yellow" aria-hidden="true"> </span> Dependentes <span class="badge">' . $users->dep . '</span>
                        </a>';
                    } else {
                        $retorno .= '<a title="Colaboradores" target="_blank" class="btn btn-default btn-sm bg-blue" href="' . route("contrato.impressao.consultor", $users->id) . '">
                            <span class="fa fa-print" aria-hidden="true"> </span> Imprimir contrato consultor
                        </a>';
                    }

                    if ($users->funcionarios > 0 && !$consultor) {
                        $retorno .= '<a title="Colaboradores" class="btn btn-default btn-sm" href="' . route("user.colaboradores.id", $users->id) . '">
                            <span class="fa fa-users text-blue" aria-hidden="true"> </span> Colaboradores <span class="badge">' . $users->funcionarios . '</span>
                        </a>';
                    }*/

                    /*      $retorno .= '<a title="Desativar" class="btn btn-default btn-sm" href="' . route("user.delete", $users->id) . '">
                              <span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"> </span> Desativar
                          </a>
                      </div>';*/
                    $retorno .= '</div>';
                }

                return $retorno;
            })->editColumn('tipo', function ($user) {
                $estado = '';
                switch ($user->tipo) {
                        case 1:
                            $estado = 'Comum';
                            break;
                        case 2:
                            $estado = 'Empresa';
                            break;
                        case 3:
                            $estado = 'Clinica';
                            break;
                    }

                return $estado;
            })->editColumn('name', function ($user) {
                $tipo = strlen($user->cpf);

                $name = $user->name;

                if ($tipo == 18) {
                    $name = $user->empresa;
                }

                return $name;
            })->editColumn('telefone', function ($user) {
                $telefone = $user->celular;

                if ($user->telefone) {
                    $telefone .= ' <br> '.$user->telefone;
                }

                return $telefone;
            });
        } else {
            $datatables->editColumn('tipo', function ($user) {
                $estado = '';
                switch ($user->tipo) {
                        case 1:
                            $estado = 'Comum';
                            break;
                        case 2:
                            $estado = 'Empresa';
                            break;
                        case 3:
                            $estado = 'Clinica';
                            break;
                    }

                return $estado;
            })->editColumn('name', function ($user) {
                $tipo = strlen($user->cpf);

                $name = $user->name;

                if ($tipo == 18) {
                    $name = $user->empresa;
                }

                return $name;
            })->editColumn('telefone', function ($user) {
                $telefone = $user->celular;

                if ($user->telefone) {
                    $telefone .= ' <br> '.$user->telefone;
                }

                return $telefone;
            });
        }

        return $datatables->make(true);
    }

    public function getUsers()
    {
        return $this->getUsersJson();
    }

    public function getUsersDisabled()
    {
        return $this->getUsersJson(false, true);
    }

    public function getUsersConsultor()
    {
        return $this->getUsersJson(false, false, false, false, false, true);
    }

    public function getUsersClinica()
    {
        return $this->getUsersJson(false, false, false, false, false, false, true);
    }

    public function getUsersAprovacaoDoc()
    {
        return $this->getUsersJson(false, false, false, false, false, false, false, true);
    }

    public function getUsersInativo()
    {
        return $this->getUsersJson(false, false, true);
    }

    public function getUsersInadimplente()
    {
        return $this->getUsersJson(false, false, false, true);
    }

    public function getUsersFinalizado()
    {
        return $this->getUsersJson(false, false, false, false, true);
    }

    public function empresaUsers()
    {
        return $this->getUsersJson(Auth::user()->id);
    }

    public function apiBusca(Request $request)
    {
        return \Response::json(
                User::where('name', 'like', "%{$request->get('search')}%")
                    ->orWhere('username', 'like', "%{$request->get('search')}%")
                    ->orWhere('id', "{$request->get('search')}")
                    ->orWhere('codigo', "{$request->get('search')}")
                    ->select('id', 'name', 'empresa', 'cpf')
                    ->take(15)->get(),
                200);
    }

    public function apiBuscaGuia(Request $request)
    {
        return \Response::json(
                User::where('tipo', 1)
                    ->whereNotIn('id', [1, 2])
                    ->where(function ($query) use ($request) {
                        $query->where('name', 'like', "%{$request->get('search')}%")
                            ->orWhere('username', 'like', "%{$request->get('search')}%")
                            ->orWhere('id', "{$request->get('search')}")
                            ->orWhere('codigo', "{$request->get('search')}");
                    })
                    ->select(['id', 'name', 'codigo', 'status', 'empresa', 'cpf'])
                    ->take(15)->get(),
                200);
    }

    public function apiBuscaClinica()
    {
        return \Response::json(
                User::where('tipo', 3)->select('id', 'name')->get(),/*where('name', 'like', "%{$request->get('search')}%")
                ->where('tipo', 3)
                ->orWhere('username', 'like', "%{$request->get('search')}%")
                ->orWhere('id', "{$request->get('search')}")
                ->orWhere('codigo', "{$request->get('search')}")
                ->select('id', 'name')
                ->take(15)->get(),*/
                200);
    }

    public function apiBuscaMedicos(Request $request)
    {
        $user = User::with('medicos')->find($request->get('clinica'));

        return \Response::json(
                $user->getRelation('medicos'),
                200);
    }

    public function apiBuscaEmpresa(Request $request)
    {
        return \Response::json(
                User::whereHas('roles', function ($query) {
                    $query->where('name', 'user-empresa');
                })
                    ->where(function ($query) use ($request) {
                        $query->where('name', 'like', "%{$request->get('search')}%")
                            ->orWhere('username', 'like', "%{$request->get('search')}%")
                            ->orWhere('id', "{$request->get('search')}")
                            ->orWhere('codigo', "{$request->get('search')}");
                    })->select('id', 'name', 'empresa', 'cpf')
                    ->take(10)->get(),
                200);
    }

    public function apiBuscaConsultor(Request $request)
    {
        return \Response::json(
                User::where('titulo_id', '<', 5)
                    ->where(function ($query) use ($request) {
                        $query->where('name', 'like', "%{$request->get('search')}%")
                            ->orWhere('username', 'like', "%{$request->get('search')}%")
                            ->orWhere('id', "{$request->get('search')}");
                    })->select('id', 'name', 'empresa', 'cpf')
                    ->take(10)->get(),
                200);
    }

    public function indexEmpresaId($id)
    {
        return $this->getUsersJson($id);
    }
}
