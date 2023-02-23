<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Log;
use App\Models\User;
use App\Models\Sistema;
use App\Models\Operacoes;
use App\Models\Movimentos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\MovimentoRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MovimentoController extends Controller
{
    private $sistema;

    public function __construct()
    {
        $this->middleware('permission:master|admin');
        $this->sistema = Sistema::findOrFail(1);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('default.movimento.create', [
            'title' => 'Movimentos ',
            'operacoes' => Operacoes::whereIn('id', [3, 4, 5, 13, 14, 17, 18, 19, 20, 33, 34])->get(),
            'usuarios' => User::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MovimentoRequest $request)
    {
        try {
            DB::beginTransaction();

            $dados = $request->all();

            $movimento = Movimentos::whereUserId($dados['user_id'])->orderBy('id')->get();

            if ($movimento) {
                $movimento = $movimento->last();
            }

            $dados['saldo_anterior'] = ! $movimento ? 0 : $movimento->saldo;
            $dados['responsavel_user_id'] = Auth::user()->id;

            if (in_array($dados['operacao_id'], [1, 2, 3, 6, 7, 9, 10, 13, 15, 16, 17, 18,  19, 20, 25, 26, 27, 32, 34])) {
                $dados['saldo'] = ! $movimento ? (float) $dados['valor_manipulado'] : (float) ($movimento->saldo + $dados['valor_manipulado']);
            } else {
                $dados['saldo'] = ! $movimento ? (float) (0 - $dados['valor_manipulado']) : (float) ($movimento->saldo - $dados['valor_manipulado']);
            }

            Log::notice('Add  {{ $this->sistema->moeda }}'.$dados['valor_manipulado']);
            Log::notice('para #'.$dados['user_id']);
            Log::notice('Movimentação', $dados);
            Movimentos::create($dados);
            flash()->success('Movimento cadastrado com sucesso');

            DB::commit();

            return redirect()->back();
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            flash()->error('Desculpe, Ocorreu um erro ao salvar o movimento');

            return redirect()->back();
        }
    }
}
