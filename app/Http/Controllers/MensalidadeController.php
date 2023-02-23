<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sistema;
use Illuminate\Http\Request;
use App\Models\MetodoPagamento;
use App\Services\BoletoService;
use App\Saude\Domains\Mensalidade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;
use Eduardokum\LaravelBoleto\Boleto\Render\Pdf;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MensalidadeController extends Controller
{
    private $sistema;

    public function __construct()
    {
        $this->middleware('manipularOutro', ['except' => ['produtoPago', 'pagamentoSistema']]);
        $this->middleware('permission:master|admin', ['only' => ['pagamentoSistema', 'produtoPago', 'getMensalidadeContrato']]);
        $this->sistema = Sistema::findOrfail(1);
    }

    public function show($id)
    {
        try {
            $mensalidade = Mensalidade::findOrFail($id);
            $serviceBoleto = new BoletoService($mensalidade->user_id);

            if (! $serviceBoleto->haveBanco()) {
                flash()->error('Não há banco selecionado no sistema para gerar boletos, verifique as configurações de contas!');

                return redirect()->back();
            }

            $dadosPagamento = [
                    'vencimento'  => Carbon::parse($mensalidade->getOriginal()['dt_pagamento']),
                    'valor'       => $mensalidade->valor,
                    'boleto_id'   => $mensalidade->id <= 3134 ? $mensalidade->contrato_id : $mensalidade->boleto_id,
                    'contrato_id' => $mensalidade->contrato_id.'/'.explode('/', $mensalidade->parcela)[0],
                    'parcela'     => $mensalidade->parcela,
                ];

            $boleto = $serviceBoleto->montarBoleto($dadosPagamento);

            $pdf = new Pdf();
            $pdf->addBoleto($boleto);
            flash()->success('Boleto gerado com sucesso!');
            $pdf->gerarBoleto('I', $boleto->getNossoNumero().'.pdf');
        } catch (ModelNotFoundException $a) {
        }
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
            return view('default.contratos.edit-mensalidade', [
                    'dados' => Mensalidade::with('contrato.usuario')->findOrFail($id),
                    'metodo_pagamento' => MetodoPagamento::whereIn('id', [1, 8])->get(),
                ]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, houve um erro ao acessar as informações!');

            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $mensalidade = Mensalidade::findOrFail($id);

            if ($mensalidade->getOriginal()['status'] < 4 || Auth::user()->can(['master', 'admin'])) {
                $mensalidade->update($request->all());

                flash()->success('Dados da mensalidade atualizados com sucesso!');

                //DB::rollback();
                DB::commit();
            } else {
                flash()->warning('Mensalidades fechadas/canceladas não podem ser editadas!');
                DB::rollback();

                return redirect()->back();
            }

            return redirect()->route('contratos.edit', $mensalidade->contrato_id);
        } catch (ModelNotFoundException $e) {
            DB::rollback();

            flash()->error('Desculpe, houve um erro ao atualizar os dados');

            return redirect()->back()->withInput();
        }
    }

    public function getMensalidadeContrato($mensalidade)
    {
        $mensalidades = Mensalidade::whereContratoId($mensalidade)->select([
                'id',
                'dt_pagamento',
                'valor',
                'dt_baixa',
                'status',
                ]);

        return Datatables::of($mensalidades)
                ->orderColumn('id', 'id $1')
                ->editColumn('status', function ($mensalidade) {
                    return '<span class="label label-'.$mensalidade->status_cor.'">'.$mensalidade->status.'</span>';
                })
                ->editColumn('valor', function ($mensalidade) {
                    return mascaraMoeda($this->sistema->moeda, $mensalidade->valor, 2, true);
                })
  /*              ->addColumn('action', function ($mensalidade) {
                    return '<div class="btn-group" role="group" aria-label="Botões de Ação">
                                <a href="'.route('mensalidade.show', $mensalidade).'"
                                       target="_blank" class="btn btn-warning">Boleto avulso</a>
                                                               </div>';
                })*/
                ->make(true);
    }
}
