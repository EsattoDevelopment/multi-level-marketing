<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Saude\Domains\Dependente;
use Illuminate\Support\Facades\DB;
use App\Saude\Http\Requests\DependentesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DependenteController extends BaseController
{
    public function __construct()
    {
        $this->middleware('UserDesativado', [
                'only' => [
                        'store',
                        'edit',
                        'destroy',
                        'create',
                    ],
            ]);

        $this->middleware('SemPedido', [
                'only' => [
                        'store',
                        'edit',
                        'destroy',
                        'create',
                    ],
            ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user)
    {
        //dd(User::select(['id', 'name'])->find($user)->conjuge());
        return $this->view('dependentes.index', [
                'dados'             => Dependente::whereStatus(1)->whereTitularId($user)->get(),
                'dados_desativados' => Dependente::whereStatus(0)->whereTitularId($user)->get(),
                'usuario'           => User::select(['id', 'name'])->find($user),
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($user)
    {
        return $this->view('dependentes.create', [
                'usuario' => User::select(['id', 'name'])->find($user),
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DependentesRequest|Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store($user, DependentesRequest $request)
    {
        DB::beginTransaction();
        try {
            Dependente::create($request->all());

            DB::commit();

            flash()->success('Dependente inserido com sucesso!');

            return redirect()->route('saude.dependentes.index', $user);
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
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($user, $id)
    {
        try {
            return $this->view('dependentes.edit', [
                    'dados'   => Dependente::whereId($id)->whereTitularId($user)->first(),
                    'usuario' => User::select(['id', 'name'])->find($user),
                ]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, houve um erro ao abrir os dados');

            return redirect()->route('saude.dependentes.index', $user);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param DependentesRequest|Request $request
     * @param  int                       $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($user, DependentesRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $dependente = Dependente::findOrFail($id);

            $dependente->update($request->all());

            DB::commit();

            flash()->success('Dados salvos com sucesso!');

            return redirect()->route('saude.dependentes.index', $user);
        } catch (ModelNotFoundException $e) {
            DB::rollback();

            flash()->error('Desculpe, houve um erro na operação!');

            return redirect()->back()->withInput();
        }
    }

    public function busca(Request $request)
    {
        return \Response::json(
                Dependente::where('titular_id', $request->get('search'))
                    ->select(['id', 'name', 'parentesco', 'rg'])
                    ->get(),
                200);
    }
}
