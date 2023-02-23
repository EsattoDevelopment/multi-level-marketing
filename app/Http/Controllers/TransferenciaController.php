<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use App\Services\TransferenciaService;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Sistema;
use App\Models\Movimentos;
use App\Models\DadosBancarios;
use App\Models\Transferencias;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DestinatarioRequest;
use App\Http\Requests\TransferenciaRequest;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TransferenciaEfetivada;
use App\Notifications\TransferenciaSolicitada;
use App\Notifications\TransferenciaSolicitadaAdmin;
use App\Notifications\TransferenciaEfetivadaDestinatario;

class TransferenciaController extends Controller
{
    private $sistema;

    public function __construct()
    {
        $this->sistema = Sistema::findOrFail(1);

        $this->middleware('permission:master|admin', [
                'only' => [
                    'edit',
                    'todos',
                    'em_liquidacao',
                    'efetivar',
                    'cancelar',
                    'cancelados',
                ],
            ]);

        $this->middleware('Documentos', [
            'only' => [
                'transferenciaInterna',
                'destinatario',
                'store',
                'efetivar',
            ],
        ]);

        if ($this->sistema->habilita_autenticacao_transferencias) {
            $this->middleware('DoisFatores', [
                'only' => [
                    'transferenciaInterna',
                    'destinatario',
                    'store',
                    'efetivar',
                ],
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transferencias = \Auth::user()->transferencias;

        return view('default.transferencias.index', compact('transferencias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dadosBancarios = \Auth::user()->dadosBancarios->where('status_comprovante', 'validado');

        /*return view('default.transferencias.create', compact('dadosBancarios'));*/

        $transferencia_gratuitas = $this->sistema->transferencia_externa_qtde_gratis;
        $transferenciaService = new TransferenciaService();
        $transferencia_gratuitas_restante = $transferenciaService->transferenciaExternaGratuitaQtde(Auth::user()->id);

        return view('default.transferencias.create', [
            'dadosBancarios' => $dadosBancarios,
            'transferencia_gratuitas' => $transferencia_gratuitas,
            'transferencia_gratuitas_restante' => $transferencia_gratuitas_restante
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransferenciaRequest $request)
    {
        DB::beginTransaction();
        try {
            $valor = $this->getMoney($request->get('valor'));
            $transferenciaService = new TransferenciaService();

            $dadosTrasferencia = [
                'valor' => $valor,
                'user_id' => \Auth::user()->id,
                'responsavel_user_id' => \Auth::user()->id,
                'operacao_id' => 33,
                'dt_solicitacao' => Carbon::now()->format('Y-m-d H:i:s'),
            ];

            if ($request->has('conta_id')) {
                $conta = DadosBancarios::findOrFail($request->get('conta_id'));
                $dadosTrasferencia['dado_bancario_id'] = $conta->id;
                $dadosTrasferencia['valor_taxa'] = $transferenciaService->transferenciaExternaValorTaxa(\Auth::user()->id, $valor);
            }

            if ($request->has('user')) {
                $dadosTrasferencia['valor_taxa'] = $transferenciaService->transferenciaInternaValorTaxa(\Auth::user()->id, $valor);
                $userId = $request->get('user');
                $destinatario = User::findOrFail($userId);
                $dadosTrasferencia['destinatario_user_id'] = $destinatario->id;
                $dadosTrasferencia['dt_efetivacao'] = Carbon::now()->format('Y-m-d H:i:s');
                $dadosTrasferencia['status'] = 2;
            }

            $transferencia = Transferencias::create($dadosTrasferencia);

            if ($request->has('user')) {
                $resultado = self::efetivarTransacao($transferencia);

                if (! $resultado) {
                    DB::rollback();
                    flash()->error('Desculpe, não foi possivel realizar a operação!');

                    return redirect()->back();
                }
            }
            else
                {
                    $sucesso = self::debitarTransferenciaConta($transferencia);

                    if($sucesso){
                        //verifico se vai debitar taxa
                        if($transferencia->valor_taxa > 0)
                            $sucesso = self::debitarTransferenciaTaxa($transferencia);
                    }

                    if($sucesso)
                    {
                        Notification::send(User::findOrFail(2), new TransferenciaSolicitadaAdmin($transferencia));
                        \Auth::user()->notify(new TransferenciaSolicitada($transferencia));
                    }
                    else
                        {
                            flash()->error('Saldo insuficiente!');

                            return redirect()->back();
                        }
                }

            DB::commit();

            return redirect()->route('transferencia.index');
        } catch (\Exception $e) {
            DB::rollback();

            flash()->error('Desculpe, não foi possivel realizar a operação!');

            return  redirect()->back();
        }
    }

    /**
     * @param Transferencias $transferencia
     * @return bool
     */
    private function debitarTransferenciaConta(Transferencias $transferencia):bool
    {
        $sucesso = false;

        try
        {
            $usuario = $transferencia->usuario;
            $ultimoMovimento = $usuario->ultimoMovimento();

            $conta = $transferencia->conta;

            if ($conta)
            {
                $mensagem = "Transferência Nº {$transferencia->id} - {$conta->agencia}/{$conta->conta}/" . $conta->bancoReferencia->nome;
            }
            else
                {
                    $mensagem = "Transferência Nº {$transferencia->id} - Enviada para {$transferencia->destinatario->name} 0001/{$transferencia->destinatario->conta}";
                }

            if (decimal($ultimoMovimento->saldo) >= decimal($transferencia->valor))
            {
                $sucesso = true;

                $dadosMovimento = [
                    'valor_manipulado' => $transferencia->valor,
                    'saldo_anterior' => $ultimoMovimento->saldo,
                    'saldo' => $ultimoMovimento->saldo - $transferencia->valor,
                    'descricao' => $mensagem,
                    'responsavel_user_id' => Auth::user()->id,
                    'user_id' => $usuario->id,
                    'operacao_id' => 33,
                    'titulo_id' => $usuario->titulo->id,
                    'transferencia_id' => $transferencia->id,
                ];

                Movimentos::create($dadosMovimento);
            }
        }
        catch (\Exception $e)
        {

        }

        return $sucesso;
    }

    private function debitarTransferenciaTaxa(Transferencias $transferencia):bool
    {
        $sucesso = false;

        try
        {
            $usuario = $transferencia->usuario;
            $ultimoMovimento = $usuario->ultimoMovimento();

            $conta = $transferencia->conta;

            if ($conta)
            {
                $mensagem = "Taxa da transferência Nº {$transferencia->id} - {$conta->agencia}/{$conta->conta}/" . $conta->bancoReferencia->nome;
            }
            else
            {
                $mensagem = "Taxa da transferência Nº {$transferencia->id} - Enviada para {$transferencia->destinatario->name} 0001/{$transferencia->destinatario->conta}";
            }

            if (decimal($ultimoMovimento->saldo) >= decimal($transferencia->valor_taxa))
            {
                $sucesso = true;

                $dadosMovimento = [
                    'valor_manipulado' => $transferencia->valor_taxa,
                    'saldo_anterior' => $ultimoMovimento->saldo,
                    'saldo' => $ultimoMovimento->saldo - $transferencia->valor_taxa,
                    'descricao' => $mensagem,
                    'responsavel_user_id' => Auth::user()->id,
                    'user_id' => $usuario->id,
                    'operacao_id' => 5,
                    'titulo_id' => $usuario->titulo->id,
                    'transferencia_id' => $transferencia->id,
                ];

                Movimentos::create($dadosMovimento);
            }
        }
        catch (\Exception $e)
        {

        }

        return $sucesso;
    }

    private function estornarTransferenciaConta(Transferencias $transferencia):bool
    {
        $sucesso = false;

        try
        {
            $usuario = $transferencia->usuario;
            $ultimoMovimento = $usuario->ultimoMovimento();

            $conta = $transferencia->conta;

            if ($conta)
            {//transferencia externa
                $mensagem = "Estorno transferência Nº {$transferencia->id} - {$conta->agencia}/{$conta->conta}/" . $conta->bancoReferencia->nome;
            }
            else
                {//transferencia interna
                    $mensagem = "Estorno transferência Nº {$transferencia->id} - {$transferencia->destinatario->name} 0001/{$transferencia->destinatario->conta}";
                }

            $dadosMovimento = [
                'valor_manipulado' => $transferencia->valor,
                'saldo_anterior' => $ultimoMovimento->saldo,
                'saldo' => $ultimoMovimento->saldo + $transferencia->valor,
                'descricao' => $mensagem,
                'responsavel_user_id' => Auth::user()->id,
                'user_id' => $usuario->id,
                'operacao_id' => 23,
                'titulo_id' => $usuario->titulo->id,
                'transferencia_id' => $transferencia->id,
            ];

            Movimentos::create($dadosMovimento);

            $sucesso = true;

            if($sucesso){
                //verifico se vai estornar as taxas tbm
                if ($conta)
                {//transferencia externa
                    if($this->sistema->transferencia_externa_estornar_taxa == 1 && $transferencia->valor_taxa > 0)
                        $sucesso = self::estornarTransferenciaContaTaxa($transferencia);
                }
                else
                {//transferencia interna
                    if($this->sistema->transferencia_interna_estornar_taxa == 1 && $transferencia->valor_taxa > 0)
                        $sucesso = self::estornarTransferenciaContaTaxa($transferencia);
                }
            }
        }
        catch (\Exception $e)
        {

        }

        return $sucesso;
    }

    private function estornarTransferenciaContaTaxa(Transferencias $transferencia):bool
    {
        $sucesso = false;

        try
        {
            $usuario = $transferencia->usuario;
            $ultimoMovimento = $usuario->ultimoMovimento();

            $conta = $transferencia->conta;

            if ($conta)
            {//transferencia externa
                $mensagem = "Estorno taxa da transferência Nº {$transferencia->id} - {$conta->agencia}/{$conta->conta}/" . $conta->bancoReferencia->nome;
            }
            else
            {//transferencia interna
                $mensagem = "Estorno taxa da transferência Nº {$transferencia->id} - {$transferencia->destinatario->name} 0001/{$transferencia->destinatario->conta}";
            }

            $dadosMovimento = [
                'valor_manipulado' => $transferencia->valor_taxa,
                'saldo_anterior' => $ultimoMovimento->saldo,
                'saldo' => $ultimoMovimento->saldo + $transferencia->valor_taxa,
                'descricao' => $mensagem,
                'responsavel_user_id' => Auth::user()->id,
                'user_id' => $usuario->id,
                'operacao_id' => 23,
                'titulo_id' => $usuario->titulo->id,
                'transferencia_id' => $transferencia->id,
            ];

            Movimentos::create($dadosMovimento);

            $sucesso = true;
        }
        catch (\Exception $e)
        {

        }

        return $sucesso;
    }

    private function efetivarTransacao(Transferencias $transferencia):bool
    {
        $sucesso = false;

        try
        {
            $usuario = $transferencia->usuario;
            $conta = $transferencia->conta;

            if ($conta)
            {
                $transferencia->responsavel_user_id = Auth::user()->id;
                $sucesso = true;
            }
            else
                {
                    $sucesso = self::debitarTransferenciaConta($transferencia);

                    if($sucesso){
                        //verifico se vai debitar taxa
                        if($transferencia->valor_taxa > 0)
                            $sucesso = self::debitarTransferenciaTaxa($transferencia);
                    }

                    if($sucesso)
                    {
                        $ultimoMovimentoDestinatario = $transferencia->destinatario->ultimoMovimento();

                        $dadosMovimentoDestinatario = [
                            'valor_manipulado' => $transferencia->valor,
                            'saldo_anterior' => $ultimoMovimentoDestinatario ? $ultimoMovimentoDestinatario->saldo : 0,
                            'saldo' => $ultimoMovimentoDestinatario ? $ultimoMovimentoDestinatario->saldo + $transferencia->valor : $transferencia->valor,
                            'descricao' => "Transferência Nº {$transferencia->id} - Recebida de {$transferencia->usuario->name} 0001/{$transferencia->usuario->conta}",
                            'responsavel_user_id' => Auth::user()->id,
                            'user_id' => $transferencia->destinatario->id,
                            'operacao_id' => 34,
                            'titulo_id' => $transferencia->destinatario->titulo->id,
                            'transferencia_id' => $transferencia->id,
                        ];

                        Movimentos::create($dadosMovimentoDestinatario);
                    }
                    else
                    {
                        flash()->error('Saldo insuficiente!');

                        return redirect()->back();
                    }
                }

            if($sucesso)
            {
                $transferencia->status = 2;
                $transferencia->dt_efetivacao = Carbon::now()->format('Y-m-d H:i:s');

                $transferencia->save();

                $usuario->notify(new TransferenciaEfetivada($transferencia));

                if ($transferencia->destinatario)
                {
                    $transferencia->destinatario->notify(new TransferenciaEfetivadaDestinatario($transferencia));
                }
            }
        }
        catch (\Exception $e)
        {

        }

        return $sucesso;
    }

    public function createTransferenciaLiberty()
    {
        return view('default.transferencias.destinatario');
    }

    public function destinatario(DestinatarioRequest $request)
    {
        $agencia = $request->get('agencia');
        $conta = $request->get('conta');
        $digito_conta = $request->get('digito_conta');
        $usuario = User::whereConta($conta)->where('id', '<>', Auth::user()->id)->first();

        if (! $usuario instanceof User) {
            flash()->error('Conta não encontrada!');

            return redirect()->back();
        }

        $transferencia_gratuitas = $this->sistema->transferencia_interna_qtde_gratis;
        $transferenciaService = new TransferenciaService();
        $transferencia_gratuitas_restante = $transferenciaService->transferenciaInternaGratuitaQtde(Auth::user()->id);

        return view('default.transferencias.destinatario-valor', [
            'usuario' => $usuario,
            'transferencia_gratuitas' => $transferencia_gratuitas,
            'transferencia_gratuitas_restante' => $transferencia_gratuitas_restante
        ]);
    }

    public function transferenciaInterna(TransferenciaRequest $request)
    {
        DB::beginTransaction();
        try {
            $valor = $this->getMoney($request->get('valor'));

            $conta = DadosBancarios::findOrFail($request->get('conta_id'));

            $dadosTrasferencia = [
                'valor' => $valor,
                'user_id' => \Auth::user()->id,
                'responsavel_user_id' => \Auth::user()->id,
                'dado_bancario_id' => $conta->id,
                'operacao_id' => 33,
                'dt_solicitacao' => Carbon::now()->format('Y-m-d H:i:s'),
            ];

            $transferencia = Transferencias::create($dadosTrasferencia);

            DB::commit();

            return redirect()->route('transferencia.index');
        } catch (\Exception $e) {
            DB::rollback();

            flash()->error('Desculpe, não foi possivel realizar a operação!');

            return  redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function todos()
    {
        $transferencias = Transferencias::all();

        return view('default.transferencias.todos', compact('transferencias'));
    }

    public function cancelados()
    {
        $transferencias = Transferencias::whereStatus(3)->get();

        return view('default.transferencias.cancelados', compact('transferencias'));
    }

    public function em_liquidacao()
    {
        $transferencias = Transferencias::whereStatus(1)->get();

        return view('default.transferencias.em_liquidacao', compact('transferencias'));
    }

    public function efetivar($id)
    {
        DB::beginTransaction();

        $transferencia = Transferencias::findOrFail($id);

        $resultado = self::efetivarTransacao($transferencia);

        if ($resultado) {
            DB::commit();

            return redirect()->route('transferencia.em_liquidacao');
        } else {
            DB::rollback();

            flash()->error('Desculpe, ocorreu um erro ao efetivar transferência!');

            return redirect()->back();
        }
    }

    public function cancelar($id)
    {
        DB::beginTransaction();

        try
        {
            $transferencia = Transferencias::findOrFail($id);

            $transferencia->status = 3;
            $transferencia->save();

            if($this->estornarTransferenciaConta($transferencia))
            {
                DB::commit();
            }
            else
            {
                DB::rollback();
            }

            return redirect()->route('transferencia.em_liquidacao');
        }
        catch (\Exception $e)
        {
            DB::rollback();

            return redirect()->route('transferencia.em_liquidacao');
        }
    }

    /**
     * @param string $value
     * @return float|mixed|string
     */
    private function getMoney(string $value)
    {
        $sistema = Sistema::find(1);
        $value = str_replace([$sistema->moeda, ' '], '', $value);
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);
        $value = (float) $value;

        return $value;
    }
}
