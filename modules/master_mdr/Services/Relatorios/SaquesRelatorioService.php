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
class SaquesRelatorioService
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
            //$sortBy = $this->dados['sort_by'];
            /*  $inicio = implode('-', array_reverse(explode('/', $this->dados['inicio'])));
              $fim = implode('-', array_reverse(explode('/', $this->dados['fim'])));*/
            $inicio = $this->dados['inicio'];
            $fim = $this->dados['fim'];
            // Report title
            $title = 'Relatório de Saques';

            // For displaying filters description on header
            $meta = [
                'Período' => Carbon::parse($this->dados['inicio'])->format('d/m/Y').' a '.Carbon::parse($this->dados['fim'])->format('d/m/Y'),
            ];

            $queryBuilder = DB::table('transferencias as t')
                ->join('users as u', 'u.id', '=', 't.user_id')
                ->whereDate('t.dt_efetivacao', '>=', $inicio)
                ->whereDate('t.dt_efetivacao', '<=', $fim)
                ->where('t.dado_bancario_id', '>', 0)
                ->whereNull('t.destinatario_user_id');

            if (isset($this->dados['usuarios']) && $this->dados['usuarios'] > 0) {
                $queryBuilder
                    ->whereIn('u.id', $this->dados['usuarios']);
            }

            if ($this->dados['tipo'] == 2) {
                $title .= ' - Analítico';

                $queryBuilder
                    ->join('dados_bancarios as db', 'db.id', '=', 't.dado_bancario_id')
                    ->leftjoin('users as ur', 'ur.id', '=', 't.responsavel_user_id')
                    ->select([
                        't.id as transferencia_id',
                        'u.name as usuario',
                        DB::raw('t.valor as valor'),
                        DB::raw('DATE(t.dt_solicitacao) as data_solicitacao'),
                        DB::raw('DATE(t.dt_efetivacao) as data_efetivacao'),
                        DB::raw("concat('Banco:',db.banco_id,' Agência: ',db.agencia,ifnull(concat('-',db.agencia_digito),''),' Conta:',db.conta,ifnull(concat('-',db.conta_digito),'')) as dados_bancarios"),
                        'ur.name as responsavel',
                    ]);
            } else {
                $title .= ' - Sintético';

                $queryBuilder
                    ->select([
                        DB::raw('DATE(t.dt_efetivacao) as data_efetivacao'),
                        DB::raw('sum(t.valor) as valor'),
                    ])
                    ->groupBy(['data_efetivacao']);
            }

            $queryBuilder->orderBy('data_efetivacao');

            // Set Column to be displayed
            if ($this->dados['tipo'] == 2) {
                $columns = [
                    'ID' => 'transferencia_id',
                    'Usuário' => 'usuario',
                    'Valor transferência' => 'valor',
                    'Dt Solicitação' => 'data_solicitacao',
                    'Dt Efetivação' => 'data_efetivacao',
                    'Dados bancários' => 'dados_bancarios',
                    'Responsável' => function ($resul) {
                        return ucwords(strtolower($resul->responsavel));
                    },
                ];
            } else {
                $columns = [
                    'Data de efetivação' => 'data_efetivacao',
                    'Valor transferência'  => 'valor',
                ];
            }

            $relatorio = \PdfReport::of($title, $meta, $queryBuilder, $columns);

            if ($this->dados['tipo'] == 2) {
                $relatorio
                    ->editColumn('Dt Solicitação', [
                        'displayAs' => function ($result) {
                            return Carbon::parse($result->data_solicitacao)->format('d/m/Y');
                        },
                    ])
                    ->editColumn('Dt Efetivação', [
                        'displayAs' => function ($result) {
                            return Carbon::parse($result->data_efetivacao)->format('d/m/Y');
                        },
                    ])
                    ->showTotal([
                        'Valor transferência' => '$',
                    ])
                    ->setCss([
                        'tr' => 'page-break-inside: avoid;',
                    ]);

                $relatorio->setOrientation('landscape');
            } else {
                $relatorio->editColumn('Data de efetivação', [
                    'displayAs' => function ($result) {
                        return Carbon::parse($result->data_efetivacao)->format('d/m/Y');
                    },
                ])
                    ->showTotal([
                        'Valor transferência' => '$',
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
