<?php

namespace App\Http\Controllers;

use App\Models\DadosPagamento;
use App\Models\MetodoPagamento;
use App\Models\Pedidos;
use App\Services\Pagamentos;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use mysql_xdevapi\Exception;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PaypalController extends Controller
{
    private $api_context;

    public function __construct()
    {
        $paypal_conf = \Config::get('paypal');

        $this->api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret'])
        );

        $this->api_context->setConfig($paypal_conf['settings']);
    }

    public function pagarComPayPal(Request $request)
    {
        Log::info("**************");
        Log::info("Inicio de pagamento de depósito via Paypal");
        $pedido = Pedidos::with('itens', 'dadosPagamento', 'user')->where('id', $request->pedido_id)->first();
        Log::info("Depósito: {$pedido->id}");
        Log::info("Usuario: {$pedido->user->id} - {$pedido->user->name}");
        Log::info("Valor do depósito: {$pedido->dadosPagamento->valor}");

        $pagador = new Payer();

        $pagador->setPaymentMethod('paypal');

        $item_1 = new Item();
        $item_1->setName($pedido->itens->first()->name_item)->setCurrency('BRL')->setQuantity(1)->setPrice($pedido->dadosPagamento->valor);

        $valorTotal = $pedido->dadosPagamento->valor;

        $item_2 = null;

        //verifico se tem valor da taxa
        $metodoPagamento = MetodoPagamento::where('id', 3)->first();
        if($metodoPagamento->taxa_valor > 0 || $metodoPagamento->taxa_porcentagem > 0) {
            $valorTaxa = $metodoPagamento->taxa_valor;
            if($metodoPagamento->taxa_porcentagem > 0)
                $valorTaxa += ($pedido->dadosPagamento->valor * $metodoPagamento->taxa_porcentagem) / 100;

            $pedido->dadosPagamento->taxa_valor = $valorTaxa;
            $valorTotal = $valorTotal + convertDoubleGeral($valorTaxa);
            Log::info("Valor da taxa: " . convertDoubleGeral($valorTaxa));

            $item_2 = new Item();
            $item_2->setName($metodoPagamento->taxa_descricao)->setCurrency('BRL')->setQuantity(1)->setPrice(convertDoubleGeral($valorTaxa));
        }

        $lista_itens = new ItemList();
        if ($item_2 != null)
            $lista_itens->setItems(array($item_1, $item_2));
        else
            $lista_itens->setItems(array($item_1));

        $valor = new Amount();
        $valor->setCurrency('BRL')->setTotal($valorTotal);

        $transacao = new Transaction();
        $transacao->setAmount($valor)->setItemList($lista_itens)->setDescription('Pedido: ' . $pedido->id . " - " . $pedido->user->name);

        $urls_redirecionamento = new RedirectUrls();
        $urls_redirecionamento->setReturnUrl(URL::route('paypal.status', $pedido->id))->setCancelUrl(URL::route('paypal.status', $pedido->id));

        $pagamento = new Payment();
        $pagamento->setIntent('Sale')->setPayer($pagador)->setRedirectUrls($urls_redirecionamento)->setTransactions(array($transacao));

        try {
            $pagamento->create($this->api_context);
        } catch (\PayPal\Exception\PPConnectionException $e) {
            if (\Config::get('app.debug')) {
                Log::error('Paypal: Erro na transação (tempo Limite de Conexão Excedido)');
                flash()->error('Tempo limite de conexão com o PayPal excedido.<br>Se persistir informe o suporte técnico.');
            } else {
                Log::error('Paypal:Erro na transação (Serviço fora do ar)');
                flash()->error('Serviço do PayPal fora do ar, tente novamente mais tarde.');
            }

            Log::info("Fim de pagamento de depósito via Paypal");
            Log::info("**************");
            return \redirect()->back();
        }

        foreach ($pagamento->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $url_redirecionar = $link->getHref();
                break;
            }
        }

        DB::beginTransaction();
        //registro o pagamento
        try {
            $pedido->dadosPagamento->transaction_id = $pagamento->getId();
            $pedido->dadosPagamento->save();
        } catch (Exception $e) {
            DB::rollback();
            Log::error('DadosPagamento: Erro ao salvar o id de transação: ' . $e->getMessage());
            Log::info("Fim de pagamento de depósito via Paypal");
            Log::info("**************");
            flash()->error('Não foi possível prosseguir com o pagamento.<br>Se persistir contate o suporte técnico.');
            return \redirect()->back();
        }

        if (isset($url_redirecionar)) {
            DB::commit();
            Log::info('DadosPagamento: Salvo o id de transação: ' . $pagamento->getId());
            Log::info('Redirecionado para o site do PayPal');
            return Redirect::away($url_redirecionar);
        }

        //se chegou aqui, deu algum erro
        DB::rollback();
        Log::error('Paypal: Erro no redirecionamento para o site');
        Log::info("Fim de pagamento de depósito via Paypal");
        Log::info("**************");
        flash()->error('Não foi possível prosseguir com o pagamento.<br>Se persistir contate o suporte técnico.');
        return \redirect()->back();
    }

    public function statusPagamento($pedido_id){
        Log::info("Procedimentos no site do Paypal concluido.");
        Log::info("Redirecionado para nosso site para a consulta da transação");
        $pedido = Pedidos::with('itens', 'dadosPagamento', 'user')->where('id', $pedido_id)->first();

        $id_pagamento = $pedido->dadosPagamento->transaction_id;

        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
            DB::beginTransaction();
            try {
                $pedido->dadosPagamento->transaction_id = "";
                $pedido->dadosPagamento->save();
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
            }
            Log::error('PayPal: Falha na transação.');
            Log::info("Fim de pagamento de depósito via Paypal");
            Log::info("**************");
            flash()->error('Falha na transação.<br>Se persistir contate o suporte técnico.');
            return \redirect()->route('pedido.usuario.pedido', [Auth::user()->id, $pedido->id]);
        }

        $pagamento = Payment::get($id_pagamento, $this->api_context);
        $execucao_pagamento = new PaymentExecution();
        $execucao_pagamento->setPayerId(Input::get('PayerID'));

        $result = $pagamento->execute($execucao_pagamento, $this->api_context);

        if ($result->getState() == 'approved') {
            Log::info("PayPal: Pagamento aprovado");
            DB::beginTransaction();
            try {
                $pedido->dadosPagamento->documento = 'Confirmação automática PayPal via sistema';
                $pedido->dadosPagamento->status = 2; //pedido pago
                $pedido->dadosPagamento->metodo_pagamento_id = 3; //paypal
                $pedido->dadosPagamento->data_pagamento = Carbon::now();
                $pedido->dadosPagamento->data_pagamento_efetivo = Carbon::now();
                $pedido->dadosPagamento->responsavel_user_id = 1;
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
                    Log::info("Fim de pagamento de depósito via Paypal");
                    Log::info("**************");
                    flash()->error('Não foi possível prosseguir com o pagamento.<br>Se persistir contate o suporte técnico.');
                    return \redirect()->route('pedido.usuario.pedido', [Auth::user()->id, $pedido->id]);
                } else {
                    DB::commit();
                    Log::info('Pagamento efetuado com sucesso');
                    Log::info("Fim de pagamento de depósito via Paypal");
                    Log::info("**************");
                    flash()->success('Pagamento efetuado com sucesso!');
                    return \redirect()->route('depositos.confirmados', Auth::user()->id);
                }
            } catch (ModelNotFoundException $e) {
                DB::rollback();
                Log::error('Houve erros na efetivação do pagamento. ' . $e->getMessage());
                Log::info("Fim de pagamento de depósito via Paypal");
                Log::info("**************");
                flash()->error('Não foi possível prosseguir com o pagamento.<br>Se persistir contate o suporte técnico.');
                return \redirect()->route('pedido.usuario.pedido', [Auth::user()->id, $pedido->id]);
            }
        }

        //se chegou aqui o pagamento não foi aprovado
        DB::beginTransaction();
        try {
            $pedido->dadosPagamento->status = 4; //status de aguardando confirmação
            $pedido->dadosPagamento->data_vencimento = Carbon::now()->addDays(3)->format('Y-m-d H:i:s');
            $pedido->dadosPagamento->metodo_pagamento_id = 3; //Paypal
            $pedido->dadosPagamento->save();

            //atualizo o pedido
            $pedido->status = 4; //aguardando
            $pedido->save();
            DB::commit();
            Log::info('Pagamento ainda não foi aprovado');
            Log::info("DadosPagamento: Atualizado para o Status 4 (aguardando cofirmação de pagamento)");
            Log::info("Fim de pagamento de depósito via Paypal");
            Log::info("**************");
        } catch (Exception $e) {
            DB::rollback();
            Log::info('Pagamento ainda não foi aprovado');
            Log::error("DadosPagamento: Erro ao atualizar status do pagamento para 4. {$e->getMessage()}");
            Log::info("Fim de pagamento de depósito via Paypal");
            Log::info("**************");
            flash()->error('Não foi possível prosseguir com o pagamento.<br>Se persistir contate o suporte técnico.');
            return \redirect()->route('pedido.usuario.pedido', [Auth::user()->id, $pedido->id]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
