<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Faturamento\Services;

use Carbon\Carbon;
use App\Models\Sistema;
use Illuminate\Support\Facades\DB;

/**
     * Services do contratos
     * Class RelatorioService.
     */
    class ContratosRelatorioService
    {
        private $dados;
        private $sistema;

        public function __construct(array $dados = [])
        {
            $this->dados = $dados;
            $this->sistema = Sistema::findOrFail(1);
        }

        /**
         * @description Gera o relatório de Faturamento
         * @param array $dados
         * @return PdfReport
         */
        public function gerar()
        {
            try {
                // Retrieve any filters
                $sortBy = $this->dados['sort_by'];
                $inicio = $this->dados['inicio'];
                $fim = $this->dados['fim'];

                // Report title
                $title = 'Relatório de faturamento';

                // For displaying filters description on header
                $meta = [
                    'Periodo' => $this->dados['inicio'].' a '.$this->dados['fim'],
                    'Filtro'  => 'Método de pagamento',
                ];

                $queryBuilder = DB::table('dados_pagamento as dp')
                    ->join('metodo_pagamento as mp', 'mp.id', '=', 'dp.metodo_pagamento_id')
                    ->whereDate('dp.data_pagamento', '>=', $inicio)
                    ->whereDate('dp.data_pagamento', '<=', $fim)
                    ->where('dp.status', 2)
                    ->whereIn('i.tipo_pedido_id', [1, 2]);

                if ($sortBy > 0) {
                    $queryBuilder->where('mp.id', $sortBy);
                }

                if ($this->dados['tipo'] == 2) {
                    $title .= ' - Analítico';

                    $queryBuilder->join('pedidos as p', 'p.id', '=', 'dp.pedido_id')
                        ->join('users as r', 'r.id', '=', 'dp.responsavel_user_id')
                        ->join('users as u', 'u.id', '=', 'p.user_id')
                        //->join('contratos as c', 'c.pedido_id', '=', 'p.id')
                        ->join('itens_pedido as ip', 'ip.pedido_id', '=', 'p.id')
                        ->join('itens as i', 'i.id', '=', 'ip.item_id')
                        ->select([
                            DB::raw('DATE(dp.data_pagamento) as pagamento'),
                            'u.name as usuario',
                            'p.valor_total as valor',
                            'ip.name_item as item',
                        ]);
                } else {
                    $title .= ' - Sintético';

                    $queryBuilder->join('pedidos as p', 'p.id', '=', 'dp.pedido_id')
                        ->join('itens_pedido as ip', 'ip.pedido_id', '=', 'p.id')
                        ->join('itens as i', 'i.id', '=', 'ip.item_id')
                        ->select([
                            DB::raw('DATE(dp.data_pagamento) as pagamento'),
                            DB::raw('sum(dp.valor) as valor'),
                            'mp.name as metodo',
                            'i.name as item',
                        ])
                        ->groupBy(['pagamento', 'item']);
                }

                $queryBuilder->orderBy('dp.data_pagamento');

                //dd($queryBuilder->get());

                // Set Column to be displayed
                if ($this->dados['tipo'] == 2) {
                    $columns = [
                        'Dt Pagamento'      => 'pagamento',
                        'Usuário'           => 'usuario',
                        'Item',
                        'Valor total' => 'valor',
                        'Valor' => function ($resul) {
                            return mascaraMoeda($this->sistema->moeda, $resul->valor, 2, true);
                        },
                    ];
                } else {
                    $columns = [
                        'Dt Pagamento' => 'pagamento',
                        'Item'  => 'item',
                        'Valor Total'        => function ($resul) {
                            return mascaraMoeda($this->sistema->moeda, $resul->valor, 2, true);
                        },
                    ];
                }

                $relatorio = \PdfReport::of($title, $meta, $queryBuilder, $columns);

                if ($this->dados['tipo'] == 2) {
                    $relatorio->editColumn('Dt Pagamento', [
                        'displayAs' => function ($result) {
                            return Carbon::parse($result->pagamento)->format('d M Y');
                        },
                    ])
                        ->showTotal([
                            'Valor total' => 'point',
                        ])
                        ->setCss([
                            'tr' => 'page-break-inside: avoid;',
                            'table tr td:last-child' => 'max-width: 100px;',
                        ]);

                    $relatorio->setOrientation('landscape');
                } else {
                    $relatorio->editColumn('Dt Pagamento', [
                        'displayAs' => function ($result) {
                            return Carbon::parse($result->pagamento)->format('d M Y');
                        },
                    ])
                        ->editColumn('Valor Total', [
                            'class' => 'right bold',
                        ])
                        ->setCss([
                            'tr' => 'page-break-inside: avoid;',
                        ]);
                }

                return $relatorio->stream();
            } catch (ModelNotFoundException $e) {
                dd('deu ruim - '.$e->getMessage());
            }
        }
    }
