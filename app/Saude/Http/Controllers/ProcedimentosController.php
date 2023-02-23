<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Http\Controllers;

use Illuminate\Http\Request;
use App\Saude\Domains\Procedimento;
use Yajra\Datatables\Facades\Datatables;
use App\Saude\Http\Requests\ProcedimentosRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProcedimentosController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:master|admin');
//        $this->middleware('role:user-empresa');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('procedimentos.index', [
            'dados' => Procedimento::take(10)->get(),
            'dados_desativados' => Procedimento::onlyTrashed()->get(),
        ]);
    }

    public function apiBusca(Request $request)
    {
        return \Response::json(
            Procedimento::where('name', 'like', "%{$request->get('search')}%")
                ->orWhere('id', "{$request->get('search')}")
                ->orWhere('codigo', "{$request->get('search')}")
                ->select('id', 'name', 'codigo')
                ->take(15)->get(),
            200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexJson()
    {
        return Datatables::of(Procedimento::query())
            ->addColumn('valor', function ($procedimento) {
                return mascaraMoeda($sistema->moeda, $procedimento->valor, 2, true);
            })
            ->addColumn('action', function ($procedimento) {
                $retorno = '<form action="'.route('saude.procedimentos.destroy', $procedimento->id).'" method="post">
                        '.csrf_field().'
                        <div class="btn-group" role="group" aria-label="Botões de Ação">                        
                        <a title="Editar" class="btn btn-default btn-sm"
                           href="'.route('saude.procedimentos.edit', $procedimento->id).'">
                                        <span class="glyphicon glyphicon-edit text-success"
                                              aria-hidden="true"></span> Editar
                        </a>                      
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" data-id="'.$procedimento->id.'" class="btn btn-danger btn-sm botao-del" >
                            <span class="glyphicon glyphicon-trash text-black"></span>
                        </button>                    
                        ';
                $retorno .= '</div></form>';

                return $retorno;
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
        return $this->view('procedimentos.create', [
            'title' => 'Cadastro de Procedimentos',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProcedimentosRequest $request)
    {
        try {
            $procedimento = Procedimento::create($request->request->all());
            flash()->success("Procedimento <b>{$procedimento->name}</b> inserido com sucesso!");

            return redirect()->route('saude.procedimentos.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, houve um erro na operação!');

            return redirect()->back()->withInput();
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
            $procedimento = Procedimento::findOrFail($id);

            return $this->view('procedimentos.edit', [
                'title' => "{$procedimento->name} - Edição de procedimento",
                'dados' => $procedimento,
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('saude.procedimentos.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProcedimentosRequest $request, $id)
    {
        try {
            $procedimento = Procedimento::findOrFail($id);
            $procedimento->update($request->all());
            flash()->success("Procedimento <b>{$procedimento->name}</b> atualizado com sucesso!");

            return redirect()->route('saude.procedimentos.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, houve um erro na operação!');

            return redirect()->back()->withInput();
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
        try {
            Procedimento::destroy($id);

            flash()->warning(sprintf('Procedimento desativado com sucesso. Caso queira reativar <a href="%s">clique aqui</a>.', route('saude.procedimentos.recovery', $id)));

            return redirect()->route('saude.procedimentos.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao desativar o Procedimento.');

            return redirect()->route('saude.procedimentos.index');
        }
    }

    public function recovery($id)
    {
        try {
            $procedimento = Procedimento::onlyTrashed()->findOrFail($id);
            $procedimento->restore();

            flash()->success('Registro ativado com sucesso!');

            return redirect()->route('saude.procedimentos.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Erro ao ativar o registro da base de dados!');

            return redirect()->route('saude.procedimentos.index');
        }
    }
}
