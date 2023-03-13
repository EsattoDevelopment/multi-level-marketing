<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Log;
use Carbon\Carbon;
use App\Models\Itens;
use App\Models\Pedidos;
use App\Models\Sistema;
use App\Models\Movimentos;
use App\Models\ItensPedido;
use Illuminate\Http\Request;
use App\Models\ContasEmpresa;
use App\Models\DadosPagamento;
use App\Models\MetodoPagamento;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OutrosRequest;
use App\Http\Requests\PedidoRequest;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests\AutenticacaoContratacaoRequest;
use App\Http\Requests\AutenticacaoRecontratacaoRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class PedidoController.
 */
class PedidoController extends Controller
{
    protected $sistema;

    /**
     * PedidoController constructor.
     */
    public function __construct()
    {
        $this->sistema = Sistema::findOrFail(1);
        $this->middleware('manipularOutro', [
            'except' => [
                'show',
                'index',
                'create',
                'interna',
                'store',
                'pagos',
                'pagosJson',
                'aguardandoPagamento',
                'cancelados',
                'aguardandoConfirmacao',
                'bonusVisualizar',
                'bonus',
                'consultor',
                'verContrato',
                'visualizarBoleto',
                'modoRecontratacao',
                'novoContrato',
                'usuarioPedidosAguardandoPagamento',
                'usuarioPedidosAguardandoConferencia',
                'usuarioPedidosConfirmados',
                'usuarioPedidosCancelados',

                'normalAguardandoPagamento',
                'normalAguardandoConfirmacao',
                'nomalCancelados',
            ],
            ]);

        $this->middleware('permission:master|admin', [
            'only' => [
                'show',
                'index',
                'pagos',
                'aguardandoPagamento',
                'cancelados',
                'aguardandoConfirmacao',
                'download',
            ],
        ]);

        $this->middleware('permission:master', [
            'only' => [
                'edit',
            ],
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $pedidos = Pedidos::with('itens.itens', 'dadosPagamento')
            ->with(['user' => function ($query) {
                $query->withTrashed()->select('id', 'name', 'username', 'cpf', 'empresa');
            },])->get();
        return view('default.pedidos.index', [
            'title' => 'Todos pedidos',
            'pedidos_aguardando' => $pedidos->where('status', 1),
            'pedidos_pagos' => $pedidos->where('status', 2),
            'pedidos_cancelados' => $pedidos->where('status', 3),
            'pedidos_aguarda_confimacao' => $pedidos->where('status', 4),
        ]);
    }

    /**
     * @param $pacote
     * @return Factory|RedirectResponse|View
     */
    public function interna($pacote)
    {
        try {
            return view('default.pedidos.interna', [
                'title' => 'Página interna pacote',
                'item'  => Itens::findOrFail($pacote),
            ]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, o registro não pode ser carregado!');
            return redirect()->back();
        }
    }

    /**
     * @param OutrosRequest $request
     * @return Factory|View
     */
    public function bonus(OutrosRequest $request)
    {
        if ($request->has('pedido_id')) {
            $pedidos = Pedidos::with('itens.itens', 'dadosPagamento.responsavel', 'user', 'status')->find($request->get('pedido_id'));
            if ($pedidos) {
                if (in_array($pedidos->status, [2, 3])) {
                    flash()->error('Pedido já finalizado no sistema!');
                    $pedidos = null;
                } else {
                    $request->session()->forget('flash_notification');
                }
            } else {
                flash()->error('Pedido não encontrado!');
                $pedidos = null;
            }
        } else {
            $request->session()->forget('flash_notification');
            $pedidos = null;
        }

        return view('default.pedidos.outros', [
                'title'   => 'Todos pedido',
                'pedidos' => $pedidos,
            ]);
    }

    /**
     * @return Factory|View
     */
    public function pagos()
    {
        return view('default.pedidos.pagos', [
            'title' => 'Pedidos Pagos ',
        ]);
    }

    /**
     * @return mixed
     */
    public function pagosJson()
    {
        $pedidos = DB::table('pedidos as p')
            ->leftjoin('itens_pedido as ip', 'ip.pedido_id', '=', 'p.id')
            ->leftjoin('itens as i', 'i.id', '=', 'ip.item_id')
            ->leftjoin('dados_pagamento as dp', 'dp.pedido_id', '=', 'p.id')
            ->leftjoin('users as u', 'u.id', '=', 'p.user_id')
            ->where('p.status', '=', 2)
            ->select([
                'p.id',
                'i.name as item',
                'u.name as nome',
                'u.username',
                'p.valor_total',
                'p.data_compra',
                'dp.data_pagamento',
                'dp.path_comprovante_ted',
            ]);
        $retorno = Datatables::of($pedidos)
            ->orderColumn('id', 'id $1')
            ->editColumn('data_compra', function ($pedidos) {
                return $pedidos->data_compra ? with(new Carbon($pedidos->data_compra))->format('d/m/Y H:i:s') : '';
            })
            ->editColumn('data_pagamento', function ($pedidos) {
                return $pedidos->data_pagamento ? with(new Carbon($pedidos->data_pagamento))->format('d/m/Y H:i:s') : '';
            })
            ->editColumn('valor_total', function ($pedidos) {
                return mascaraMoeda($this->sistema->moeda, $pedidos->valor_total, 2, true);
            });
        if (Auth::user()->can(['master', 'admin'])) {
            $retorno->addColumn('action', function ($pedido) {
                $botoes = '<div class="btn-group" role="group" aria-label="Botões de Ação">
                        <a title="Editar" class="btn btn-default btn-sm" href="'.route('pedido.edit', $pedido->id).'">
                            <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"> </span> Editar
                        </a>';
                if ($pedido->path_comprovante_ted) {
                    $botoes .= '<a data-fancybox href="'.route('imagecache', ['visualizardoc', $pedido->path_comprovante_ted]).'" 
                            title="Visualizar Comprovante" class="btn btn-default btn-sm">
                            <span class="fa fa-file-text-o text-success" aria-hidden="true"> </span> Comprovante
                        </a>';
                } else {
                    $botoes .= '</div>';
                }

                return $botoes;
                /*return '
                     <div class="btn-group" role="group" aria-label="Botões de Ação">
                        <a title="Editar" class="btn btn-default btn-sm" href="'.route('pedido.edit', $pedido->id).'">
                            <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"> </span> Editar
                        </a>
                        <a data-fancybox href="' . route('imagecache',['visualizardoc', $pedido->imagem_comprovante]) . '"
                            title="Visualizar Comprovante" class="btn btn-default btn-sm">
                            <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"> </span> Comprovante
                        </a>
                     </div>
                ';*/
            });
        }

        return $retorno->make(true);
    }

    /**
     * @return Factory|View
     */
    public function aguardandoPagamento()
    {
        return view('default.pedidos.aguardando-pagamento', [
            'title' => 'Aguardando pagamento',
            'pedidos_aguardando' => DB::table('pedidos')
                ->join('users', 'users.id', '=', 'pedidos.user_id')
                ->where('tipo_pedido', 4)
                ->select(
                    'pedidos.id',
                    'pedidos.valor_total',
                    'pedidos.data_compra',
                    'users.name',
                    'users.empresa',
                    'users.id as user_id',
                    'users.username'
                )
                ->where('pedidos.status', 1)
                ->get(),
        ]);
    }

    public function normalAguardandoPagamento()
    {
        return view('default.pedidos.aguardando-pagamento', [
            'title'              => 'Aguardando pagamento',
            'pedidos_aguardando' => DB::table('pedidos')
                ->join('itens_pedido', 'itens_pedido.pedido_id', '=', 'pedidos.id')
                ->join('itens', 'itens.id', '=', 'itens_pedido.item_id')
                ->join('users', 'users.id', '=', 'pedidos.user_id')
                ->where('itens.tipo_pedido_id', '<>', 4)
                ->select(
                    'pedidos.id',
                    'pedidos.valor_total',
                    'pedidos.data_compra',
                    'itens.name as item_name',
                    'users.name',
                    'users.empresa',
                    'users.id as user_id',
                    'users.username'
                )->where('pedidos.status', 1)->get(),
        ]);
    }

    /**
     * @return Factory|View
     */
    public function aguardandoConfirmacao()
    {
        return view('default.pedidos.aguardando-confirmacao', [
            'title' => 'Aguardando confirmação ',
            'pedidos_aguarda_confimacao' => Pedidos::with('itens.itens', 'dadosPagamento', 'user')
                ->where('tipo_pedido', 4)
                ->where('status', 4)->get(),
        ]);
    }

    public function normalAguardandoConfirmacao()
    {
        return view('default.pedidos.aguardando-confirmacao', [
            'title'                      => 'Aguardando confirmação ',
            'pedidos_aguarda_confimacao' => Pedidos::with('itens.itens', 'dadosPagamento.metodoPagamento', 'user')
                ->whereHas('itens', function ($query) {
                    $query->whereHas('item', function ($query) {
                        $query->where('tipo_pedido_id', '<>', 4);
                    });
                })
                ->where('status', 4)->get(),
        ]);
    }

    /**
     * @return Factory|View
     */
    public function cancelados()
    {
        return view('default.pedidos.cancelados', [
            'title' => 'Pedidos cancelados ',
            'pedidos_cancelados' => Pedidos::with('itens.itens', 'dadosPagamento', 'user')->where('status', 3)->get(),
        ]);
    }

    public function nomalCancelados()
    {
        return view('default.pedidos.cancelados', [
            'title' => 'Pedidos cancelados ',
            'pedidos_cancelados' => Pedidos::with('itens.itens', 'dadosPagamento', 'user')
                ->whereHas('itens', function ($query) {
                    $query->whereHas('item', function ($query) {
                        $query->where('tipo_pedido_id', '<>', 4);
                    });
                })
                ->where('status', 3)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|Application|View
     */
    public function create()
    {
        $itens = Itens::with('titulo')
            ->whereIn('tipo_pedido_id', [1, 2])
            ->whereNull('user_id')
            ->where('ativo', 1)
            ->orderBy('ordem_exibicao')
            ->get();
        return view('default.pedidos.create', [
            'title' => 'Novo pedido',
            'itens' => $itens,
        ]);
    }

    /**
     * @return Factory|View
     */
    public function consultor()
    {
        $itens = Itens::with('titulo')
            ->whereIn('tipo_pedido_id', [3])
            ->whereNull('user_id')
            ->where('ativo', 1)
            ->get();
        return view('default.pedidos.consultor', [
            'title' => 'Seja um Credenciado',
            'itens' => $itens,
        ]);
    }

    /**
     * @param $id
     * @return Factory|View
     */
    public function edit($id)
    {
        return view('default.pedidos.edit-adesao', [
            'title'  => 'Pedido #'.$id.' ',
            'dados'  => Pedidos::with('itens.itens', 'user', 'dadosPagamento.responsavel')
                ->find($id),
            'contas' => ContasEmpresa::with('banco')->whereUsarBoleto(1)->get(),
            'metodo_pagamento' => MetodoPagamento::whereIn('id', [1, 8])->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PedidoRequest|Request $request
     *
     * @return Response
     */
    public function store(PedidoRequest $request)
    {
        return self::salvar($request);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request)
    {
        $pedido = Pedidos::findOrFail($request->get('pedido_id'));
        $request->merge(['data_pagamento' => implode('-', array_reverse(explode('/', $request->get('data_pagamento'))))]);

        $pedido->dadosPagamento->update($request->except(['pedido_id']));

        flash()->success('Registro salvo com sucesso!');

        return redirect()->route('pedido.pagos');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Application|Factory|View
     */
    public function show($id)
    {
        $contas = null;
        $contaEmpresa = null;
        $metodosPagamento = null;
        $pedido = Pedidos::with('itens.itens', 'dadosPagamento', 'user')->find($id);
        $metodosPagamento = MetodoPagamento::where('status', 1)->get();
        $metodosPagamentoBancoTed = ContasEmpresa::with('banco')->where('recebe_ted', 1)->get();
        if ($pedido->getRelation('dadosPagamento')->metodo_pagamento_id == 9) {
            $contaEmpresa = ContasEmpresa::with('banco')->find($pedido->getRelation('dadosPagamento')->conta_empresa_id);
            $metodosPagamento = MetodoPagamento::find($pedido->getRelation('dadosPagamento')->metodo_pagamento_id);
        }
        return view('default.pedidos.show', [
            'title'  => 'Pedido #'.$id.' ',
            'dados'  => $pedido,
            'contas' => $contas,
            'contaEmpresa' => $contaEmpresa,
            'metodo_pagamento' => $metodosPagamento,
            'metodosPagamentoBancoTed' => $metodosPagamentoBancoTed,
        ]);
    }

    /**
     * @param $id
     * @return Factory|View
     */
    public function usuarioPedidos($id)
    {
        return view('default.pedidos.user-pedido', [
            'title' => 'Meus pedidos ',
            'pedidos_pagos' => Pedidos::with('itens.itens', 'dadosPagamento')
                ->whereUserId($id)
                ->whereIn('status', [2, 7])
                ->where('tipo_pedido', '<>', 4)
                ->get(),
            'pedidos_concluidos' => Pedidos::with('itens.itens', 'dadosPagamento')
                ->whereUserId($id)
                ->whereStatus(6)
                ->where('tipo_pedido', '<>', 4)
                ->get(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Application|Factory|View
     */
    public function visualizarPedido($user, $id)
    {
        $movimento = Movimentos::whereUserId(Auth::user()->id)->orderBy('id')->get();

        if ($movimento) {
            $movimento = $movimento->last();
        }

        $pedido = Pedidos::with('itens.itens', 'dadosPagamento', 'usuario', 'status')
            ->whereId($id)
            ->whereUserId($user)
            ->first();

        $metodoPagamentoQuery = MetodoPagamento::where('status', 1);

        $tipo = 'Licenças';

        if ($pedido->itens->first()->itens->tipo_pedido_id === 4) {
            $metodoPagamentoQuery->where('usar_deposito', 1);
            $header = 'Nº de referência do seu pedidos:';
            $tipo = 'Depositos';
        } else {
            $metodoPagamentoQuery->where('usar_item', 1);
            $header = 'Nº de referência da sua licença:';
        }

        $metodoPagamento = $metodoPagamentoQuery->OrderBy('order')->get();

        $mensagemBoleto = '';
        $exibeBoleto = false;

        if ($metodoPagamento->where('id', 1)->count() > 0) {
            $limites = limitesBoleto(1, $pedido->dadosPagamento->valor);
            $mensagemBoleto = 'Valor máximo via boleto automático: '.mascaraMoeda($this->sistema->moeda, $limites['limiteboleto'], 2, true);
            $exibeBoleto = $limites['emitirboleto'];
        }

        return view('default.pedidos.visualizar', [
            'title' => 'Pedido #'.$id.' ',
            'dados' => $pedido,
            'tipo' => $tipo,
            'url' => $tipo,
            'empresa' => User::whereHas('roles', function ($query) {$query->where('name', 'user-empresa');})->first(),
            'contasTed' => ContasEmpresa::with('banco')->where('status', 1)->where('recebe_ted', 1)->get(),
            'metodoPagamento' => $metodoPagamento,
            'movimento' => $movimento,
            'mensagemBoleto' => $mensagemBoleto,
            'exibeBoleto' => $exibeBoleto,
            'header' => $header,
        ]);
    }

    /**
     * @param $id
     * @return Factory|View
     */
    public function bonusVisualizar($id)
    {
        $movimento = Movimentos::whereUserId(Auth::user()->id)->orderBy('id')->get();

        if ($movimento) {
            $movimento = $movimento->last();
        }

        return view('default.pedidos.visualizar-outros', [
            'title' => 'Pedido #'.$id.' ',
            'dados' => Pedidos::with('itens.itens', 'dadosPagamento', 'usuario', 'status')->find($id),
            'movimento' => $movimento,
        ]);
    }

    public function visualizarBoleto($pedido_id, $msg)
    {
        if ($msg == 1) {//geração de boleto
            $msg = "swal('Parabéns!', 'Boleto gerado com sucesso!', 'success');";
        } else {
            $msg = '';
        }

        $pedido = Pedidos::with('itens.itens', 'dadosPagamento', 'usuario', 'status')->where('id', $pedido_id)->first();

        return view('default.pedidos.visualizar-boleto', [
            'title'     => 'Pedido #'.$pedido_id.' ',
            'tipo' => $pedido->tipo_pedido == 4 ? 'Depósito' : 'Pedido',
            'tipo2' => $pedido->tipo_pedido == 4 ? 'Depósitos' : 'Pedidos',
            'url' => $pedido->tipo_pedido == 4 ? route('depositos.aguardando.deposito') : route('pedidos.aguardando.conferencia'),
            'dados'     => $pedido,
            'msg' => $msg,
        ]);
    }

    /**
     * @param $user
     * @param $id
     * @return RedirectResponse
     */
    public function cancelarPedido($user, $id)
    {
        try {
            DB::beginTransaction();

            $pedido = Pedidos::whereId($id);
            $pedido->update(['status' => 3]);

            DB::commit();

            flash()->success('Pedido cancelado com sucesso!');

            Log::info('Pedido cancelado:', ['pedido' => $id, 'user ação' => Auth::user()->id]);

            if ($pedido->first()->tipo_pedido === 4) {
                return redirect()->route('depositos.cancelados');
            }

            return redirect()->route('pedido.usuario.pedidos', Auth::user());
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            flash()->error('Desculpe, erro ao cancelar o pedido. Tente novamente, se o erro persistir entre em contato conosco!');

            Log::info('Erro ao cancelar pedido', ['user' => Auth::user()->id]);

            return redirect()->route('pedido.usuario.pedidos', Auth::user());
        }
    }

    /**
     * @param $pedido
     * @return RedirectResponse
     * https://github.com/jenssegers/date
     */
    public function verContrato($pedido)
    {
        try {
            $pedido = Pedidos::with('itens.itens', 'dadosPagamento', 'usuario', 'status')
                ->whereId($pedido)
                ->whereUserId(Auth::user()->id)
                ->firstOrFail();

            if (file_exists(storage_path('app/contratos/'.$pedido->contrato)) && ! is_dir(storage_path('app/contratos/'.$pedido->contrato))) {
                return \Response::make(file_get_contents(storage_path('app/contratos/'.$pedido->contrato)), 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="contrato-de-mutuo-financeiro-n-'.$pedido->id.'-'.str_slug($pedido->itens->first()->name_item, '-').'.pdf"',
                ]);
            }
            throw new ModelNotFoundException('-');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('pedido.usuario.pedidos', Auth::user());
        }
    }

    public function download($id, $nomeArquivo)
    {
        try {
            return response()->download(storage_path('app/comprovantes/'.$nomeArquivo));
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'cod_error' => $e->getCode()]);
        }
    }

    public function novoContrato(AutenticacaoContratacaoRequest $request)
    {
        if ($request->has('aceite')) {
            return self::salvar($request);
        } else {
            flash()->warning('Atenção! O aceite  dos termos é obrigatório!');
            return redirect()->back();
        }
    }

    private function salvar($request)
    {
        try {
            $dados = [];

            $item = Itens::whereId($request->get('item'))->first();

            $acrescimoDependentes = 0;

            $dados['status'] = 1;
            $dados['sen-dependente'] = $request->get('sen-dependente');
            $dados['valor_total'] = ($request->get('qtd_itens') * $item->valor) + $acrescimoDependentes;
            $dados['user_id'] = Auth::user()->id;
            $dados['tipo_pedido'] = $item->tipo_pedido_id;
            $dados['data_compra'] = Carbon::now();
            $dados['aceite'] = $request->has('aceite') ?? false;

            DB::beginTransaction();

            $pedido = Pedidos::create($dados);

            // adicionar quantidade de pontos

            $dadoItenPedido = [
                'pedido_id' => $pedido->id,
                'item_id' => $item->id,
                'name_item' => $item->name,
                'pontos_binarios' => $item->pontos_binarios,
                'valor_unitario' => $item->valor,
                'valor_total' => ($request->get('qtd_itens') * $item->valor) + $acrescimoDependentes,
                'quantidade' => $request->get('qtd_itens'),

                'quitar_com_bonus' => $item->quitar_com_bonus,
                'potencial_mensal_teto' => $item->potencial_mensal_teto ?? -1,
                'resgate_minimo' => $item->resgate_minimo ?? -1,
                'total_dias_contrato' => $item->contrato ?? -1,
                'total_meses_contrato' => $item->meses ?? -1,
                'resgate_minimo_automatico' => $item->resgate_minimo_automatico,
                'finaliza_contrato_automatico' => $item->finaliza_contrato_automatico,
                'dias_carencia_transferencia' => $item->dias_carencia_transferencia,
                'dias_carencia_saque' => $item->dias_carencia_saque,
                'modo_recontratacao_automatica' => $request["modo_recontratacao_automatica_{$item->id}"] ?? 0,
            ];

            ItensPedido::create($dadoItenPedido);

            DadosPagamento::create([
                'valor'           => ($request->get('qtd_itens') * $item->valor) + $acrescimoDependentes,
                'status'          => 0,
                'pedido_id'       => $pedido->id,
                'data_vencimento' => Carbon::now()->addWeekday(5),
            ]);

            DB::commit();

            Log::info('Pedido Cadastrado', $request->except('_token'));

            //$valorTotalPedido = ($request->get('qtd_itens') * $item->valor) + $acrescimoDependentes;

            //if ($valorTotalPedido > 0 && $item->tipo_pedido_id != 3) { /*é um pedido covencional, tipo 3 é agente*/

            // (new Pagamentos($pedido))->pagarComSaldo();

            //flash()->success('Parabéns por sua contratação, você escolheu um excelente plano!');

            //return redirect()->route('pedido.usuario.pedidos', Auth::user());
            // }
            /*é um pedido para se tornar agente*/
            return redirect()->route('pedido.usuario.pedido', [\Auth::user()->id, $pedido->id]);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            flash()->error('Desculpe, erro ao realizar o pedido. Tente novamente, se o erro persistir entre em contato conosco!');

            Log::info('Erro ao cadastrar pedido', ['user' => Auth::user()->id]);

            return redirect()->route('pedido.create');
        }
    }

    public function modoRecontratacao(AutenticacaoRecontratacaoRequest $request)
    {
        $dados = $request->all();

        $pedido = Pedidos::find($dados['pedido_id']);
        $pedidoItens = $pedido->itens->first();
        $pedidoItens->modo_recontratacao_automatica = $dados["modo_recontratacao_automatica_{$pedidoItens->id}"];
        $pedidoItens->save();

        return redirect()->back();
    }

    public function usuarioPedidosAguardandoPagamento()
    {
        return view('default.pedidos.pedidos', [
            'title' => 'Pedidos - Aguardando pagamento',
            'usuarioDepositosAguardandoDeposito' => Pedidos::with('itens.itens', 'dadosPagamento')
                ->whereUserId(Auth::user()->id)
                ->whereStatus(1)
                ->pedidos()
                ->get(),
        ]);
    }

    public function usuarioPedidosAguardandoConferencia()
    {
        return view('default.pedidos.pedidos', [
            'title' => 'Pedidos - Aguardando conferência',
            'usuarioDepositosAguardandoConferencia' => Pedidos::with('itens.itens', 'dadosPagamento')
                ->whereUserId(Auth::user()->id)
                ->whereStatus(4)
                ->pedidos()
                ->get(),
        ]);
    }

    public function usuarioPedidosConfirmados()
    {
        return view('default.pedidos.pedidos', [
            'title' => 'Pedidos - Confirmados',
            'usuarioDepositosConfirmados' => Pedidos::with('itens.itens', 'dadosPagamento')
                ->whereUserId(Auth::user()->id)
                ->whereStatus(2)
                ->pedidos()
                ->get(),
        ]);
    }

    public function usuarioPedidosCancelados()
    {
        return view('default.pedidos.pedidos', [
            'title' => 'Pedidos - Cancelados',
            'usuarioDepositosCancelados' => Pedidos::with('itens.itens', 'dadosPagamento')
                ->whereUserId(Auth::user()->id)
                ->whereStatus(3)
                ->pedidos()
                ->get(),
        ]);
    }
}
