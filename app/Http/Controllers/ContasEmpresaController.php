<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Log;
use App\Models\Bancos;
use Illuminate\Http\Request;
use App\Models\ContasEmpresa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ContasEmpresaRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ContasEmpresaController extends Controller
{
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
        return view('default.contas_empresa.index', [
            'title' => 'Contas empresa ',
            'dados' => ContasEmpresa::with('banco')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('default.contas_empresa.create', [
            'title' => 'Cadastro de contas ',
            'bancos' => Bancos::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ContasEmpresaRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContasEmpresaRequest $request)
    {
        try {
            DB::beginTransaction();

            if ($request->get('usar_boleto') == 1) {
                ContasEmpresa::whereBancoId($request->get('banco_id'))->update(['usar_boleto' => 0]);
            }

            ContasEmpresa::create($request->all());

            DB::commit();

            flash()->success('Conta cadastrada com sucesso!');

            Log::info('Conta cadastrada:', ['user ação' => Auth::user()->id]);

            return redirect()->route('contas_empresa.index');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            flash()->error('Desculpe, erro ao cadastrar conta. Tente novamente, se o erro persistir entre em contato com o ADM!');

            Log::info('Erro ao cadastrar conta', ['user' => Auth::user()->id]);

            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('default.contas_empresa.edit', [
            'title' => 'Editar Contas empresa ',
            'dados' => ContasEmpresa::with('banco')->whereId($id)->first(),
            'bancos' => Bancos::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ContasEmpresaRequest $request, $id)
    {
        try {
            $conta = ContasEmpresa::findOrFail($id);

            Log::info('Conta antes alteração:', $conta->toArray());

            DB::beginTransaction();

            if ($request->get('usar_boleto') == 1) {
                ContasEmpresa::whereBancoId($request->get('banco_id'))->update(['usar_boleto' => 0]);
            }

            $conta->update($request->all());

            DB::commit();

            flash()->success('Conta Alterada com sucesso!');

            Log::info('Conta apos alteração:', $request->except('_token'));

            return redirect()->route('contas_empresa.index');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            flash()->error('Desculpe, erro ao editar conta. Tente novamente, se o erro persistir entre em contato com o ADM!');

            Log::info('Erro ao editar conta', ['user' => Auth::user()->id]);

            return redirect()->back();
        }
    }
}
