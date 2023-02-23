<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use App\Models\Itens;
use App\Models\Pedidos;
use App\Models\Sistema;
use Illuminate\Http\Request;
use App\Models\PedidosMovimentos;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;

/**
 * Class PedidosMovimentosController.
 */
class PedidosMovimentosController extends Controller
{
    /**
     * @var
     */
    protected $sistema;

    /**
     * PedidosMovimentosController constructor.
     */
    public function __construct()
    {
        $this->sistema = Sistema::findOrFail(1);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexOld()
    {
        $extrato = [];

        foreach (Itens::where('ativo', 1)->where('ativo_qtd', 1)->get() as $item) {
            $extrato[$item->id] = [
                'name_item' => $item->name,
                'cor_item' => $item->cor_item,
                'total' => 0,
            ];
        }

        foreach (Pedidos::where('user_id', Auth::user()->id)->whereIn('status', [2, 6, 7])->where('pedidos.tipo_pedido', '<>', 3)->with('itens')->orderBy('data_compra', 'desc')->get() as $pedido) {
            $pedidos_movimento = PedidosMovimentos::where('pedido_id', $pedido->id)->whereNotIn('operacao_id', [26, 35])->sum('valor_manipulado');
            $totalMovimento = 0;
            $totalMovimento += $pedidos_movimento;

            $itemPedido = $pedido->itens->first();

            $extrato[$itemPedido->item_id]['itens'][] = [
                'id_pedido' => $pedido->id,
                'valor' => $pedido->valor_total,
                'data' => $pedido->data_compra,
                'status' => $pedido->status,
                'total' => $totalMovimento - $pedido->movimentosInterno()->where('operacao_id', 26)->sum('valor_manipulado'),
                'total_movimento' => $totalMovimento,
            ];

            $extrato[$itemPedido->item_id]['total'] += $pedido->valor_total + $totalMovimento;
        }

        foreach ($extrato as $key => $ex) {
            if (! isset($ex['itens'])) {
                unset($extrato[$key]);
            }
        }

        return view('default.pedidos_movimentos.index', [
            'extrato' => $extrato,
        ]);
    }

    public function index()
    {
        $extrato = [];
        $total = 0;
        $depositado = 0;
        $capitalizado = 0;

        foreach (Itens::where('ativo', 1)->where('ativo_qtd', 1)->get() as $item) {
            $extrato[$item->id] = [
                'name_item' => $item->name,
                'cor_item' => $item->cor_item,
                'qtd_depositos' => 0,
                'total' => 0,
                'depositado' => 0,
                'capitalizado' => 0,
            ];
        }

        foreach (Pedidos::where('user_id', Auth::user()->id)->whereIn('status', [2, 7])->whereNotIn('pedidos.tipo_pedido', [3, 4])->with('itens')->orderBy('data_compra', 'desc')->get() as $pedido) {
            $pedidos_movimento = PedidosMovimentos::where('pedido_id', $pedido->id)->whereNotIn('operacao_id', [26, 35])->sum('valor_manipulado');
            $totalMovimento = 0;
            $totalMovimento += $pedidos_movimento;

            $itemPedido = $pedido->itens->first();

            $extrato[$itemPedido->item_id]['itens'][] = [
                'id_pedido' => $pedido->id,
                'valor' => $pedido->valor_total,
            ];

            $consultorQuitarComBonus = false;

            if ($itemPedido->item->quitar_com_bonus && Auth::user()->titulo->habilita_rede) {
                $consultorQuitarComBonus = true;
            }

            $extrato[$itemPedido->item_id]['total'] += $totalMovimento + ($consultorQuitarComBonus ? 0 : $pedido->valor_total);
            $extrato[$itemPedido->item_id]['depositado'] += $pedido->valor_total;
            $extrato[$itemPedido->item_id]['capitalizado'] += $totalMovimento;
        }

        foreach ($extrato as $key => $ex) {
            if (isset($ex['itens'])) {
                $total += $ex['total'];
                $depositado += $ex['depositado'];
                $capitalizado += $ex['capitalizado'];
                continue;
            }
            unset($extrato[$key]);
        }

        return view('default.pedidos_movimentos.index-new', compact('extrato', 'total', 'depositado', 'capitalizado'));
    }

    public function extratoItem($item)
    {
        $pedidos = Pedidos::where('user_id', Auth::user()->id)
            ->whereIn('status', [2, 7])
            ->whereNotIn('pedidos.tipo_pedido', [3, 4])
            ->whereHas('itens', function ($query) use ($item) {
                $query->whereHas('item', function ($query) use ($item) {
                    $query->where('id', $item);
                });
            })
            ->with('itens')
            ->orderBy('data_compra', 'desc')
            ->get();

        $totalMovimento = 0;
        $totalDeposito = 0;

        foreach ($pedidos as $pedido) {
            $pedidos_movimento = $pedido->movimentosInterno()->whereNotIn('operacao_id', [26, 35])->sum('valor_manipulado');

            $totalMovimento += $pedidos_movimento;
            $totalDeposito += $pedido->valor_total;
            $pedido->movimento_total = $pedidos_movimento;
        }

        $item = Itens::findOrFail($item);

        return view('default.pedidos_movimentos.item', compact('pedidos', 'totalDeposito', 'totalMovimento', 'item'));
    }

    public function extratoPedido($pedido, $item)
    {
        $pedido = Pedidos::where('user_id', Auth::user()->id)
            ->where('id', $pedido)
            ->with('itens')
            ->first();

        $pedido->movimento_total = $pedido->movimentosInterno()->whereNotIn('operacao_id', [26, 35])->sum('valor_manipulado');

        $pedido->percentual_pago = round($pedido->movimento_total * 100 / $pedido->valor_total, 2);

        $pedido->transferido = $pedido->movimentosInterno()->where('operacao_id', 26)->sum('valor_manipulado');

        $item = Itens::findOrFail($item);

        return view('default.pedidos_movimentos.item-interna', compact('pedido', 'item'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getMovimento(Request $request)
    {
        $pm = DB::table('pedidos_movimentos as pm')
            ->select(DB::raw('pm.id, pm.valor_manipulado, pm.saldo, pm.created_at, p.valor_total, pm.descricao, pm.operacao_id'))
            ->leftjoin('pedidos as p', 'p.id', '=', 'pm.pedido_id')
            ->where('pm.pedido_id', $request->get('id'))
            ->where('pm.user_id', Auth::user()->id)
            ->orderBy('pm.id', 'desc');

        $datatables = Datatables::of($pm)->editColumn('valor_manipulado', function ($pm) {
            $valor = mascaraMoeda($this->sistema->moeda, $pm->valor_manipulado, 2, true);

            return $pm->operacao_id == 26 ? '('.$valor.')' : $valor;
        })->editColumn('saldo', function ($pm) {
            $valor = mascaraMoeda($this->sistema->moeda, $pm->saldo, 2, true);

            return $valor;
        })->addColumn('porcentagem', function ($pm) {
            $valor = round(($pm->valor_manipulado / $pm->valor_total) * 100, 2).'%';

            return $pm->operacao_id == 26 ? '('.$valor.')' : $valor;
        })->editColumn('created_at', function ($pm) {
            return Carbon::parse($pm->created_at)->format('d/m/Y');
        });

        return $datatables->make(true);
    }
}
