<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use App\Models\Sistema;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;

class PedidoController extends Controller
{
    private $sistema;

    public function __construct()
    {
        $this->middleware('permission:master|admin');
        $this->sistema = Sistema::findOrFail(1);
    }

    public function depositosPagos()
    {
        $pedidos = DB::table('pedidos as p')
            ->leftjoin('itens_pedido as ip', 'ip.pedido_id', '=', 'p.id')
            ->leftjoin('itens as i', 'i.id', '=', 'ip.item_id')
            ->leftjoin('dados_pagamento as dp', 'dp.pedido_id', '=', 'p.id')
            ->leftjoin('metodo_pagamento as mp', 'mp.id', '=', 'dp.metodo_pagamento_id')
            ->leftjoin('users as u', 'u.id', '=', 'p.user_id')
            ->where('p.status', '=', 2)
            ->whereIn('i.tipo_pedido_id', [4])
            ->select([
                'p.id',
                'i.name as item',
                'u.name as nome',
                'u.username',
                'p.valor_total',
                'p.data_compra',
                'dp.data_pagamento',
                'dp.path_comprovante_ted',
                'mp.name as metodo_pagamento',
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
            });
        }

        return $retorno->make(true);
    }

    public function contratosConsultor()
    {
        $pedidos = DB::table('pedidos as p')
            ->leftjoin('itens_pedido as ip', 'ip.pedido_id', '=', 'p.id')
            ->leftjoin('itens as i', 'i.id', '=', 'ip.item_id')
            ->leftjoin('dados_pagamento as dp', 'dp.pedido_id', '=', 'p.id')
            ->leftjoin('users as u', 'u.id', '=', 'p.user_id')
            ->where('p.status', '=', 2)
            ->whereIn('i.tipo_pedido', [3])
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
            });
        }

        return $retorno->make(true);
    }

    public function contratosCapitalAtivos()
    {
        return self::contratosCapital(2, false, 4);
    }

    public function contratosCapitalFinalizados()
    {
        return self::contratosCapital(6, false, 4);
    }

    private function contratosCapital($status, $tipo = false, $naoTrazer = false)
    {
        $pedidos = DB::table('pedidos as p')
            ->leftjoin('itens_pedido as ip', 'ip.pedido_id', '=', 'p.id')
            ->leftjoin('itens as i', 'i.id', '=', 'ip.item_id')
            ->leftjoin('dados_pagamento as dp', 'dp.pedido_id', '=', 'p.id')
            ->leftjoin('users as u', 'u.id', '=', 'p.user_id')
            ->where('p.status', '=', $status)
            ->whereNotIn('i.id', [7, 8])
            ->select([
                'p.id',
                'i.name as item',
                'u.name as nome',
                'u.username',
                'p.valor_total',
                'p.data_compra',
                'dp.data_pagamento',
                'p.data_fim',
                'dp.path_comprovante_ted',
            ]);

        if ($tipo) {
            $pedidos->where('i.tipo_pedido_id', $tipo);
        }

        if ($naoTrazer) {
            $pedidos->where('i.tipo_pedido_id', '<>', $naoTrazer);
        }

        $retorno = Datatables::of($pedidos)
            ->orderColumn('id', 'id $1')
            ->editColumn('data_compra', function ($pedidos) {
                return $pedidos->data_compra ? with(new Carbon($pedidos->data_compra))->format('d/m/Y H:i:s') : '';
            })
            ->editColumn('data_fim', function ($pedidos) {
                return $pedidos->data_fim ? with(new Carbon($pedidos->data_fim))->format('d/m/Y H:i:s') : '';
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
                            <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"> </span> Visualizar
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
            });
        }

        return $retorno->make(true);
    }
}
