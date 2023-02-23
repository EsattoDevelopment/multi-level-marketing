<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use App\Models\Sistema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SistemaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $dados = Sistema::findOrFail(1);

        return view('default.sistema.edit', compact('dados'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $request['emails_dados_bancarios'] = implode(', ', $request->get('emails_dados_bancarios') ?? []);
            $request['emails_documentacao'] = implode(', ', $request->get('emails_documentacao') ?? []);
            $request['emails_comprovante_pagamento'] = implode(', ', $request->get('emails_comprovante_pagamento') ?? []);

            $dados = Sistema::findOrFail(1);

            if(!isset($request->transferencia_interna_estornar_taxa))
                $request->merge(['transferencia_interna_estornar_taxa' => 0]);

            if(!isset($request->transferencia_externa_estornar_taxa))
                $request->merge(['transferencia_externa_estornar_taxa' => 0]);

            $dados->update($request->all());

            DB::commit();
            flash()->success('Registro alterado com sucesso!');

            return redirect()->route('sistema.edit', 1);
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            flash()->danger('Erro ao alterado o registro!');

            return redirect()->route('sistema.edit', 1);
        }
    }
}
