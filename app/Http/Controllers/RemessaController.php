<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Remessa;
use Illuminate\Http\Request;
use App\Services\RemessaService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Facades\Datatables;

class RemessaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master', [
                'only' => [
                    'destroy',
                ],
            ]);

        $this->middleware('permission:master|admin', [
                'except' => [
                    'destroy',
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
        return view('default.remessas.index');
    }

    public function getRemessas()
    {
        $remessas = DB::table('remessas')
                ->select(['id', 'numero', 'arquivo', 'created_at', 'efetivado']);

        return Datatables::of($remessas)
                ->editColumn('created_at', function ($remessa) {
                    return $remessa->created_at ? with(new Carbon($remessa->created_at))->format('d/m/Y') : '';
                })
                ->addColumn('action', function ($remessa) {
                    $retorno = '<form method="post" id="formDel_'.$remessa->id.'" action="'.route('remessa.destroy', $remessa->id == 1 ? 0 : $remessa->id).'">
                                '.csrf_field().'
                                <div class="btn-group" role="group" aria-label="Botões de Ação">
                                    <a title="Download" class="btn btn-default btn-sm" href="'.route('remessa.download', $remessa->id).'">
                                        <span class="glyphicon glyphicon-download text-success" aria-hidden="true"></span> Baixar
                                    </a>';

                    if ($remessa->efetivado == 0) {
                        $retorno .= '<a title="Efetivada" class="btn btn-default btn-sm" href="'.route('remessa.efetivar', $remessa->id).'">
                                        <span class="glyphicon glyphicon-ok text-success" aria-hidden="true"></span> Efetivada
                                    </a>
                                     <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" data-id="'.$remessa->id.'" class="btn btn-danger btn-sm botao-del" >
                                        <span class="glyphicon glyphicon-trash text-black"></span>
                                    </button>';
                    }

                    $retorno .= '</div></form>';

                    return $retorno;
                })
                ->addColumn('details_url', function ($remessa) {
                    return route('remessa.boletos', $remessa->id);
                })
                ->orderColumn('created_at', 'created_at $1')
                ->make(true);
    }

    public function getBoletos($id)
    {
        $boletos = DB::table('boletos as b')
                ->where('b.remessa_id', $id)
                ->leftJoin('mensalidades as m', 'm.boleto_id', '=', 'b.id')
                ->leftJoin('pedidos as p', 'p.boleto_id', '=', 'b.id')
                ->select([
                    'b.id',
                    'b.nosso_numero',
                    'b.vencimento',
                    'b.created_at',
                    'b.numero_documento',
                    DB::raw('IFNULL(m.valor, p.valor_total) as valor'),
                    DB::raw('IFNULL(m.user_id, p.user_id) as user_id'), ]);

        return Datatables::of($boletos)
                ->editColumn('vencimento', function ($boleto) {
                    return with(new Carbon($boleto->vencimento))->format('d/m/Y');
                })
                ->editColumn('created_at', function ($boleto) {
                    return with(new Carbon($boleto->created_at))->format('d/m/Y');
                })
                ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $remessa = new RemessaService();

        return $remessa->gerar(500);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function efetivar($remessa)
    {
        DB::beginTransaction();
        try {
            $remessa = Remessa::find($remessa);

            if ($remessa->efetivado == 0) {
                $remessa->efetivado = 1;
                $remessa->dt_efetivado = date('Y-m-d H:i:s');

                $remessa->save();

                DB::commit();

                flash()->success('Operação realizada com sucesso!');
            } else {
                flash()->warning('A remessa já foi efetivada anteriormente!');
            }

            return redirect()->route('remessa.index');
        } catch (\Exception $e) {
            DB::rollback();

            flash()->error('Houve um erro ao realizar a operação!');

            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download($remessa)
    {
        $remessa = Remessa::find($remessa);
        $arquivo = '/'.$remessa->arquivo.'.';

        if ($remessa->numero == 1) {
            $arquivo .= 'CRM';
        } else {
            if ($remessa->numero < 10) {
                $arquivo .= 'RM'.$remessa->numero;
            } else {
                $arquivo .= 'RM0';
            }
        }

        return response()->download('remessas/'.$remessa->created_at->format('Y').$arquivo);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $remessa = Remessa::find($id);

            if ($remessa->efetivado == 0) {
                $arquivo = 'remessas/'.$remessa->created_at->format('Y').'/'.$remessa->arquivo.'.';

                if ($remessa->numero == 1) {
                    $arquivo .= 'CRM';
                } else {
                    if ($remessa->numero < 10) {
                        $arquivo .= 'RM'.$remessa->numero;
                    } else {
                        $arquivo .= 'RM0';
                    }
                }

                $remessa->delete();

                if (Storage::disk('public')->exists($arquivo)) {
                    Storage::disk('public')->delete($arquivo);
                }

                flash()->success('Registro apagado com sucesso!');
            } else {
                flash()->error('Remessa não pode ser apagada, porque já foi efetivada!');
            }

            return redirect()->route('remessa.index');
        } catch (\Exception $e) {
            flash()->success('Registro apagado com sucesso!');

            return redirect()->back();
        }
    }
}
