<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use App\Models\User;
use Log;
use Carbon\Carbon;
use App\Models\Itens;
use App\Models\Pedidos;
use App\Models\Sistema;
use App\Models\Movimentos;
use App\Models\ItensPedido;
use App\Models\ContasEmpresa;
use App\Models\DadosPagamento;
use App\Models\MetodoPagamento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DepositoRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class DepositoController.
 */
class DepositoController extends Controller
{
    private $sistema;

    /**
     * DepositoController constructor.
     */
    public function __construct()
    {
        $this->middleware('manipularOutro', [
            'except' => [
                'create',
                'store',
                'usuarioDepositosAguardandoDeposito',
                'usuarioDepositosAguardandoConferencia',
                'usuarioDepositosConfirmados',
                'usuarioDepositosCancelados',
            ],
        ]);

        $this->sistema = Sistema::findOrFail(1);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if($this->sistema->deposito_is_active) {
            return view('default.deposito.create');
        }

        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepositoRequest $request)
    {
        if($this->sistema->deposito_is_active) {

            try {
                $item = Itens::whereTipoPedidoId(1)->first();

                $dados['status'] = 1;
                $dados['sen-dependente'] = null;
                $dados['valor_total'] = $this->getMoney($request->get('valor'));
                $dados['user_id'] = Auth::user()->id;
                $dados['tipo_pedido'] = $item->tipo_pedido_id;
                $dados['data_compra'] = Carbon::now();

                DB::beginTransaction();

                $pedido = Pedidos::create($dados);

                // adicionar quantidade de pontos
                $dadoItenPedido = [
                    'pedido_id'       => $pedido->id,
                    'item_id'         => $item->id,
                    'name_item'       => $item->name,
                    'pontos_binarios' => $item->pontos_binarios,
                    'valor_unitario'  => $item->valor,
                    'valor_total'     => $this->getMoney($request->get('valor')),
                    'quantidade'      => 1,

                    'quitar_com_bonus'             => $item->quitar_com_bonus,
                    'potencial_mensal_teto'        => $item->potencial_mensal_teto ?? -1,
                    'resgate_minimo'               => $item->resgate_minimo ?? -1,
                    'total_dias_contrato'          => $item->contrato  ?? 3,
                    'total_meses_contrato'         => $item->meses ?? 2,
                    'resgate_minimo_automatico'    => $item->resgate_minimo_automatico ?? -1,
                    'finaliza_contrato_automatico' => $item->finaliza_contrato_automatico,
                    'dias_carencia_saque'          => $item->dias_carencia_saque,
                ];

                ItensPedido::create($dadoItenPedido);

                DadosPagamento::create([
                    'valor'           => $this->getMoney($request->get('valor')),
                    'status'          => 0,
                    'pedido_id'       => $pedido->id,
                    'data_vencimento' => Carbon::now()->addWeekday(5),
                ]);

                DB::commit();

                Log::info('Pedido Cadastrado', $request->except('_token'));

                return redirect()->route('deposito.usuario', ['user' => Auth::user(), 'pedido' => $pedido->id]);
            } catch (ModelNotFoundException $e) {
                flash()->error('Erro ao efetuar depósito, tente novamente com outro valor!');

                Log::info('Erro ao fazer depósito', ['user' => Auth::user()->id]);

                return redirect()->route('default.deposito.create');
            }
        }

        return  redirect()->back();
    }

    public function usuarioDepositosAguardandoDeposito()
    {
        return view('default.deposito.depositos', [
            'title'                        => 'Meus Depósitos - Aguardando depósito',
            'usuarioDepositosAguardandoDeposito'           => Pedidos::with('itens.itens', 'dadosPagamento')
                ->whereUserId(Auth::user()->id)
                ->whereStatus(1)
                ->depositos()
                ->get(),
            ]);
    }

    public function usuarioDepositosAguardandoConferencia()
    {
        return view('default.deposito.depositos', [
            'title'                        => 'Meus Depósitos - Aguardando conferência',
            'usuarioDepositosAguardandoConferencia' => Pedidos::with('itens.itens', 'dadosPagamento')
                ->whereUserId(Auth::user()->id)
                ->whereStatus(4)
                ->depositos()
                ->get(),
        ]);
    }

    public function usuarioDepositosConfirmados()
    {
        return view('default.deposito.depositos', [
            'title'                        => 'Meus Depósitos - Confirmados',
            'usuarioDepositosConfirmados'                => Pedidos::with('itens.itens', 'dadosPagamento')
                ->whereUserId(Auth::user()->id)
                ->whereStatus(2)
                ->depositos()
                ->get(),
        ]);
    }

    public function usuarioDepositosCancelados()
    {
        return view('default.deposito.depositos', [
            'title'                        => 'Meus Depósitos - Confirmados',
            'usuarioDepositosCancelados'           => Pedidos::with('itens.itens', 'dadosPagamento')
                ->whereUserId(Auth::user()->id)
                ->whereStatus(3)
                ->depositos()
                ->get(),
        ]);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function visualizarDeposito($user, $id)
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

        if ($pedido->itens->first()->itens->tipo_pedido_id === 4) {
            $metodoPagamentoQuery->where('usar_deposito', 1);
        } else {
            $metodoPagamentoQuery->where('usar_item', 1);
        }

        $metodoPagamento = $metodoPagamentoQuery->OrderBy('order')->get();

        $mensagemBoleto = '';
        $exibeBoleto = false;

        if ($metodoPagamento->where('id', 1)->count() > 0) {
            $limites = limitesBoleto(1, $pedido->dadosPagamento->valor);
            $mensagemBoleto = 'Valor máximo via boleto automático:  '.mascaraMoeda($this->sistema->moeda, $limites['limiteboleto'], 2, true).'. Valores maiores via TED';
            $exibeBoleto = $limites['emitirboleto'];
        }

        return view('default.deposito.visualizar', [
            'title' => 'Pedido #'.$id.' ',
            'dados' => $pedido,
            'empresa' => User::whereHas('roles', function ($query) {$query->where('name', 'user-empresa');})->first(),
            'contasTed' => ContasEmpresa::with('banco')->where('status', 1)->where('recebe_ted', 1)->get(),
            'metodoPagamento' => $metodoPagamento,
            'movimento' => $movimento,
            'mensagemBoleto' => $mensagemBoleto,
            'exibeBoleto' => $exibeBoleto,
        ]);
    }
}
