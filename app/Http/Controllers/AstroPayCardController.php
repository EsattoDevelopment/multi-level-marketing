<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AstroPayCardRequest;
use App\Models\MetodoPagamento;
use App\Models\Pedidos;
use App\Services\Pagamentos;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Services\AstroPayCardService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;

class AstroPayCardController extends Controller
{
    public function pagar(AstroPayCardRequest $request )
    {
        Log::info("**************");
        Log::info("Inicio de pagamento de depósito via Astropay Card");

        $pedido = Pedidos::with('itens', 'dadosPagamento', 'user', 'status')
            ->whereId($request->pedido_id)
            ->whereUserId(Auth::user()->id)
            ->first();

        Log::info("Depósito: {$pedido->id}");
        Log::info("Usuario: {$pedido->user->id} - {$pedido->user->name}");
        Log::info("Valor do depósito: {$pedido->dadosPagamento->valor}");

        if($pedido == null){
            flash()->error("Pedido não localizado para o seu usuário");
            return redirect()->back();
        }

        $valorTotal = $pedido->dadosPagamento->valor;

        //verifico se tem valor da taxa
        $metodoPagamento = MetodoPagamento::where('id', 11)->first();
        if($metodoPagamento->taxa_valor > 0 || $metodoPagamento->taxa_porcentagem > 0) {
            $valorTaxa = $metodoPagamento->taxa_valor;
            if($metodoPagamento->taxa_porcentagem > 0)
                $valorTaxa += ($pedido->dadosPagamento->valor * $metodoPagamento->taxa_porcentagem) / 100;

            $pedido->dadosPagamento->taxa_valor = $valorTaxa;
            $valorTotal = $valorTotal + convertDoubleGeral($valorTaxa);
            Log::info("Valor da taxa: " . convertDoubleGeral($valorTaxa));
        }

//AUTHENTIC
        $x_login = (strtolower(env('APP_ENV')) == 'local') ? $metodoPagamento->configuracao['x_login_homolog'] : $metodoPagamento->configuracao['x_login_prod'];
        $x_trans_key = (strtolower(env('APP_ENV')) == 'local') ? $metodoPagamento->configuracao['x_trans_key_homolog'] : $metodoPagamento->configuracao['x_trans_key_prod'];
        $sandbox = (strtolower(env('APP_ENV')) == 'local') ? true : false;

        //AstroPayCard class instance
        $ap = new AstroPayCardService($sandbox, $x_login, $x_trans_key);

//Cardholder data
        $x_card_num = $request->numero_cartao;
        $x_card_code = $request->cvv;
        $x_exp_date = $request->data_expiracao;

//Transaction data
        $additional_params = ['x_description' => $pedido->itens->first()->name_item . " " . $pedido->user->name];
        $idUnique = $pedido->id . "-" . uniqid();
        $x_amount = $valorTotal;
        $x_unique_id =  $idUnique;
        $x_invoice_num = $idUnique;
        $x_currency = 'BRL';

//Making an AUTH_CAPTURE transaction, this method response has the result
        $dados_pagamento = [];
        $response = $ap->auth_capture_transaction($x_card_num, $x_card_code, $x_exp_date, $x_amount, $x_unique_id, $x_invoice_num, $x_currency, $additional_params);
        if($response != false) {
            $response = json_decode($response);

            $dados_pagamento['response_code'] = $response->response_code;
            $dados_pagamento['response_subcode'] = $response->response_subcode;
            $dados_pagamento['response_reason_code'] = $response->response_reason_code;
            $dados_pagamento['response_reason_text'] = $response->response_reason_text;
            $dados_pagamento['approval_code'] = $response->approval_code;
            $dados_pagamento['x_amount'] = $response->x_amount;
            $dados_pagamento['md5_hash'] = $response->md5_hash;

//Evaluate if the transaction was succesfull or not
            if ($response->response_code == 1) {
                if (convertDoubleGeral($x_amount) == convertDoubleGeral($response->x_amount)) {
                    Log::info("Astropay Card: Pagamento aprovado");
                    DB::beginTransaction();
                    try {
                        //atualizo os dados do pagamento
                        $pedido->dadosPagamento->documento = 'Confirmação automática AstroPay Card via sistema';
                        $pedido->dadosPagamento->status = 2; //pago
                        $pedido->dadosPagamento->data_pagamento = Carbon::now();
                        $pedido->dadosPagamento->data_pagamento_efetivo = Carbon::now();
                        $pedido->dadosPagamento->metodo_pagamento_id = 11; //astropay card
                        $pedido->dadosPagamento->invoice_id = $response->x_invoice_num;
                        $pedido->dadosPagamento->transaction_id = $response->TransactionID;
                        $pedido->dadosPagamento->taxa_valor = $valorTaxa;
                        $pedido->dadosPagamento->ultimo_request_astropay = Carbon::now();
                        $pedido->dadosPagamento->responsavel_user_id = 1;
                        $pedido->dadosPagamento->dados_pagamento = $dados_pagamento;
                        $pedido->dadosPagamento->save();

                        //atualizo o pedido
                        $pedido->status = 2; //pedido pago
                        $pedido->save();

                        Log::info("DadosPagamento: Atualizado para status 2 (pedido pago)");
                        Log::info('Iniciado o procedimento de efetivação do pagamento ($pagamento->efetivarPagamento())');

                        $pagamento = new Pagamentos($pedido);

                        $erros = $pagamento->efetivarPagamento();

                        if ($erros > 0) {
                            DB::rollback();
                            Log::error('Houve erros na efetivação do pagamento');
                            Log::info("Fim de pagamento de depósito via AstroPay Card");
                            Log::info("**************");
                            flash()->error('Não foi possível prosseguir com o pagamento.<br>Se persistir contate o suporte técnico.');
                            return \redirect()->route('pedido.usuario.pedido', [Auth::user()->id, $pedido->id]);
                        } else {
                            DB::commit();
                            Log::info('Pagamento efetuado com sucesso');
                            Log::info("Fim de pagamento de depósito via AstroPay Card");
                            Log::info("**************");
                            flash()->success('Pagamento efetuado com sucesso!');
                            return \redirect()->route('depositos.confirmados', Auth::user()->id);
                        }
                    } catch (ModelNotFoundException $e) {
                        DB::rollback();
                        Log::error('Houve erros na efetivação do pagamento. ' . $e->getMessage());
                        Log::info("Fim de pagamento de depósito via AstroPayCard");
                        Log::info("**************");
                        flash()->error('Não foi possível prosseguir com o pagamento.<br>Se persistir contate o suporte técnico.');
                        return \redirect()->route('pedido.usuario.pedido', [Auth::user()->id, $pedido->id]);
                    }
                } else {
                    //a transação foi aprovada, mas os valores divergem
                    DB::beginTransaction();
                    try {
                        $pedido->dadosPagamento->status = 4; //status de aguardando confirmação
                        //$pedido->dadosPagamento->data_vencimento = $boleto['data_vencimento'];
                        $pedido->dadosPagamento->metodo_pagamento_id = 11; //astropay card
                        $pedido->dadosPagamento->invoice_id = $response->x_invoice_num;
                        $pedido->dadosPagamento->transaction_id = $response->TransactionID;
                        $pedido->dadosPagamento->ultimo_request_astropay = Carbon::now();
                        $pedido->dadosPagamento->dados_pagamento = $dados_pagamento;
                        $pedido->dadosPagamento->taxa_valor = $valorTaxa;
                        $pedido->dadosPagamento->save();

                        //atualizo o pedido
                        $pedido->status = 4; //aguardando confirmação
                        $pedido->save();

                        DB::commit();

                        flash()->warning('O valor aprovado pelo AstroPay Card difere do total do depósito.<br>Por favor contate o suporte técnico para prosseguir com o depósito.');
                        return redirect()->route('pedido.usuario.pedido', [$request->user_id, $request->pedido_id]);
                    } catch (Exception $e) {
                        DB::rollBack();
                        flash()->error('Ocorreu um erro ao processar seu depósito.<br>Se o erro persistir, por favor contate o suporte técnico.');
                        return redirect()->route('pedido.usuario.pedido', [$request->user_id, $request->pedido_id]);
                    }
                }
            } else {
                $msg = "Seu depósito não pode ser processado.<br>Se o erro persistir, por favor contate o suporte técnico";
                if($response->response_code == 2 && $response->response_subcode == 2 && $response->response_reason_code == 2)
                    $msg = "Seu depósito não pode ser processado.<br>Fundos insuficientes no cartão.";
                elseif($response->response_code == 2 && $response->response_subcode == 3 && $response->response_reason_code == 6)
                    $msg = "Seu depósito não pode ser processado.<br>O número do cartão AstroPay é inválido.";
                elseif($response->response_code == 2 && $response->response_subcode == 3 && $response->response_reason_code == 7)
                    $msg = "Seu depósito não pode ser processado.<br>O cartão AstroPay foi bloqueado.";
                elseif($response->response_code == 3 && $response->response_subcode == 3 && $response->response_reason_code == 7)
                    $msg = "Seu depósito não pode ser processado.<br>A data de validade do cartão é inválida.";
                elseif($response->response_code == 3 && $response->response_subcode == 3 && $response->response_reason_code == 8)
                    $msg = "Seu depósito não pode ser processado.<br>O cartão expirou.";
                elseif($response->response_code == 3 && $response->response_subcode == 3 && $response->response_reason_code == 11)
                    $msg = "Seu depósito não pode ser processado.<br>Uma transação duplicada foi enviada, aguarde 3 minutos e tente novamente.";
                elseif($response->response_code == 3 && $response->response_subcode == 3 && $response->response_reason_code == 78)
                    $msg = "Seu depósito não pode ser processado.<br>O código do cartão (CVV) é inválido.";

                flash()->error($msg);
                return redirect()->back();
            }
        }else{
            flash()->error("Ocorreu um erro ao conectar com a AstroPay Card.<br>Por favor tente novamente mais tarde.<br>Caso o erro persista, entre em contato com o suporte técnico.");
            return redirect()->back();
        }

//Use only in "string" format
        //$response = explode("|", $response);

//Clasify the response data
        //$response_code = $response[0];
        //$response_subcode = $response[1];
        //$response_reason_code = $response[2];
        //$response_reason_text = $response[3];
        //$response_authorization_code = $response[4];
        //$response_transaction_id = $response[6];
        //$response_amount = $response[10];

        //In case of "json" format

//**For VOID or REFUND a transaction**
//$x_trans_id = "546502";
//$response = $ap->void_transaction($x_trans_id, $x_card_num, $x_card_code, $x_exp_date, $x_amount);
//$response = $ap->refund_transaction($x_trans_id, $x_card_num, $x_card_code, $x_exp_date, $x_amount);

//**For transaction status check**
//$response = $ap->check_transaction_status($x_invoice_num, 1);

//var_dump($response);
    }
}
