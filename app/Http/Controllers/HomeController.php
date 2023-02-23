<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Videos;
use App\Models\Pedidos;
use App\Models\Sistema;
use App\Models\Download;
use App\Models\Movimentos;
use App\Models\DadosPagamento;
use App\Models\Transferencias;
use App\Models\PedidosMovimentos;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected $sistema;

    public function __construct()
    {
        $this->sistema = Sistema::findOrFail(1);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indiceEconomico()
    {
        return view('default.home.indice-economico');
    }

    public function index($layout = '')
    {
        if (Auth::user()->can(['master', 'admin'])) {
            return self::indexAdmin();
        }

        $objMovimento = new Movimentos();

        $contrato = Auth::user()->contratoVigente();

        if ($contrato) {
            $contrato->load(['item' => function ($query) {
                $query->select('id', 'tipo_pacote');
            }]);
        }

        $depositos = Pedidos::with(['movimentosInterno' => function ($query) {
            $inicio = Carbon::now()->subDays(15);
            $fim = Carbon::now();
            $query->whereDate('created_at', '>=', $inicio)->whereDate('created_at', '<=', $fim);
        }])->with('itens.itens', 'dadosPagamento')
            ->where(function ($query) {
                $query->where('status', 7)->orWhere('status', 2);
            })->where('user_id', Auth::user()->id)
            ->whereNotIn('tipo_pedido', [3, 4])
            ->get();

        $depositoTotal = 10;
        $capitalizacaoTotal = 0;
        $chartPieDepositos = [];

        self::calcDepositos($depositos, $chartPieDepositos, $capitalizacaoTotal, $depositoTotal);

        $chart = self::calcCaloresChart($chartPieDepositos, $depositoTotal);

        $usuario = User::find(Auth::user()->id);

        $timeLine = self::timeLine();

        $royaltiesPagos = Auth::user()->movimentos->where('operacao_id', 31)->sum('valor_manipulado');

        return view('default.home'.$layout, [
            'usuario' => $usuario,
            'totalGanhos' => $objMovimento->totalGanhos(Auth::user()->id) - $royaltiesPagos,
            'contrato' => $contrato,
            'depositos' => 0, //$depositoTotal,
            'labels' => json_encode($chart['labels']),
            'colors' => json_encode($chart['colors']),
            'valores' => json_encode($chart['valores']),
            'porcentagens' => json_encode($chart['porcentagens']),
            'capitalizacao' => $capitalizacaoTotal,
            'timeLine' => $timeLine,
        ]);
    }

    private function indexAdmin()
    {
        $transferencias = Transferencias::whereStatus(1)->orderBy('created_at', 'desc')->take(4)->get();
        $transfEfetivas = Transferencias::whereStatus(2)->orderBy('dt_solicitacao', 'desc')->take(4)->get();
        $depositoAguardandoConfirmacao = Pedidos::whereHas('itens', function ($query) {
            $query->where('item_id', 8);
        })
            ->whereStatus(4)
            ->orderBy('updated_at', 'desc')
            ->take(4)
            ->get();

        $ultimoContratos = Pedidos::whereHas('itens', function ($query) {
            $query->whereNotIn('item_id', [7, 8]);
        })
            ->whereStatus(2)
            ->orderBy('updated_at', 'desc')
            ->take(4)
            ->get();

        $dataFim = Carbon::now();
        $dataInicio = Carbon::now()->subDays(15);
        $ultimoDepositos = DadosPagamento::whereDate('data_pagamento_efetivo', '>=', $dataInicio)
            ->whereDate('data_pagamento_efetivo', '<=', $dataFim)
        ->whereHas('pedido', function ($query) {
            $query->whereStatus(2)
                ->whereHas('itens', function ($query) {
                    $query->where('item_id', 8);
                });
        })
            ->orderBy('data_pagamento_efetivo', 'desc')
            ->get([
                'id',
                'data_pagamento_efetivo',
                'pedido_id',
                'valor',
                'valor',
                'valor_efetivo',
                'status',
            ]);

        $dadosChartDeposito = self::depositosChart($ultimoDepositos);

        return view('default.home-admin', compact('transferencias', 'transfEfetivas', 'depositoAguardandoConfirmacao', 'ultimoContratos', 'dadosChartDeposito'));
    }

    /**
     * @param Collection $depositos
     * @return json
     */
    private function depositosChart(Collection $depositos)
    {
        $collection = $depositos->map(function ($item, $key) {
            $pivot = $item->data_pagamento_efetivo->format('Y-m-d');
            $pivotBR = $item->data_pagamento_efetivo->format('d/m/Y');

            $item->dt = $pivot;
            $item->dt_br = $pivotBR;

            return $item;
        });

        $collection = $collection->sortBy('data_pagamento_efetivo')->groupBy('dt');
        $dadosChart = [];

        foreach ($collection as $key => $dp) {
            $data = $dp->first()->dt_br;
            $dadosChart[] = ['dia' => $key, 'depositos' => round($dp->sum('valor'), 2)];
        }

        return json_encode($dadosChart);
    }

    /**
     * @param $depositos
     * @param $chartPieDepositos
     * @param $capitalizacaoTotal
     * @param $depositoTotal
     */
    private function calcDepositos($depositos, &$chartPieDepositos, &$capitalizacaoTotal, &$depositoTotal)
    {
        foreach ($depositos as $pedido) {
            $itemPedido = $pedido->getRelation('itens')->first();
            $item = $itemPedido->getRelation('itens');

            $chartPieDepositos[$item->id]['label'] = $item->name;
            $chartPieDepositos[$item->id]['color'] = $item->cor_item;
            $chartPieDepositos[$item->id]['valor'] = isset($chartPieDepositos[$item->id]['valor']) ? $chartPieDepositos[$item->id]['valor'] + $itemPedido->valor_total : $itemPedido->valor_total;

            $ultimoMovimento = $pedido->ultimoMovimentosInterno();

            if ($ultimoMovimento) {
                $capitalizacaoTotal += $ultimoMovimento->saldo;
            }

            $depositoTotal += $pedido->getRelation('dadosPagamento')->valor;
        }
    }

    /**
     * @param $chartPieDepositos
     * @param $depositoTotal
     * @return array
     */
    private function calcCaloresChart($chartPieDepositos, $depositoTotal):array
    {
        $chart = [
            'labels' => [],
            'colors' => [],
            'valores' => [],
            'porcentagens' => [],
        ];

        //separa as informações para o grafico
        foreach ($chartPieDepositos as $key => $chartPieDeposito) {
            $chartPieDepositos[$key]['data'] = round(($chartPieDeposito['valor'] * 100) / $depositoTotal, 2);

            $chart['labels'][] = $chartPieDepositos[$key]['label'];
            $chart['colors'][] = $chartPieDepositos[$key]['color'];
            $chart['porcentagens'][] = round(($chartPieDeposito['valor'] * 100) / $depositoTotal, 2);
            $chart['valores'][] = $chartPieDepositos[$key]['label'].': '.mascaraMoeda($this->sistema->moeda, $chartPieDeposito['valor'], 2, true);
        }

        return $chart;
    }

    private function timeLine()
    {
        $collection = new Collection();

        $downloads = Download::orderBy('created_at', 'desc')
            ->take(3);

        if (! \Auth::user()->titulo->habilita_rede) {
            $downloads = $downloads->whereHas('tipo', function ($query) {
                $query->where('habilita_rede', 0);
            });
        }

        $collection = $collection->merge($downloads->get());

        $collection = $collection
        ->merge(Videos::whereHas('videosTitulos', function ($query) {
            $query->where('titulo_id', Auth::user()->titulo_id);
        })
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get());

        $collection = $collection
            ->merge(PedidosMovimentos::whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))
                ->where('user_id', Auth::user()->id)
                ->where('operacao_id', 7)
                ->get());

        $collection = $collection->map(function ($item, $key) {
            $pivot = $item->created_at->format('d/m/Y');
            $item->dt = $pivot;

            return $item;
        });

        return $collection->sortByDesc('created_at')->groupBy('dt');
    }
}
