<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace MasterMdr\Services\Relatorios;

use Carbon\Carbon;
use App\Models\Sistema;
use Illuminate\Support\Facades\DB;

/**
 * Services do Faturamento
 * Class RelatorioService.
 */
class DepositosRelatorioService
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
            /*  $inicio = implode('-', array_reverse(explode('/', $this->dados['inicio'])));
              $fim = implode('-', array_reverse(explode('/', $this->dados['fim'])));*/
            $inicio = $this->dados['inicio'];
            $fim = $this->dados['fim'];
            // Report title
            $title = 'Relatório de Depósitos';

            // For displaying filters description on header
            $meta = [
                'Período' => Carbon::parse($this->dados['inicio'])->format('d/m/Y').' a '.Carbon::parse($this->dados['fim'])->format('d/m/Y'),
            ];

            $queryBuilder = DB::table('dados_pagamento as dp')
                ->join('metodo_pagamento as mp', 'mp.id', '=', 'dp.metodo_pagamento_id')
                ->join('pedidos as p', 'p.id', '=', 'dp.pedido_id')
                ->join('itens_pedido as ip', 'ip.pedido_id', '=', 'p.id')
                ->join('itens as i', 'i.id', '=', 'ip.item_id')
                ->join('users as u', 'u.id', '=', 'p.user_id')
                ->whereDate('dp.data_pagamento_efetivo', '>=', $inicio)
                ->whereDate('dp.data_pagamento_efetivo', '<=', $fim)
                ->where('dp.status', 2)
                ->where('i.id', 8);

            if (isset($this->dados['metodoPagamento']) && $this->dados['metodoPagamento'] > 0) {
                $queryBuilder->where('mp.id', $this->dados['metodoPagamento']);
            }

            if (isset($this->dados['usuarios']) && $this->dados['usuarios'] > 0) {
                $queryBuilder
                    ->whereIn('u.id', $this->dados['usuarios']);
            }

            if ($this->dados['tipo'] == 2) {
                $title .= ' - Analítico';

                $queryBuilder
                    ->join('users as ur', 'ur.id', '=', 'dp.responsavel_user_id')
                    ->select([
                        'p.id as pedido_id',
                        'ip.name_item as deposito',
                        'u.username as usuario',
                        'p.valor_total as valor',
                        'ur.name as responsavel',
                        'mp.name as metodo',
                        'dp.documento as descricao',
                        DB::raw('DATE(dp.data_pagamento_efetivo) as pagamento'),
                    ]);
            } else {
                $title .= ' - Sintético';

                $queryBuilder
                    ->select([
                        DB::raw('DATE(dp.data_pagamento_efetivo) as pagamento'),
                        DB::raw('sum(p.valor_total) as valor'),
                        'mp.name as metodo',
                    ])
                    ->groupBy(['pagamento', 'metodo']);
            }

            $queryBuilder->orderBy('dp.data_pagamento_efetivo');

            // Set Column to be displayed
            if ($this->dados['tipo'] == 2) {
                $columns = [
                    'Dt Pagamento' => 'pagamento',
                    'Usuário' => 'usuario',
                    'Depósito' => function ($resul) {
                        return "{$resul->pedido_id} - {$resul->deposito}";
                    },
                    'Valor pago' => 'valor',
                    'Método pagamento'       => 'metodo',
                    'Responsável Baixa' => function ($resul) {
                        return ucwords(strtolower($resul->responsavel));
                    },
                    'Descrição'               => 'descricao',
                ];
            } else {
                $columns = [
                    'Data de Pagamento' => 'pagamento',
                    'Método de pagamento'  => 'metodo',
                    'Valor total pago'  => 'valor',
                ];
            }

            $relatorio = \PdfReport::of($title, $meta, $queryBuilder, $columns);

            if ($this->dados['tipo'] == 2) {
                $relatorio->editColumn('Dt Pagamento', [
                    'displayAs' => function ($result) {
                        return Carbon::parse($result->pagamento)->format('d/m/Y');
                    },
                ])
                    ->showTotal([
                        'Valor pago' => '$',
                    ])
                    ->setCss([
                        'tr' => 'page-break-inside: avoid;',
                    ]);

                $relatorio->setOrientation('landscape');
            } else {
                $relatorio->editColumn('Data de Pagamento', [
                    'displayAs' => function ($result) {
                        return Carbon::parse($result->pagamento)->format('d/m/Y');
                    },
                ])
                    ->showTotal([
                        'Valor total pago' => '$',
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
