<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Contrato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\MensalidadeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ContratosController extends Controller
{
    /**
     *     ### Status do contrato
     * - 1 Aguardando liberação
     * - 2 Em aberto
     * - 3 Pausado (verificar motivo)
     * - 4 Cancelado
     * - 5 Finalizado.
     */
    public function __construct()
    {
        $this->middleware('permission:master|admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('default.contratos.index', [
            'title' => 'Contratos aguardando',
            'dados' => Contrato::with('item')
                ->with([
                    'usuario' => function ($query) {
                        $query->withTrashed()->select('id', 'name', 'codigo');
                    },
                ])->whereStatus(1)
                ->select('id', 'user_id', 'item_id', 'dt_inicio', 'dt_fim')
                ->get(),
        ]);
    }

    public function abertos()
    {
        return view('default.contratos.abertos', [
            'title' => 'Contratos em aberto/Liberados',
        ]);
    }

    private function getContratos($status)
    {
        return Contrato::with([
            'item' => function ($query) {
                $query
                    ->withTrashed()
                    ->select([
                        'id',
                        'name',
                        'tipo_pacote',
                    ]);
            },
        ])
            ->with([
                'usuario' => function ($query) {
                    $query->withTrashed()->select([
                        'id',
                        'name',
                        'codigo',
                    ]);
                },
            ])
            ->whereStatus($status);
    }

    public function getAbertos()
    {
        $contratos = $this->getContratos(2);

        return $this->datatables($contratos);
    }

    public function getAtrasados()
    {
        $contratos = $this->getContratos(3);

        return $this->datatables($contratos);
    }

    private function datatables($contratos)
    {
        return Datatables::of($contratos)
            ->addColumn('action', function ($contrato) {
                $retorno = '<div class="btn-group" role="group" aria-label="Botões de Ação">
                        <a title="Editar" class="btn btn-default btn-sm"
                           href="'.route('contratos.edit', $contrato->id).'">
                                        <span class="glyphicon glyphicon-edit text-success"
                                              aria-hidden="true"></span> Abrir
                        </a>';

                /*

                        <a title="Gerar parcelas" target="_blank" class="btn btn-default btn-sm"
                           href="'.route('contratos.mensalidades.gerar', $contrato->id).'">
                                        <span class="fa fa-copy"
                                              aria-hidden="true"></span> Gerar parcelas
                        </a>
                  <a href="'.route('contrato.impressao', $contrato).'" target="_blank"
                           class="btn btn-warning btn-sm"> <i class="fa fa-print"></i> Imprimir contrato '.config('constants')['tipo_pacote'][$contrato->getRelation('item')->tipo_pacote].'
                </a>*/

                if (Auth::user()->can(['master', 'admin'])) {
                    $retorno .= '<a title="Cancelar"
                           data-url1="'.route('contratos.cancelar.dentro-prazo', $contrato->id).'"
                           data-url2="'.route('contratos.cancelar.fora-prazo', $contrato->id).'"
                           class="btn btn-default btn-sm cancelar"
                           href="javascript:;">
                            <span class="glyphicon glyphicon-remove text-danger"
                                  aria-hidden="true"></span> Cancelar
                                    </a>';
                }

                $retorno .= '</div>';

                return $retorno;
            })
            ->addColumn('details_url', function ($contrato) {
                return route('mensalidade.get', $contrato->id);
            })
            ->setRowId(function ($contrato) {
                return 'contrato-'.$contrato->id;
            })
            ->make(true);
    }

    public function atrasados()
    {
        return view('default.contratos.atrasados', [
            'title' => 'Contratos em atraso',
        ]);
    }

    public function finalizando()
    {
        return view('default.contratos.finalizando', [
            'title' => 'Contratos finalizando',
            'dados' => Contrato::with([
                'item' => function ($query) {
                    $query
                        ->withTrashed()
                        ->select([
                            'id',
                            'name',
                            'tipo_pacote',
                        ]);
                },
            ])
                ->with([
                    'usuario' => function ($query) {
                        $query->withTrashed()->select([
                            'id',
                            'name',
                            'codigo',
                        ]);
                    },
                ])
                ->whereStatus(5)->get(),
        ]);
    }

    public function finalizados()
    {
        return view('default.contratos.finalizados', [
            'title' => 'Contratos Finalizados',
            'dados' => Contrato::with([
                'item' => function ($query) {
                    $query
                        ->withTrashed()
                        ->select([
                            'id',
                            'name',
                            'tipo_pacote',
                        ]);
                },
            ])
                ->with([
                    'usuario' => function ($query) {
                        $query->withTrashed()->select([
                            'id',
                            'name',
                            'codigo',
                        ]);
                    },
                ])
                ->whereStatus(6)->get(),
        ]);
    }

    public function cancelados()
    {
        return view('default.contratos.cancelados', [
            'title' => 'Contratos Cancelados',
            'dados' => Contrato::with([
                'item' => function ($query) {
                    $query
                        ->withTrashed()
                        ->select([
                            'id',
                            'name',
                            'tipo_pacote',
                        ]);
                },
            ])
                ->with([
                    'usuario' => function ($query) {
                        $query->withTrashed()->select([
                            'id',
                            'name',
                            'codigo',
                        ]);
                    },
                ])
                ->whereIn('status', [4, 7])->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $contrato = Contrato::with('item', 'usuario', 'mensalidades')->findOrFail($id);

            return view('default.contratos.edit', [
                'title' => "#{$contrato->id} - {$contrato->getRelation('usuario')->name} - Edição de contrato",
                'dados' => Contrato::with('item', 'usuario', 'mensalidades')->findOrFail($id),
            ]);
        } catch (ModelNotFoundException $e) {
            Log::error('Erro ao abrir contrato!', ['id' => $id, 'message' => $e->getMessage(), 'user' => Auth::user()->id]);

            flash()->error('Desculpe, ocorreu um erro ao acessar os dados!');

            return redirect()->route('contratos.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $contrato = Contrato::findOrFail($id);

            $contrato->update($request->all());

            DB::commit();

            flash()->success('Dados salvos com sucesso!');

            $retorno = 'contratos.index';

            if (2 == $contrato->status) {
                $retorno = 'contratos.abertos';
            }

            return redirect()->route($retorno);
        } catch (ModelNotFoundException $e) {
            DB::rollback();

            Log::error('Erro ao alterar contrato!', ['id' => $id, 'message' => $e->getMessage(), 'user' => Auth::user()->id]);

            flash()->error('Desculpe, ocorreu um erro ao atualizar os dados!');

            return redirect()->back();
        }
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function mensalidadesGerar($id)
    {
        DB::beginTransaction();
        try {
            \Log::info('Entrou para gerar mensalidade, contrato #'.$id);
            //verifica se há mensalidades geradas para resgata-las e não gerar novas

            $contrato = Contrato::with('item')->findOrFail($id);

            $mensalidade = new MensalidadeService();

            $mensalidade->gerar($contrato);

            DB::commit();

            return redirect()->route('contratos.abertos');
        } catch (ModelNotFoundException $e) {
            DB::rollback();

            flash()->error('Desculpe, houve um erro ao gerar o cârne!');

            return redirect()->back();
        }
    }

    public function mensalidadeAvulsa($contrato, $mensalidade)
    {
    }

    public function cancelarDentro($contrato)
    {
        DB::beginTransaction();

        try {
            $contrato = Contrato::findOrFail($contrato);

            $contrato->update([
                'status' => 7,
                'dt_cancelamento' => Carbon::now(),
            ]);

            DB::commit();

            return response()->json(['ok' => true, 'teste' => 'teste', 'contrato' => $contrato->id], 200);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['ok' => false, 'Erro' => $e->getTrace()], 500);
        }
    }

    public function cancelarFora($contrato)
    {
        DB::beginTransaction();
        try {
            $contrato = Contrato::findOrFail($contrato);

            $contrato->update([
                'status' => 4,
                'dt_cancelamento' => Carbon::now(),
            ]);

            DB::commit();

            return response()->json(['ok' => true, 'teste' => 'teste', 'contrato' => $contrato->id], 200);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['ok' => false, 'Erro' => $e->getMessage()], 500);
        }
    }
}
