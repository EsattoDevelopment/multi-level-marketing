<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pedidos;
use PagSeguro\Helpers\Xhr;
use App\Services\Pagamentos;
use Illuminate\Http\Request;
//Criar pagamentos
use mysql_xdevapi\Exception;
use App\Models\DadosPagamento;
use App\Models\MetodoPagamento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PagSeguro\Configuration\Configure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
//consultar pagamentos
use PagSeguro\Domains\Requests\Payment as PagseguroPayment;
use PagSeguro\Services\Transactions\Notification as PagseguroNotification;

class PagseguroController extends Controller
{
    //statua do pagseguro
    /*return [
    'status_pagseguro' => [
    '1' => 'Aguardando pagamento',
    '2' => 'Em análise',
    '3' => 'Paga',
    '4' => 'Disponível',
    '5' => 'Em disputa',
    '6' => 'Devolvida',
    '7' => 'Cancelada'
    ]
    ];*/
    public function __construct()
    {
        $this->_configs = new Configure();
        $this->_configs->setCharset('UTF-8');
        $this->_configs->setAccountCredentials(env('PAGSEGURO_EMAIL'), env('PAGSEGURO_TOKEN'));
        $this->_configs->setEnvironment(env('PAGSEGURO_AMBIENTE'));
        //pode ser false
        $this->_configs->setLog(true, storage_path('logs/pagseguro_'.date('Ymd').'.txt'));
    }

    public function getCredenciais()
    {
        return $this->_configs->getAccountCredentials();
    }

    public function criaRequisicao($deposito_id)
    {
        try {
            $pedido = Pedidos::with('itens', 'dadosPagamento', 'user')->where('id', $deposito_id)->first();
            $pagamento = new PagseguroPayment();
            $pagamento->setCurrency('BRL');
            //referência interna do pagamento ao sistema
            $pagamento->setReference($deposito_id);
            //pegar valores do plano selecionado na tela
            $pagamento->addItems()->withParameters(
                $pedido->id,
                $pedido->itens->first()->name_item,
                1,
                $pedido->dadosPagamento->valor
            );
            //verifico se tem valor da taxa
            $metodoPagamento = MetodoPagamento::find(2);
            if ($metodoPagamento->taxa_valor > 0 || $metodoPagamento->taxa_porcentagem > 0) {
                $valorTaxa = $metodoPagamento->taxa_valor;
                if ($metodoPagamento->taxa_porcentagem > 0) {
                    $valorTaxa += ($pedido->dadosPagamento->valor * $metodoPagamento->taxa_porcentagem) / 100;
                }

                $pagamento->addItems()->withParameters(
                    $metodoPagamento->id,
                    $metodoPagamento->taxa_descricao,
                    1,
                    convertDoubleGeral($valorTaxa)
                );
            }
            //pegar do usuario logado
            $pagamento->setSender()->setName($pedido->user->name);
            $pagamento->setSender()->setEmail($pedido->user->email);
            //pegar cpf ou cnpj do usuario logado
            /*if($pedido->user->cpf){
                $pagamento->setSender()->setPhone()->withParameters(
                    Auth::user()->cliente->tipo_cpf_cnpj,
                    Auth::user()->cliente->cpf_cnpj
                );
            }*/
            //pegar telefone do usuario logado
            /*if(Auth::user()->cliente->telefone){
                $pagamento->setSender()->setPhone()->withParameters(
                    Auth::user()->cliente->telefone_ddd,
                    Auth::user()->cliente->telefone
                );
            }*/
            $onlyCheckoutCode = true;
            $result = $pagamento->register($this->getCredenciais(), $onlyCheckoutCode);

            return $result->getCode();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function notificacao(Request $request)
    {
        Log::info('******pagseguro post pelo uol carlos******');
        /*        try {
                    if (Xhr::hasPost()) {
                        $response = PagseguroNotification::check(
                            $this->getCredenciais()
                        );
                        self::atualizaPagamento($response);
                        Log::info("Código de retorno: ");
                    } else {
                        throw new Exception('Código invalido');
                    }
                } catch (Exception $e) {
                    logger($e->getMessage());
                }*/
    }

    public function registrarPagamento(Request $request, $deposito_id)
    {
        Log::info('#######recebeu o retorno do pagamento pagseguro para registro interno no sistema##########');
        Log::info('ID do depósito: '.$deposito_id);
        Log::info('Código: '.$request->code);

        $metodoPagamento = MetodoPagamento::find(2);
        $dadosPagamento = DadosPagamento::where('pedido_id', $deposito_id)->first();
        $pedido = Pedidos::where('id', $deposito_id)->first();

        DB::beginTransaction();
        try {
            $valorTaxa = $metodoPagamento->taxa_valor;
            if ($metodoPagamento->taxa_porcentagem > 0) {
                $valorTaxa += ($pedido->dadosPagamento->valor * $metodoPagamento->taxa_porcentagem) / 100;
            }

            $dadosPagamento->status = 4; //status de aguardando confirmação
            $dadosPagamento->data_vencimento = Carbon::now()->addDays(3)->format('Y-m-d H:i:s');
            $dadosPagamento->metodo_pagamento_id = $metodoPagamento->id; //pagseguro
            $dadosPagamento->transaction_id = $request->code;
            $dadosPagamento->taxa_valor = $valorTaxa;
            $dadosPagamento->save();

            //atualizo o pedido
            $pedido->status = 4; //aguardando confirmação
            $pedido->save();
            Log::info('Pedido salvo');

            DB::commit();

            //já aproveito e verifico se já mudou o status para pago

            if ($this->consultaPagamentoManual($request->code) == 3) {
                //se for igual a 3 já consta como pago
                DB::beginTransaction();
                try {
                    $dadosPagamento->documento = 'Confirmação automática Pagseguro via sistema';
                    $dadosPagamento->status = 2; //pedido pago
                    $dadosPagamento->data_pagamento = Carbon::now();
                    $dadosPagamento->data_pagamento_efetivo = Carbon::now();
                    $dadosPagamento->metodo_pagamento_id = $metodoPagamento->id; //pagseguro
                    $dadosPagamento->responsavel_user_id = 1;
                    $dadosPagamento->save();

                    //atualizo o pedido
                    $pedido->status = 2; //pedido pago
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
            }

            return response()->json(['status' => 'success'], 200);
        } catch (Exception $e) {
            Log::info('Erro ao salvar pedido');

            DB::rollBack();

            return false;
        }
    }

    public function consultaPagamentoManual($transaction_id)
    {
        try {
            $transaction_id = str_replace('-', '', $transaction_id);

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
                return 0;
            } else {
                return $xml->status;
            }

            //por json
            /*if (! $err) {
                $obj = json_decode($response);
                if (isset($obj->transaction_code)) {
                    if($obj->transaction_code == $transaction_id){
                        if (isset($obj->status->id)) {
                            return $obj->status->id;
                        }
                    }
                }
            }*/
        } catch (\Exception $e) {
            Log::error('Erro ao consultar status da transação no Pagseguro. Código da transação: '.$transaction_id);

            return 0;
        }
    }
}
