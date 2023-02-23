<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

use Carbon\Carbon;
use App\Models\Pedidos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PagseguroService
{
    public function __construct()
    {
    }

    public function atualizarPagamentos()
    {
        Log::info('###Inicio da verificação de pagamentos Pagseguro###');
        //seleciono os pedidos que estão aguardando a confirmação do pagamento
        //e o metodo de pagamento com Pagseguro
        $pedidos = Pedidos::with('itens.itens', 'dadosPagamento', 'usuario')
            ->whereHas('dadosPagamento', function ($query) {
                $query->where('status', 4)->where('metodo_pagamento_id', 2);
            })
            ->where('status', 4)->get();
        dd($pedidos);
        foreach ($pedidos as $pedido) {
            try {
                Log::info('  ');
                Log::info("Verificando do pedido {$pedido->id} - {$pedido->usuario->name}");
                Log::info("Chave de transação {$pedido->dadosPagamento->transaction_id}");
                //$transaction_id = str_replace('-', '', $pedido->dadosPagamento->transaction_id);
                $transaction_id = $pedido->dadosPagamento->transaction_id;

                if ('sandbox' == env('PAGSEGURO_AMBIENTE')) {
                    /*$endPoint = "https://sandbox.api.pagseguro.com/digital-payments/v1/transactions/{$transaction_id}/status";*/
                    $endPoint = "https://ws.sandbox.pagseguro.uol.com.br/v3/transactions/{$transaction_id}?email=".env('PAGSEGURO_EMAIL').'&token='.env('PAGSEGURO_TOKEN');
                } else {
                    $endPoint = "https://ws.pagseguro.uol.com.br/v3/transactions/{$transaction_id}?email=".env('PAGSEGURO_EMAIL').'&token='.env('PAGSEGURO_TOKEN');
                }

                $curl = curl_init();

                curl_setopt_array($curl, [
                    CURLOPT_URL => $endPoint,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => false,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                ]);

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                $xml = simplexml_load_string($response);

                if (count($xml->error) > 0) {
                    Log::info('Erro no xml, ou não tem pedidos');

                    return 0;
                } else {
                    switch ($xml->status) {
                        case 1:
                            Log::info('Status da transação: Aguardando pagamento');
                            break;
                        case 2:
                            Log::info('Status da transação: Em análise');
                            break;
                        case 3:
                            Log::info('Status da transação: Paga');
                            DB::beginTransaction();
                            try {
                                $pedido->getRelation('dadosPagamento')->documento = 'Confirmação automática Pagseguro via sistema';
                                $pedido->getRelation('dadosPagamento')->status = 2;
                                $pedido->getRelation('dadosPagamento')->data_pagamento = Carbon::now();
                                $pedido->getRelation('dadosPagamento')->data_pagamento_efetivo = Carbon::now();
                                $pedido->getRelation('dadosPagamento')->responsavel_user_id = 1;
                                $pedido->getRelation('dadosPagamento')->save();

                                $pedido->status = 2;
                                $pedido->save();

                                $pagamento = new Pagamentos($pedido);

                                $erros = $pagamento->efetivarPagamento();

                                if ($erros > 0) {
                                    DB::rollback();
                                    Log::info('Houve erros ao processar a confirmação de pagamento Pagseguro do pedido #'.$pedido->id);
                                    Log::info('');
                                } else {
                                    DB::commit();
                                    Log::info('Pedido #'.$pedido->id.' confirmado pagamento com sucesso');
                                    Log::info('');
                                }
                            } catch (ModelNotFoundException $e) {
                                DB::rollback();
                                Log::info('Houve erros ao processar a confirmação de pagamento Pagseguro do pedido #'.$pedido->id);
                                Log::info('');
                            }
                            break;
                        case 4:
                            Log::info('Status da transação: Disponível');
                            break;
                        case 5:
                            Log::info('Status da transação: Em disputa');
                            break;
                        case 6:
                            Log::info('Status da transação: Devolvida');
                            break;
                        case 7:
                            Log::info('Status da transação: Cancelada');
                            break;
                    }
                }
            } catch (\Exception $e) {
                Log::error('Erro ao consultar da transação: '.$e->getMessage());
            }
        }

        if ($pedidos->count() == 0) {
            Log::info('Não tem pedidos com pagseguro!');
        }
        Log::info('### fim da verificação de pagamentos Pagseguro ###');

        return true;
    }
}
