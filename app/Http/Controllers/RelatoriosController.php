<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Sistema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Saude\Faturamento\Services\ContratosRelatorioService;
use App\Saude\Faturamento\Services\FaturamentoRelatorioService;

class RelatoriosController extends Controller
{
    private $sistema;

    public function __construct()
    {
        $this->middleware('permission:master|admin');
        $this->sistema = Sistema::findOrFail(1);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pagamentos()
    {
        $dadosPagamentos = DB::table('users')
            ->join('movimentos', 'users.id', '=', 'movimentos.user_id')
            ->join('dados_bancarios', 'users.id', '=', 'dados_bancarios.user_id')
            ->join('banco', 'banco.id', '=', 'dados_bancarios.banco_id')
            ->where('dados_bancarios.receber_bonus', '=', 1)
            ->where('movimentos.saldo', '>=', 100)
            ->whereRaw('movimentos.created_at = (select max(created_at)
                                                        from movimentos as ms
                                                        where movimentos.user_id = ms.user_id
                                                        order by created_at desc
                                                        limit 1)')
            ->select(
                'users.id as user_id',
                'users.name',
                'users.username',
                'users.cpf',
                'banco.codigo as banco_codigo',
                'banco.nome as banco',
                'dados_bancarios.agencia',
                'dados_bancarios.agencia_digito',
                'dados_bancarios.conta',
                'dados_bancarios.conta_digito',
                'movimentos.saldo',
                'dados_bancarios.tipo_conta'
            )
            ->get();

        return view('default.relatorios.pagamentos', [
            'dados' => $dadosPagamentos,
        ]);
    }

    public function user()
    {
        return view('default.relatorios.usuarios', []);
    }

    public function faturamento()
    {
        return view('default.relatorios.faturamento', []);
    }

    public function contratos()
    {
        return view('default.relatorios.contratos', []);
    }

    public function consultor()
    {
        return view('default.relatorios.consultor', []);
    }

    public function inadimplentes()
    {
        return view('default.relatorios.inadimplentes', []);
    }

    public function relatorioUser(Request $request)
    {
        try {
            // Retrieve any filters
            $sortBy = $request->input('sort_by');
            $inicio = implode('-', array_reverse(explode('/', $request->input('inicio'))));
            $fim = implode('-', array_reverse(explode('/', $request->input('fim'))));
            // Report title
            $title = 'Relatório de usuários';

            // For displaying filters description on header
            $meta = [
                'Periodo' => strlen($inicio) > 0 ? $request->input('inicio').' a '.$request->input('fim') : Carbon::now()->format('d/m/Y'),
                'Filtro'  => 'Status',
            ];

            $queryBuilder = User::select(['id', 'name', 'telefone', 'celular', 'email', 'codigo as contrato', 'status', 'cpf', 'data_nasc', 'created_at'])
                ->whereIn('status', explode(',', $sortBy))
                ->orderBy('name');

            if (strlen($inicio) > 0) {
                $queryBuilder->whereDate('created_at', '>=', $inicio)
                    ->whereDate('created_at', '<=', $fim);
            }

            // Set Column to be displayed
            $columns = [
                'Nome'          => 'name',
                'CPF'           => 'cpf',
                'D.Nascimento'  => 'data_nasc',
                'E-mail'        => function ($resul) {
                    return strtolower($resul->email);
                },
                'Telefone',
                'Celular',
                'Registrado em' => 'created_at',
                'Status'        => function ($result) { // You can do if statement or any action do you want inside this closure
                    return ($result->status == 0) ? 'Inativo' : 'Ativo';
                },
            ];

            /*
                Generate Report with flexibility to manipulate column class even manipulate column value (using Carbon, etc).

                - of()         : Init the title, meta (filters description to show), query, column (to be shown)
                - editColumn() : To Change column class or manipulate its data for displaying to report
                - editColumns(): Mass edit column
                - showTotal()  : Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
                - groupBy()    : Show total of value on specific group. Used with showTotal() enabled.
                - limit()      : Limit record to be showed
                - make()       : Will producing DomPDF / SnappyPdf instance so you could do any other DomPDF / snappyPdf method such as stream() or download()
            */

            if ($request->get('tipo') == 1) {
                $relatorio = \PdfReport::of($title, $meta, $queryBuilder, $columns);
            } else {
                $relatorio = \ExcelReport::of($title, $meta, $queryBuilder, $columns);
            }

            $relatorio->editColumn('created_at', [
                'displayAs' => function ($result) {
                    return $result->created_at->format('d M Y');
                },
            ])
                ->editColumns(['Status'], [
                    'class' => 'right bold',
                ])
                ->setCss([
                    'tr' => 'page-break-inside: avoid;',
                ]);

            if ($request->get('tipo') == 1) {
                $relatorio->setOrientation('landscape');

                return $relatorio->stream();
            } else {
                return $relatorio->download('Relatorio de usuarios - '.date('d/m/Y'));
            }
        } catch (ModelNotFoundException $e) {
            dd('deu ruim - '.$e->getMessage());
        }
    }

    public function relatorioFaturamento(Request $request)
    {
        $faturamento = new FaturamentoRelatorioService($request->all());

        return $faturamento->gerar();
    }

    public function relatorioContratos(Request $request)
    {
        $contratos = new ContratosRelatorioService($request->all());

        return $contratos->gerar();
    }

    public function relatorioConsultor(Request $request)
    {
        try {
            // Retrieve any filters
            $sortBy = $request->input('sort_by');
            $consultor = $request->input('consultor');
            $inicio = implode('-', array_reverse(explode('/', $request->input('inicio'))));
            $fim = implode('-', array_reverse(explode('/', $request->input('fim'))));

            $title = 'Relatório de Bonificações';

            // For displaying filters description on header
            $meta = [
                'Periodo' => $request->input('inicio').' a '.$request->input('fim'),
                'Filtro'  => 'Data',
            ];

            if ($sortBy > 0) {
                $meta['Filtro'] .= ', Tipo de bonificação';
            }

            if ($consultor > 0) {
                $meta['Filtro'] .= ', '.User::select('name')->find($consultor)->name;
            }

            if ($request->get('tipo') == 2) {
                if (in_array($sortBy, [1, 20])) {
                    $queryBuilder = DB::table('pedidos as p')
                        ->leftJoin('contratos as c', 'c.pedido_id', '=', 'p.id')
                        ->Join('itens as i', 'i.id', '=', 'c.item_id')
                        ->Join('dados_pagamento as dp', 'dp.pedido_id', '=', 'p.id')
                        ->Join('users as u', 'u.id', '=', 'p.user_id')
                        ->leftJoin('boletos as b', 'b.id', '=', 'p.boleto_id')
                        ->Join('metodo_pagamento as mp', 'mp.id', '=', 'dp.metodo_pagamento_id')
                        //->join('movimentos as mv', 'mv.referencia', '=', 'p.id')
                        ->join('movimentos as mv', function ($join) {
                            $join->on('mv.referencia', '=', 'p.id')
                                ->orOn('mv.pedido_id', '=', 'p.id');
                        })
                        ->whereNotIn('mv.operacao_id', [22, 23])
                        ->where('mv.operacao_id', '=', $sortBy)
                        ->join('users as uc', 'uc.id', '=', 'mv.user_id')
                        ->whereDate('dp.data_pagamento', '>=', $inicio)
                        ->whereDate('dp.data_pagamento', '<=', $fim)
                        ->where('p.status', '=', 2)
                        ->select([
                            DB::raw('DISTINCT mv.id'),
                            DB::raw('dp.data_pagamento as "dt"'),
                            DB::raw('mv.valor_manipulado as "valor"'),
                            DB::raw('u.name as "user"'),
                            DB::raw('uc.name as "consultor"'),
                            DB::raw('mp.name as "MetodoPagamento"'),
                            DB::raw('b.nosso_numero as "boleto"'),
                            DB::raw('mv.operacao_id as "tipo"'),
                            DB::raw('"1" as "parcela"'),
                            DB::raw('i.name as "plano"'),
                            DB::raw('u.codigo as "contrato"'),
                        ]);
                } elseif (in_array($sortBy, [17, 18, 19])) {
                    $queryBuilder = DB::table('mensalidades as m')
                        ->Join('metodo_pagamento as mp', 'mp.id', '=', 'm.metodo_pagamento_id')
                        ->Join('users as u', 'u.id', '=', 'm.user_id')
                        ->Join('boletos as b', 'b.id', '=', 'm.boleto_id')
                        ->Join('contratos as c', 'c.id', '=', 'm.contrato_id')
                        ->Join('itens as i', 'i.id', '=', 'c.item_id')
                        //->join('movimentos as mv', 'mv.referencia', '=', 'm.id')
                        ->join('movimentos as mv', function ($join) {
                            $join->on('mv.referencia', '=', 'm.id')
                                ->orOn('mv.mensalidade_id', '=', 'm.id');
                        })
                        ->where('mv.operacao_id', '=', $sortBy)
                        ->join('users as uc', 'uc.id', '=', 'mv.user_id')
                        ->whereDate('m.dt_baixa', '>=', $inicio)
                        ->whereDate('m.dt_baixa', '<=', $fim)
                        ->where('m.status', '=', 4)
                        //->whereNull('u.deleted_at')
                        ->select([
                            DB::raw('m.dt_baixa as "dt"'),
                            DB::raw('mv.valor_manipulado as "valor"'),
                            DB::raw('u.name as "user"'),
                            DB::raw('uc.name as "consultor"'),
                            DB::raw('mp.name as "MetodoPagamento"'),
                            DB::raw('b.nosso_numero as "boleto"'),
                            DB::raw('mv.operacao_id as "tipo"'),
                            DB::raw('m.parcela as "parcela"'),
                            DB::raw('i.name as "plano"'),
                            DB::raw('u.codigo as "contrato"'),
                        ]);
                } elseif (22 == $sortBy) {
                    $queryBuilder = DB::table('pedidos as p')
                        ->leftJoin('contratos as c', 'c.pedido_id', '=', 'p.id')
                        ->Join('itens as i', 'i.id', '=', 'c.item_id')
                        ->Join('dados_pagamento as dp', 'dp.pedido_id', '=', 'p.id')
                        ->Join('users as u', 'u.id', '=', 'p.user_id')
                        ->leftJoin('boletos as b', 'b.id', '=', 'p.boleto_id')
                        ->Join('metodo_pagamento as mp', 'mp.id', '=', 'dp.metodo_pagamento_id')
                        ->join('movimentos as mv', function ($join) {
                            $join->on('mv.referencia', '=', 'p.id')
                                ->orOn('mv.pedido_id', '=', 'p.id');
                        })
                        ->where('mv.operacao_id', '=', $sortBy)
                        ->where('mv.operacao_id', '=', 22)
                        ->join('users as uc', 'uc.id', '=', 'mv.user_id')
                        ->whereDate('mv.created_at', '>=', $inicio)
                        ->whereDate('mv.created_at', '<=', $fim)
                        ->where('p.status', '=', 2)
                        ->select([
                            DB::raw('mv.created_at as "dt"'),
                            DB::raw('mv.valor_manipulado as "valor"'),
                            DB::raw('u.name as "user"'),
                            DB::raw('uc.name as "consultor"'),
                            DB::raw('mp.name as "MetodoPagamento"'),
                            DB::raw('b.nosso_numero as "boleto"'),
                            DB::raw('mv.operacao_id as "tipo"'),
                            DB::raw('"1" as "parcela"'),
                            DB::raw('i.name as "plano"'),
                            DB::raw('u.codigo as "contrato"'),
                        ]);
                } else {
                    $pedidos = DB::table('pedidos as p')
                        ->leftJoin('contratos as c', 'c.pedido_id', '=', 'p.id')
                        ->Join('itens as i', 'i.id', '=', 'c.item_id')
                        ->Join('dados_pagamento as dp', 'dp.pedido_id', '=', 'p.id')
                        ->Join('users as u', 'u.id', '=', 'p.user_id')
                        ->leftJoin('boletos as b', 'b.id', '=', 'p.boleto_id')
                        ->Join('metodo_pagamento as mp', 'mp.id', '=', 'dp.metodo_pagamento_id')
                        //->join('movimentos as mv', 'mv.referencia', '=', 'p.id')
                        ->join('movimentos as mv', function ($join) {
                            $join->on('mv.referencia', '=', 'p.id')
                                ->orOn('mv.pedido_id', '=', 'p.id');
                        })
                        ->whereIn('mv.operacao_id', [1, 20])
                        ->join('users as uc', 'uc.id', '=', 'mv.user_id')
                        ->whereDate('dp.data_pagamento', '>=', $inicio)
                        ->whereDate('dp.data_pagamento', '<=', $fim)
                        ->where('p.status', '=', 2)
                        ->select([
                            DB::raw('DISTINCT mv.id'),
                            DB::raw('dp.data_pagamento as "dt"'),
                            DB::raw('mv.valor_manipulado as "valor"'),
                            DB::raw('u.name as "user"'),
                            DB::raw('uc.name as "consultor"'),
                            DB::raw('mp.name as "MetodoPagamento"'),
                            DB::raw('b.nosso_numero as "boleto"'),
                            DB::raw('mv.operacao_id as "tipo"'),
                            DB::raw('"1" as "parcela"'),
                            DB::raw('i.name as "plano"'),
                            DB::raw('u.codigo as "contrato"'),
                        ]);

                    $estorno = DB::table('pedidos as p')
                        ->leftJoin('contratos as c', 'c.pedido_id', '=', 'p.id')
                        ->Join('itens as i', 'i.id', '=', 'c.item_id')
                        ->Join('dados_pagamento as dp', 'dp.pedido_id', '=', 'p.id')
                        ->Join('users as u', 'u.id', '=', 'p.user_id')
                        ->leftJoin('boletos as b', 'b.id', '=', 'p.boleto_id')
                        ->Join('metodo_pagamento as mp', 'mp.id', '=', 'dp.metodo_pagamento_id')
                        ->join('movimentos as mv', function ($join) {
                            $join->on('mv.referencia', '=', 'p.id')
                                ->orOn('mv.pedido_id', '=', 'p.id');
                        })
                        ->where('mv.operacao_id', '=', 22)
                        ->join('users as uc', 'uc.id', '=', 'mv.user_id')
                        ->whereDate('mv.created_at', '>=', $inicio)
                        ->whereDate('mv.created_at', '<=', $fim)
                        ->where('p.status', '=', 2)
                        ->select([
                            DB::raw('DISTINCT mv.id'),
                            DB::raw('mv.created_at as "dt"'),
                            DB::raw('mv.valor_manipulado as "valor"'),
                            DB::raw('u.name as "user"'),
                            DB::raw('uc.name as "consultor"'),
                            DB::raw('mp.name as "MetodoPagamento"'),
                            DB::raw('b.nosso_numero as "boleto"'),
                            DB::raw('mv.operacao_id as "tipo"'),
                            DB::raw('"1" as "parcela"'),
                            DB::raw('i.name as "plano"'),
                            DB::raw('u.codigo as "contrato"'),
                        ]);

                    if ($consultor > 0) {
                        $pedidos->where('uc.id', $consultor);
                        $estorno->where('uc.id', $consultor);
                    }

                    $queryBuilder = DB::table('mensalidades as m')
                        ->union($pedidos)
                        ->union($estorno)
                        ->Join('metodo_pagamento as mp', 'mp.id', '=', 'm.metodo_pagamento_id')
                        ->Join('users as u', 'u.id', '=', 'm.user_id')
                        ->Join('boletos as b', 'b.id', '=', 'm.boleto_id')
                        ->Join('contratos as c', 'c.id', '=', 'm.contrato_id')
                        ->Join('itens as i', 'i.id', '=', 'c.item_id')
                        //->join('movimentos as mv', 'mv.referencia', '=', 'm.id')
                        ->join('movimentos as mv', function ($join) {
                            $join->on('mv.referencia', '=', 'm.id')
                                ->orOn('mv.mensalidade_id', '=', 'm.id');
                        })
                        ->whereIn('mv.operacao_id', [17, 18, 19])
                        ->join('users as uc', 'uc.id', '=', 'mv.user_id')
                        ->whereDate('m.dt_baixa', '>=', $inicio)
                        ->whereDate('m.dt_baixa', '<=', $fim)
                        ->where('m.status', '=', 4)
                        //->whereNull('u.deleted_at')
                        ->select([
                            DB::raw('DISTINCT mv.id'),
                            DB::raw('m.dt_baixa as "dt"'),
                            DB::raw('mv.valor_manipulado as "valor"'),
                            DB::raw('u.name as "user"'),
                            DB::raw('uc.name as "consultor"'),
                            DB::raw('mp.name as "MetodoPagamento"'),
                            DB::raw('b.nosso_numero as "boleto"'),
                            DB::raw('mv.operacao_id as "tipo"'),
                            DB::raw('m.parcela as "parcela"'),
                            DB::raw('i.name as "plano"'),
                            DB::raw('u.codigo as "contrato"'),
                        ]);
                }

                if ($consultor > 0) {
                    $queryBuilder->where('uc.id', $consultor);
                }
            }

            //TODO Select dos campos
            if ($request->get('tipo') == 2) {
                $title .= ' - Analítico';

                $queryBuilder->orderBy('dt', 'ASC');
                $queryBuilder->orderBy('user', 'ASC');
            } else {
                $title .= ' - Sintético';

                //TODO inicio da formação da Query
                $queryBuilder = DB::table('movimentos as m')
                    ->join('operacoes as o', 'o.id', '=', 'm.operacao_id');

                if (in_array($sortBy, [1, 20])) {
                    $queryBuilder
                        ->Join('pedidos as p', 'p.id', '=', 'm.pedido_id')
                        ->Join('dados_pagamento as dp', 'dp.pedido_id', '=', 'p.id')
                        ->whereDate('dp.data_pagamento', '>=', $inicio)
                        ->whereDate('dp.data_pagamento', '<=', $fim)
                        ->whereIn('m.operacao_id', [1, 20])
                        ->where(function ($query) {
                            $query->where('m.referencia', '=', 'p.id')
                                ->orwhere('m.pedido_id', '=', 'p.id');
                        });
                } elseif (in_array($sortBy, [17, 18, 19])) {
                    $queryBuilder
                        ->Join('mensalidades as ms', 'ms.id', '=', 'm.mensalidade_id')
                        ->whereIn('m.operacao_id', [17, 18, 19])
                        ->whereDate('ms.dt_baixa', '>=', $inicio)
                        ->whereDate('ms.dt_baixa', '<=', $fim)
                        ->where(function ($query) {
                            $query->where('m.referencia', '=', 'ms.id')
                                ->orwhere('m.mensalidade_id', '=', 'ms.id');
                        });
                } elseif (in_array($sortBy, [22])) {
                    $queryBuilder
                        ->Join('pedidos as p', 'p.id', '=', 'm.pedido_id')
                        ->Join('dados_pagamento as dp', 'dp.pedido_id', '=', 'p.id')
                        ->whereDate('m.created_at', '>=', $inicio)
                        ->whereDate('m.created_at', '<=', $fim)
                        ->whereIn('m.operacao_id', [22])
                        ->where(function ($query) {
                            $query->where('m.referencia', '=', 'p.id')
                                ->orwhere('m.pedido_id', '=', 'p.id');
                        });
                } else {
                    $pedidos = DB::table('movimentos as m')
                        ->join('operacoes as o', 'o.id', '=', 'm.operacao_id')
                        ->Join('pedidos as p', 'p.id', '=', 'm.pedido_id')
                        ->Join('dados_pagamento as dp', 'dp.pedido_id', '=', 'p.id')
                        ->whereDate('dp.data_pagamento', '>=', $inicio)
                        ->whereDate('dp.data_pagamento', '<=', $fim)
                        ->whereIn('m.operacao_id', [1, 20])
                        ->where(function ($query) {
                            $query->where('m.referencia', '=', 'p.id')
                                ->orwhere('m.pedido_id', '=', 'p.id');
                        })
                        ->select([
                            DB::raw('DISTINCT m.id'),
                            'o.name as operacao',
                            DB::raw('sum(m.valor_manipulado) as valor'),
                        ])
                        ->groupBy('operacao');

                    $estorno = DB::table('movimentos as m')
                        ->join('operacoes as o', 'o.id', '=', 'm.operacao_id')
                        ->Join('pedidos as p', 'p.id', '=', 'm.pedido_id')
                        ->Join('dados_pagamento as dp', 'dp.pedido_id', '=', 'p.id')
                        ->whereDate('m.created_at', '>=', $inicio)
                        ->whereDate('m.created_at', '<=', $fim)
                        ->whereIn('m.operacao_id', [22])
                        ->where(function ($query) {
                            $query->where('m.referencia', '=', 'p.id')
                                ->orwhere('m.pedido_id', '=', 'p.id');
                        })
                        ->select([
                            DB::raw('DISTINCT m.id'),
                            'o.name as operacao',
                            DB::raw('sum(m.valor_manipulado) as valor'),
                        ])
                        ->groupBy('operacao');

                    if ($consultor > 0) {
                        //se for sintetico o join de usuario é adicionado
                        $pedidos->join('users as c', 'c.id', '=', 'm.user_id')
                            ->where('c.id', $consultor);

                        $estorno->join('users as c', 'c.id', '=', 'm.user_id')
                            ->where('c.id', $consultor);
                    }

                    $queryBuilder
                        ->union($pedidos)
                        ->union($estorno)
                        ->Join('mensalidades as ms', 'ms.id', '=', 'm.mensalidade_id')
                        ->whereDate('ms.dt_baixa', '>=', $inicio)
                        ->whereDate('ms.dt_baixa', '<=', $fim)
                        ->where(function ($query) {
                            $query->where('m.referencia', '=', 'ms.id')
                                ->orwhere('m.mensalidade_id', '=', 'ms.id');
                        });
                }

                if ($sortBy > 0) {
                    $queryBuilder->where('o.id', $sortBy);
                }

                if ($consultor > 0) {
                    //se for sintetico o join de usuario é adicionado
                    $queryBuilder->join('users as c', 'c.id', '=', 'm.user_id')
                        ->where('c.id', $consultor);
                }

                $queryBuilder->select([
                    'o.name as operacao',
                    DB::raw('sum(m.valor_manipulado) as valor'),
                ])
                    ->groupBy('operacao');

                //$queryBuilder->orderBy('m.created_at');
            }

            //dd($queryBuilder->toSql());
            //if($_SERVER['REMOTE_ADDR'] == '168.195.236.179'){
            //dd($queryBuilder->toSql());
            //}

            // TODO formatação dos campos
            if ($request->get('tipo') == 2) {
                $columns = [
                    'Data'        => function ($result) { // You can do if statement or any action do you want inside this closure
                        return Carbon::parse($result->dt)->format('d/m/Y');
                    },
                    'Contrato'    => 'contrato',
                    'Nome'        => function ($result) {
                        return \TratarTexto::abreviar($result->user);
                    },
                    'Tipo'        => function ($result) {
                        $retorno = '';
                        switch ($result->tipo) {
                            case 1:
                                $retorno = 'Adesão';
                                break;
                            case 17:
                                $retorno = 'Equiparação';
                                break;
                            case 18:
                                $retorno = 'Mensalidade direta';
                                break;
                            case 19:
                                $retorno = 'Mensalidade indireta';
                                break;
                            case 20:
                                $retorno = 'Renovação';
                                break;
                            case 22:
                                $retorno = 'Estorno cancelamento contrato';
                                break;
                        }

                        return $retorno;
                    },
                    'Plano'       => function ($result) { // You can do if statement or any action do you want inside this closure
                        if ($result->plano) {
                            $retorno = $result->plano;
                            if (strpos($retorno, 'PLANO FAMILIAR') !== false) {
                                $retorno = substr(trim($retorno), 15);
                            } elseif (strpos($retorno, 'PLANO EMPRESARIAL') !== false) {
                                $retorno = substr(trim($retorno), 18);
                            }

                            return $retorno;
                        } else {
                            return '';
                        }
                    },
                    'Valor'       => function ($resul) {
                        return $resul->valor; //mascaraMoeda($sistema->moeda, $resul->valor, 2, true);
                    },
                    'M.Pagamento' => 'MetodoPagamento',
                    'N° Boleto'   => 'boleto',
                    'Parcela'     => 'parcela',
                    'Consultor'   => function ($result) {
                        return \TratarTexto::abreviar($result->consultor, 20);
                    },
                ];
            } else {
                $columns = [
                    'Tipo bonificação' => 'operacao',
                    'valor'            => function ($resul) {
                        return mascaraMoeda($this->sistema->moeda, $resul->valor, 2, true);
                    },
                ];
            }

            //if ($request->get('tipo') == 1) {
            $relatorio = \PdfReport::of($title, $meta, $queryBuilder, $columns);
            /*} else {
                $relatorio = \ExcelReport::of($title, $meta, $queryBuilder, $columns);
            }*/

            if ($request->get('tipo') == 2) {
                $relatorio->editColumn('valor', [
                    'class'     => 'right bold',
                    'displayAs' => function ($result) {
                        return thousandSeparator($result->valor);
                    },
                ])
                    ->showTotal([
                        'Valor' => '$',
                    ])
                    ->setCss([
                        'tr' => 'page-break-inside: avoid;',
                    ])
                    ->setOrientation('landscape');
            } else {
                $relatorio->editColumn('Dt Pagamento', [
                    'displayAs' => function ($result) {
                        return Carbon::parse($result->created_at)->format('d M Y');
                    },
                ])
                    ->editColumn('Valor', [
                        'class' => 'right bold',
                    ])
                    ->setCss([
                        'tr' => 'page-break-inside: avoid;',
                    ]);
            }

            //if ($request->get('tipo') == 1) {

            return $relatorio->stream();
            /*} else {
                return $relatorio->download('Relatorio de faturamento - ' . date('d/m/Y'));
            }*/
        } catch (ModelNotFoundException $e) {
            dd('deu ruim - '.$e->getMessage());
        }
    }

    public function relatorioColaboradores($id)
    {
        try {
            // Retrieve any filters
            /*$sortBy = $request->input('sort_by');
            $inicio = implode('-', array_reverse(explode("/", $request->input('inicio'))));
            $fim    = implode('-', array_reverse(explode("/", $request->input('fim'))));*/

            $user = User::findOrFail($id);

            // Report title
            $title = 'Relatório de Colaboradores de '.$user->name;

            // For displaying filters description on header
            $meta = [
                'Periodo'  => Carbon::now()->month.'/'.Carbon::now()->year,
                'Contrato' => $user->contratos()->where('status', 2)->first()->id,
            ];

            $queryBuilder = DB::table('users as u')
                ->where('u.empresa_id', $id)
                ->where('u.status', 1)
                ->whereNull('u.deleted_at')
                ->select([
                    'id',
                    'name',
                    'codigo',
                    'cpf',
                ]);

            // Set Column to be displayed

            $columns = [
                'ID'       => 'id',
                'Contrato' => 'codigo',
                'Nome'     => 'name',
                'CPF'      => 'cpf',
            ];

            /*
                Generate Report with flexibility to manipulate column class even manipulate column value (using Carbon, etc).

                - of()         : Init the title, meta (filters description to show), query, column (to be shown)
                - editColumn() : To Change column class or manipulate its data for displaying to report
                - editColumns(): Mass edit column
                - showTotal()  : Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
                - groupBy()    : Show total of value on specific group. Used with showTotal() enabled.
                - limit()      : Limit record to be showed
                - make()       : Will producing DomPDF / SnappyPdf instance so you could do any other DomPDF / snappyPdf method such as stream() or download()
            */

            $relatorio = \PdfReport::of($title, $meta, $queryBuilder, $columns);

            $relatorio->setCss(['tr' => 'page-break-inside: avoid;']);

            return $relatorio->stream();
        } catch (ModelNotFoundException $e) {
            dd('deu ruim - '.$e->getMessage());
        }
    }

    public function pagamentosDiarios()
    {
        return view('default.relatorios.pagamento-diarios', []);
    }

    public function relatorioPagamentosDiarios(Request $request)
    {
        try {
            $sortBy = $request->input('sort_by');

            switch ($request->input('pagamento')) {
                case 0:
                    $tPagamento = 'Todos';
                    break;
                case 1:
                    $tPagamento = 'Boletos';
                    break;
                case 8:
                    $tPagamento = 'Dinheiro';
                    break;
            }

            $inicio = implode('-', array_reverse(explode('/', $request->input('inicio'))));
            $fim = implode('-', array_reverse(explode('/', $request->input('fim'))));

            //$mensalidade = Mensalidade::with('metodoPagamento')->whereRaw('Date(created_at) = CURDATE()')->get();
            //$pedidos = DadosPagamento::with('pedido')->whereRaw('Date(data_pagamento) = CURDATE()')->get();

            // Report title
            $title = 'Relatório de Recebimentos diários';

            // For displaying filters description on header
            $meta = [
                'Periodo' => $request->input('inicio').' a '.$request->input('fim'),
                'Tipo de pagamento' => $tPagamento,
            ];

            switch ($sortBy) {
                case 1:
                    $queryBuilder = DB::table('pedidos as p')
                        ->Join('itens_pedido as ip', 'ip.pedido_id', '=', 'p.id')
                        ->LeftJoin('movimentos as mv', 'mv.pedido_id', '=', 'p.id')
                        ->leftJoin('contratos as c', 'c.pedido_id', '=', 'p.id')
                        ->Join('itens as i', 'i.id', '=', 'ip.item_id')
                        ->Join('tipo_pedidos as tp', 'tp.id', '=', 'i.tipo_pedido_id')
                        ->Join('dados_pagamento as dp', 'dp.pedido_id', '=', 'p.id')
                        ->Join('users as u', 'u.id', '=', 'p.user_id')
                        ->leftJoin('boletos as b', 'b.id', '=', 'p.boleto_id')
                        ->Join('metodo_pagamento as mp', 'mp.id', '=', 'dp.metodo_pagamento_id')
                        ->whereDate('dp.data_pagamento', '>=', $inicio)
                        ->whereDate('dp.data_pagamento', '<=', $fim)
                        ->where('p.status', '=', 2)
                        ->where(function ($query) {
                            $query->whereNotIn('mv.operacao_id', [22, 23])
                                ->orWhereIn('i.tipo_pedido_id', [1, 2, 3, 4, 5, 6]);
                        })
                        ->select([
                            DB::raw('DISTINCT p.id'),
                            DB::raw('dp.data_pagamento as "dt"'),
                            DB::raw('REPLACE(p.valor_total, ",", ".") as "valor"'),
                            DB::raw('u.name as "user"'),
                            DB::raw('mp.name as "MetodoPagamento"'),
                            DB::raw('b.nosso_numero as "boleto"'),
                            'tp.name as tipo',
                            DB::raw('"1" as "parcela"'),
                            DB::raw('i.name as "plano"'),
                            DB::raw('u.codigo as "contrato"'),
                        ]);

                    if ($request->input('pagamento') > 0) {
                        $queryBuilder->where('mp.id', $request->input('pagamento'));
                    }
                    break;

                case 2:
                    $queryBuilder = DB::table('mensalidades as m')
                        ->Join('metodo_pagamento as mp', 'mp.id', '=', 'm.metodo_pagamento_id')
                        ->Join('users as u', 'u.id', '=', 'm.user_id')
                        ->Join('boletos as b', 'b.id', '=', 'm.boleto_id')
                        ->Join('contratos as c', 'c.id', '=', 'm.contrato_id')
                        ->Join('itens as i', 'i.id', '=', 'c.item_id')
                        ->whereDate('m.dt_baixa', '>=', $inicio)
                        ->whereDate('m.dt_baixa', '<=', $fim)
                        ->where('m.status', '=', 4)
                        //->whereNull('u.deleted_at')
                        ->select([
                            DB::raw('DISTINCT m.id'),
                            DB::raw('m.dt_baixa as "dt"'),
                            DB::raw('REPLACE(m.valor_pago, ",", ".") as "valor"'),
                            DB::raw('u.name as "user"'),
                            DB::raw('mp.name as "MetodoPagamento"'),
                            DB::raw('b.nosso_numero as "boleto"'),
                            DB::raw('"Mensalidade" as "tipo"'),
                            DB::raw('m.parcela as "parcela"'),
                            DB::raw('i.name as "plano"'),
                            DB::raw('u.codigo as "contrato"'),
                        ]);
                    if ($request->input('pagamento') > 0) {
                        $queryBuilder->where('mp.id', $request->input('pagamento'));
                    }
                    break;

                case 4:
                    $queryBuilder = DB::table('pedidos as p')
                        ->Join('movimentos as mv', 'mv.pedido_id', '=', 'p.id')
                        ->Join('operacoes as o', 'o.id', '=', 'mv.operacao_id')
                        ->Join('itens_pedido as ip', 'ip.pedido_id', '=', 'p.id')
                        ->leftJoin('contratos as c', 'c.pedido_id', '=', 'p.id')
                        ->Join('itens as i', 'i.id', '=', 'ip.item_id')
                        ->Join('tipo_pedidos as tp', 'tp.id', '=', 'i.tipo_pedido_id')
                        ->Join('dados_pagamento as dp', 'dp.pedido_id', '=', 'p.id')
                        ->Join('users as u', 'u.id', '=', 'p.user_id')
                        ->leftJoin('boletos as b', 'b.id', '=', 'p.boleto_id')
                        ->Join('metodo_pagamento as mp', 'mp.id', '=', 'dp.metodo_pagamento_id')
                        ->whereDate('mv.created_at', '>=', $inicio)
                        ->whereDate('mv.created_at', '<=', $fim)
                        ->where('p.status', '=', 2)
                        ->where('o.id', '=', 23)
                        ->select([
                            DB::raw('DISTINCT p.id'),
                            DB::raw('mv.created_at as "dt"'),
                            DB::raw('REPLACE(mv.valor_manipulado, ",", ".") as "valor"'),
                            DB::raw('u.name as "user"'),
                            DB::raw('mp.name as "MetodoPagamento"'),
                            DB::raw('b.nosso_numero as "boleto"'),
                            'o.name as tipo',
                            DB::raw('"1" as "parcela"'),
                            DB::raw('i.name as "plano"'),
                            DB::raw('u.codigo as "contrato"'),
                        ]);
                    if ($request->input('pagamento') > 0) {
                        $queryBuilder->where('mp.id', $request->input('pagamento'));
                    }
                    break;

                case 3:
                    $pedidos = DB::table('pedidos as p')
                        ->Join('itens_pedido as ip', 'ip.pedido_id', '=', 'p.id')
                        ->LeftJoin('movimentos as mv', 'mv.pedido_id', '=', 'p.id')
                        ->leftJoin('contratos as c', 'c.pedido_id', '=', 'p.id')
                        ->Join('itens as i', 'i.id', '=', 'ip.item_id')
                        ->Join('tipo_pedidos as tp', 'tp.id', '=', 'i.tipo_pedido_id')
                        ->Join('dados_pagamento as dp', 'dp.pedido_id', '=', 'p.id')
                        ->Join('users as u', 'u.id', '=', 'p.user_id')
                        ->leftJoin('boletos as b', 'b.id', '=', 'p.boleto_id')
                        ->Join('metodo_pagamento as mp', 'mp.id', '=', 'dp.metodo_pagamento_id')
                        ->whereDate('dp.data_pagamento', '>=', $inicio)
                        ->whereDate('dp.data_pagamento', '<=', $fim)
                        ->where('p.status', '=', 2)
                        ->where(function ($query) {
                            $query->whereNotIn('mv.operacao_id', [22, 23])
                                ->orWhereIn('i.tipo_pedido_id', [1, 2, 3, 4, 5, 6]);
                        })
                        ->select([
                            DB::raw('DISTINCT p.id'),
                            DB::raw('dp.data_pagamento as "dt"'),
                            DB::raw('REPLACE(p.valor_total, ",", ".") as "valor"'),
                            DB::raw('u.name as "user"'),
                            DB::raw('mp.name as "MetodoPagamento"'),
                            DB::raw('b.nosso_numero as "boleto"'),
                            'tp.name as tipo',
                            DB::raw('"1" as "parcela"'),
                            DB::raw('i.name as "plano"'),
                            DB::raw('u.codigo as "contrato"'),
                        ]);

                    if ($request->input('pagamento') > 0) {
                        $pedidos->where('mp.id', $request->input('pagamento'));
                    }

                    $estorno = DB::table('pedidos as p')
                        ->Join('movimentos as mv', 'mv.pedido_id', '=', 'p.id')
                        ->Join('operacoes as o', 'o.id', '=', 'mv.operacao_id')
                        ->Join('itens_pedido as ip', 'ip.pedido_id', '=', 'p.id')
                        ->leftJoin('contratos as c', 'c.pedido_id', '=', 'p.id')
                        ->Join('itens as i', 'i.id', '=', 'ip.item_id')
                        ->Join('tipo_pedidos as tp', 'tp.id', '=', 'i.tipo_pedido_id')
                        ->Join('dados_pagamento as dp', 'dp.pedido_id', '=', 'p.id')
                        ->Join('users as u', 'u.id', '=', 'p.user_id')
                        ->leftJoin('boletos as b', 'b.id', '=', 'p.boleto_id')
                        ->Join('metodo_pagamento as mp', 'mp.id', '=', 'dp.metodo_pagamento_id')
                        ->whereDate('mv.created_at', '>=', $inicio)
                        ->whereDate('mv.created_at', '<=', $fim)
                        ->where('p.status', '=', 2)
                        ->where('o.id', '=', 23)
                        ->select([
                            DB::raw('DISTINCT p.id'),
                            DB::raw('mv.created_at as "dt"'),
                            DB::raw('REPLACE(mv.valor_manipulado, ",", ".") as "valor"'),
                            DB::raw('u.name as "user"'),
                            DB::raw('mp.name as "MetodoPagamento"'),
                            DB::raw('b.nosso_numero as "boleto"'),
                            'o.name as tipo',
                            DB::raw('"1" as "parcela"'),
                            DB::raw('i.name as "plano"'),
                            DB::raw('u.codigo as "contrato"'),
                        ]);

                    if ($request->input('pagamento') > 0) {
                        $estorno->where('mp.id', $request->input('pagamento'));
                    }

                    $queryBuilder = DB::table('mensalidades as m')
                        ->union($pedidos)
                        ->union($estorno)
                        ->Join('metodo_pagamento as mp', 'mp.id', '=', 'm.metodo_pagamento_id')
                        ->Join('users as u', 'u.id', '=', 'm.user_id')
                        ->Join('boletos as b', 'b.id', '=', 'm.boleto_id')
                        ->Join('contratos as c', 'c.id', '=', 'm.contrato_id')
                        ->Join('itens as i', 'i.id', '=', 'c.item_id')
                        ->whereDate('m.dt_baixa', '>=', $inicio)
                        ->whereDate('m.dt_baixa', '<=', $fim)
                        ->where('m.status', '=', 4)
                        //->whereNull('u.deleted_at')
                        ->select([
                            DB::raw('DISTINCT m.id'),
                            DB::raw('m.dt_baixa as "dt"'),
                            DB::raw('REPLACE(m.valor_pago, ",", ".") as "valor"'),
                            DB::raw('u.name as "user"'),
                            DB::raw('mp.name as "MetodoPagamento"'),
                            DB::raw('b.nosso_numero as "boleto"'),
                            DB::raw('"Mensalidade" as "tipo"'),
                            DB::raw('m.parcela as "parcela"'),
                            DB::raw('i.name as "plano"'),
                            DB::raw('u.codigo as "contrato"'),
                        ]);

                    if ($request->input('pagamento') > 0) {
                        $queryBuilder->where('mp.id', $request->input('pagamento'));
                    }
                    break;

                case 5:
                    $pedidos = DB::table('pedidos as p')
                        ->Join('itens_pedido as ip', 'ip.pedido_id', '=', 'p.id')
                        ->LeftJoin('movimentos as mv', 'mv.pedido_id', '=', 'p.id')
                        ->leftJoin('contratos as c', 'c.pedido_id', '=', 'p.id')
                        ->Join('itens as i', 'i.id', '=', 'ip.item_id')
                        ->Join('tipo_pedidos as tp', 'tp.id', '=', 'i.tipo_pedido_id')
                        ->Join('dados_pagamento as dp', 'dp.pedido_id', '=', 'p.id')
                        ->Join('users as u', 'u.id', '=', 'p.user_id')
                        ->leftJoin('boletos as b', 'b.id', '=', 'p.boleto_id')
                        ->Join('metodo_pagamento as mp', 'mp.id', '=', 'dp.metodo_pagamento_id')
                        ->whereDate('dp.data_pagamento', '>=', $inicio)
                        ->whereDate('dp.data_pagamento', '<=', $fim)
                        ->where('p.status', '=', 2)
                        ->where(function ($query) {
                            $query->whereNotIn('mv.operacao_id', [22, 23])
                                ->orWhereIn('i.tipo_pedido_id', [1, 2, 3, 4, 5, 6]);
                        })
                        ->select([
                            DB::raw('DISTINCT p.id'),
                            DB::raw('dp.data_pagamento as "dt"'),
                            DB::raw('REPLACE(p.valor_total, ",", ".") as "valor"'),
                            DB::raw('u.name as "user"'),
                            DB::raw('mp.name as "MetodoPagamento"'),
                            DB::raw('b.nosso_numero as "boleto"'),
                            'tp.name as tipo',
                            DB::raw('"1" as "parcela"'),
                            DB::raw('i.name as "plano"'),
                            DB::raw('u.codigo as "contrato"'),
                        ]);

                    if ($request->input('pagamento') > 0) {
                        $pedidos->where('mp.id', $request->input('pagamento'));
                    }

                    $queryBuilder = DB::table('pedidos as p')
                        ->union($pedidos)
                        ->Join('movimentos as mv', 'mv.pedido_id', '=', 'p.id')
                        ->Join('operacoes as o', 'o.id', '=', 'mv.operacao_id')
                        ->Join('itens_pedido as ip', 'ip.pedido_id', '=', 'p.id')
                        ->leftJoin('contratos as c', 'c.pedido_id', '=', 'p.id')
                        ->Join('itens as i', 'i.id', '=', 'ip.item_id')
                        ->Join('tipo_pedidos as tp', 'tp.id', '=', 'i.tipo_pedido_id')
                        ->Join('dados_pagamento as dp', 'dp.pedido_id', '=', 'p.id')
                        ->Join('users as u', 'u.id', '=', 'p.user_id')
                        ->leftJoin('boletos as b', 'b.id', '=', 'p.boleto_id')
                        ->Join('metodo_pagamento as mp', 'mp.id', '=', 'dp.metodo_pagamento_id')
                        ->whereDate('mv.created_at', '>=', $inicio)
                        ->whereDate('mv.created_at', '<=', $fim)
                        ->where('p.status', '=', 2)
                        ->where('o.id', '=', 23)
                        ->select([
                            DB::raw('DISTINCT p.id'),
                            DB::raw('mv.created_at as "dt"'),
                            DB::raw('REPLACE(mv.valor_manipulado, ",", ".") as "valor"'),
                            DB::raw('u.name as "user"'),
                            DB::raw('mp.name as "MetodoPagamento"'),
                            DB::raw('b.nosso_numero as "boleto"'),
                            'o.name as tipo',
                            DB::raw('"1" as "parcela"'),
                            DB::raw('i.name as "plano"'),
                            DB::raw('u.codigo as "contrato"'),
                        ]);

                    if ($request->input('pagamento') > 0) {
                        $queryBuilder->where('mp.id', $request->input('pagamento'));
                    }
                    break;
            }

            $queryBuilder->orderBy('dt', 'ASC');
            $queryBuilder->orderBy('user', 'ASC');

            //dd($queryBuilder->toSql());

            // Set Column to be displayed
            $columns = [
                'Data'             => function ($result) { // You can do if statement or any action do you want inside this closure
                    return Carbon::parse($result->dt)->format('d/m/Y');
                },
                'Contrato'         => 'contrato',
                'Nome'             => function ($result) {
                    return mb_strtoupper($result->user);
                },
                'Tipo'             => 'tipo',
                'Plano'            => function ($result) { // You can do if statement or any action do you want inside this closure
                    if ($result->plano) {
                        $retorno = $result->plano;
                        if (strpos($retorno, 'PLANO FAMILIAR') !== false) {
                            $retorno = substr(trim($retorno), 15);
                        } elseif (strpos($retorno, 'PLANO EMPRESARIAL') !== false) {
                            $retorno = substr(trim($retorno), 18);
                        }

                        return $retorno;
                    } else {
                        return '';
                    }
                },
                'Valor'            => function ($resul) {
                    return $resul->valor; //mascaraMoeda($sistema->moeda, $resul->valor, 2, true);
                },
                'Metodo Pagamento' => 'MetodoPagamento',
                'N° Boleto'        => 'boleto',
                'Parcela'          => 'parcela',
            ];

            $relatorio = \PdfReport::of($title, $meta, $queryBuilder, $columns);

            $relatorio->editColumn('valor', [
                'class'     => 'right bold',
                'displayAs' => function ($result) {
                    return thousandSeparator($result->valor);
                },
            ])
                ->showTotal([
                    'Valor' => '$',
                ])
                ->setCss([
                    'tr' => 'page-break-inside: avoid;',
                ])
                ->setOrientation('landscape');

            return $relatorio->stream();
        } catch (ModelNotFoundException $e) {
            dd('deu ruim - '.$e->getMessage());
        }
    }

    public function relatorioUsuariosInadimplentes(Request $request)
    {
        try {

            // Report title
            $title = 'Relatório de inadimplentes';

            /*       $meta = [
                       'Periodo' => 'Todos',
                   ];*/

            if ($request->has('qtd_mensalidades')) {
                $meta['Filtro'] = $request->get('qtd_mensalidades').' mensalidades atrasadas ou mais';
            }

            if ($request->get('indicador') > 0) {
                $indicadorName = User::select('name')->find($request->get('indicador'))->name;
                $meta['Consultor'] = $indicadorName;
            }

            if (! isset($meta)) {
                $meta = [
                    'Filtro' => 'Nenhum',
                ];
            }

            $queryBuilder = DB::table('users as u')
                ->join('contratos as c', 'c.user_id', '=', 'u.id')
                ->join('itens as i', 'i.id', '=', 'c.item_id')
                ->join('mensalidades as m', 'm.user_id', '=', 'u.id')
                ->whereNotIn('u.id', [1, 2])
                ->whereRaw('m.contrato_id = c.id')
                ->where('u.status', 2)
                ->where('m.status', 3)
                ->where('c.status', 3)
                ->select([
                    'c.id',
                    'u.name',
                    'u.telefone',
                    'u.celular',
                    DB::raw('i.name as plano'),
                    'u.cnpj',
                    DB::raw('count(m.id) as mensalidades'),
                    DB::raw('ifnull((select date_format(max(dt_pagamento), "%d/%m/%Y") from mensalidades where `status` = 4 and user_id = u.id and contrato_id = c.id), "Não há pagamentos") as ultima'),
                ])->groupBy('c.id');

            if ($request->has('qtd_mensalidades')) {
                $queryBuilder->whereRaw("(
                                            SELECT COUNT(id)
                                            FROM mensalidades
                                            WHERE `status` = 3
                                            AND user_id = u.id
                                            AND contrato_id = c.id
                                        ) >= {$request->get('qtd_mensalidades')}");
            }

            if ($request->get('indicador') > 0) {
                $queryBuilder->where('u.indicador_id', $request->get('indicador'));
            }

            // Set Column to be displayed
            $columns = [
                'Contrato'         => 'id',
                'Nome'             => 'name',
                'Telefone'         => 'telefone',
                'Celular'          => 'celular',
                'Plano'            => 'plano',
                'Qtd. atrasos'     => 'mensalidades',
                'Ultimo pagamento' => 'ultima',
            ];

            /*
                Generate Report with flexibility to manipulate column class even manipulate column value (using Carbon, etc).

                - of()         : Init the title, meta (filters description to show), query, column (to be shown)
                - editColumn() : To Change column class or manipulate its data for displaying to report
                - editColumns(): Mass edit column
                - showTotal()  : Used to sum all value on specified column on the last table (except using groupBy method). 'point' is a type for displaying total with a thousand separator
                - groupBy()    : Show total of value on specific group. Used with showTotal() enabled.
                - limit()      : Limit record to be showed
                - make()       : Will producing DomPDF / SnappyPdf instance so you could do any other DomPDF / snappyPdf method such as stream() or download()
            */

            $relatorio = \PdfReport::of($title, $meta, $queryBuilder, $columns);

            $relatorio
                ->setCss([
                    'tr' => 'page-break-inside: avoid;',
                ])
                ->setOrientation('landscape');

            return $relatorio->stream();
        } catch (ModelNotFoundException $e) {
            dd('deu ruim - '.$e->getMessage());
        }
    }
}
