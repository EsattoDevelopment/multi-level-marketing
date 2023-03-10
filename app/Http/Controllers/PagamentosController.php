<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Event;
use Illuminate\Http\RedirectResponse;
use Log;
    use Carbon\Carbon;
    use App\Models\User;
    use App\Models\Boletos;
    use App\Models\Pedidos;
    use App\Models\Sistema;
    use App\Services\Cotacao;
    use App\Models\Movimentos;
    use App\Events\RodarSistema;
    use App\Services\Pagamentos;
    use Illuminate\Http\Request;
    use mysql_xdevapi\Exception;
    use App\Models\ContasEmpresa;
    use App\Models\DadosPagamento;
    use App\Models\MetodoPagamento;
    use App\Services\BoletoService;
    use App\Jobs\SendPagamentoEmail;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Auth;
    use App\Jobs\SendPedidoConfirmadoEmail;
    use Illuminate\Support\Facades\Storage;
    use App\Services\BoletoGerencianetService;
    use Eduardokum\LaravelBoleto\Boleto\Render\Pdf;
    use Illuminate\Database\Eloquent\ModelNotFoundException;

class PagamentosController extends Controller
{
    private $pedido;
    private $sistema;

    public function __construct()
    {
        $this->middleware('manipularOutro', [
                'except' => [
                    'produtoPago',
                    'pagamentoSistema',
                    'verificarPagamento',
                    'confirmarTransactionGatewayPagamento',
                    'pagamentoPedidoConsultor',
                    'pagarComBoleto',
                ],
            ]
        );
        $this->middleware('permission:master|admin', ['only' => ['pagamentoSistema', 'produtoPago']]);
        $this->sistema = Sistema::findOrFail(1);
    }

    public function produtoPago(): void
    {
        Event::fire(new RodarSistema());
    }

    private function houveErros($erros): bool
    {
        if ($erros > 0) {
            DB::rollback();
            return true;
        }
        DB::commit();
        return false;
    }

    private function temBoleto(): bool
    {
        $retorno = false;
        if (! is_null($this->pedido->getRelation('boleto'))) {
            $retorno = true;
        }
        return $retorno;
    }

    public function pagamentoSistema($id, Request $request)
    {
        Log::info('##########################################');
        Log::warning('Pagar pedido pelo sistema pedido #'.$id);

        try {
            DB::beginTransaction();

            $this->pedido = Pedidos::with('dadosPagamento', 'user', 'itens')->findOrFail($id);
            $metodo_pagamento_id = $request->get('metodo_pagamento_id');
            $bancoId = 0;
            $pos = strpos($metodo_pagamento_id, '-');

            if ($pos > 0) {
                $array = explode('-', $metodo_pagamento_id);
                $bancoId = $array[1];
                $metodo_pagamento_id = 9; //?? por ted e contem o numero do banco que foi efetuado o deposito
            }

            $cotacaoMoeda = (new Cotacao())->dolar();
            $cotacaoMoeda = str_replace(',', '.', $cotacaoMoeda);
            $cotacaoMoeda = floatval($cotacaoMoeda);

            $this->pedido->getRelation('dadosPagamento')->documento = $request->get('descricao');
            $this->pedido->getRelation('dadosPagamento')->status = 2;
            $this->pedido->getRelation('dadosPagamento')->valor_autorizado_diretoria = $this->pedido->getRelation('dadosPagamento')->valor;
            $this->pedido->getRelation('dadosPagamento')->valor_efetivo = $this->pedido->getRelation('dadosPagamento')->valor;
            $this->pedido->getRelation('dadosPagamento')->metodo_pagamento_id = $metodo_pagamento_id; //libera????o sistema
            if ($bancoId != 0) {
                $this->pedido->getRelation('dadosPagamento')->conta_empresa_id = $bancoId;
            }
            $this->pedido->getRelation('dadosPagamento')->cotacao_dolar_dia_compra = $cotacaoMoeda;
            $this->pedido->getRelation('dadosPagamento')->cotacao_dolar_dia_efetivo = $cotacaoMoeda;
            $this->pedido->getRelation('dadosPagamento')->data_pagamento = implode('-', array_reverse(explode('/', $request->get('data_pagamento'))));
            $this->pedido->getRelation('dadosPagamento')->data_pagamento_efetivo = implode('-', array_reverse(explode('/', $request->get('data_pagamento'))));
            $this->pedido->getRelation('dadosPagamento')->responsavel_user_id = Auth::user()->id;
            $this->pedido->getRelation('dadosPagamento')->save();

            Log::info('Pagar pedido pelo sistema');

            $this->pedido->status = 2;
            $this->pedido->save();

            $pagamento = new Pagamentos($this->pedido);

            $erros = $pagamento->efetivarPagamento();

            if ($this->houveErros($erros)) {
                Log::info('Houve erros no pagamento');
                Log::info('');
                flash()->error('Houve alguns erros no processamento do pagamento!');
                return redirect()->route('pedido.show', $this->pedido->id);
            }

            Log::info('pagamento OK #'.$this->pedido->id);
            Log::info('');

            $msg = 'Deposito conferido com sucesso';
            // se tiver boleto associado a este dep??sito eu cancelo
            if (isset($request->cancelar_boleto)) {
                $charge_id = $this->pedido->getRelation('dadosPagamento')->dados_boleto['charge_id'];
                $boletoService = new BoletoGerencianetService($this->pedido->id, false);
                $boletoCancelamento = $boletoService->cancelarBoleto($charge_id, true);
                if ($boletoCancelamento['status'] == true) {
                    $this->pedido->getRelation('dadosPagamento')->update(['dados_boleto' => $boletoCancelamento]);
                    $msg .= '<br>O boleto associado a esse pedido foi cancelado com sucesso';
                } else {
                    $msg .= '<br>N??o foi poss??vel cancelar o boleto associado a esse pedido, cancele manualmente na painel da Gerencianet.';
                }
            }

            flash()->success($msg);

            return redirect()->route('pedido.index');
        } catch (ModelNotFoundException $e) {
            flash()->success('Erro ao pagar pedido!');

            return redirect()->route('pedido.show', $this->pedido->id);
        }
    }

    public function pagamentoPedidoConsultor($pedido_id)
    {
        Log::info('##########################################');
        Log::warning('Pagar pedido de agente #'.$pedido_id);

        try {
            DB::beginTransaction();

            $this->pedido = Pedidos::with('dadosPagamento', 'user', 'itens')->findOrFail($pedido_id);

            $movimento = Movimentos::whereUserId($this->pedido->user->id)->orderBy('id')->get();

            if (! $movimento) {
                DB::rollBack();
                flash()->success('N??o h?? saldo suficiente para realizar o pagamento!');
                \Illuminate\Support\Facades\Log::info('N??o h?? saldo suficiente para realizar o pagamento!');

//                        return redirect()->back();
                return false;
            }

            $movimento = $movimento->last();

            Log::info('Saldo antes pagamento, {{ $sistema->moeda }}'.$movimento->saldo);
            Log::info('Valor pedido, {{ $sistema->moeda }}'.$this->pedido->valor_total);
            Log::info('Movimento antes pagamento', $movimento->toArray());
            // pagamento dos bonus
            $dadosMovimento = [
                'valor_manipulado'    => $this->pedido->valor_total,
                'saldo_anterior'      => $movimento->saldo,
                'saldo'               => $movimento->saldo - $this->pedido->valor_total,
                'pedido_id'          => $this->pedido->id,
                'documento'           => '',
                'descricao'           => 'Pagamento do Contrato n?? '.$this->pedido->id.' - '.$this->pedido->item()->name,
                'responsavel_user_id' => $this->pedido->user->id,
                'user_id'             => $this->pedido->user->id,
                'operacao_id'         => 12,
            ];

            $movimentoAtual = Movimentos::create($dadosMovimento);
            Log::info('Saldo apos pagamento, {{ $sistema->moeda }}'.$movimentoAtual->saldo);
            Log::info('Movimento apos pagamento', $movimentoAtual->toArray());

            /*$cotacaoMoeda = (new Cotacao())->dolar();
            $cotacaoMoeda = str_replace(',', '.', $cotacaoMoeda);
            $cotacaoMoeda = floatval($cotacaoMoeda);*/
            $cotacaoMoeda = 0;

            $this->pedido->getRelation('dadosPagamento')->documento = 'Libera????o automatica via sistema';
            $this->pedido->getRelation('dadosPagamento')->status = 2;
            $this->pedido->getRelation('dadosPagamento')->valor_autorizado_diretoria = $this->pedido->getRelation('dadosPagamento')->valor;
            $this->pedido->getRelation('dadosPagamento')->valor_efetivo = $this->pedido->getRelation('dadosPagamento')->valor;
            $this->pedido->getRelation('dadosPagamento')->metodo_pagamento_id = 7; //libera????o sistema
            $this->pedido->getRelation('dadosPagamento')->cotacao_dolar_dia_compra = $cotacaoMoeda;
            $this->pedido->getRelation('dadosPagamento')->cotacao_dolar_dia_efetivo = $cotacaoMoeda;
            $this->pedido->getRelation('dadosPagamento')->data_pagamento = Carbon::now()->format('Y-m-d H:i:s');
            $this->pedido->getRelation('dadosPagamento')->data_pagamento_efetivo = Carbon::now()->format('Y-m-d H:i:s');
            //$this->pedido->getRelation('dadosPagamento')->responsavel_user_id = Auth::user()->id;
            $this->pedido->getRelation('dadosPagamento')->save();

            Log::info('Pagar pedido de agente pelo sistema');

            $this->pedido->status = 2;
            $this->pedido->save();

            $pagamento = new Pagamentos($this->pedido);

            $erros = $pagamento->efetivarPagamento();

            if ($this->houveErros($erros)) {
                Log::info('Houve erros no pagamento automatico de pedido de agente');
                Log::info('');
                flash()->error('Solicita????o para se tornar um agente recebida com sucesso.<br>Por favor aguarde libera????o do sistema!');
            } else {
                Log::info('pagamento automatico de agente OK #'.$this->pedido->id);
                Log::info('');
                flash()->success('Parab??ns!<br>Agora voc?? ?? um '.$this->pedido->itens->first()->name_item);
            }

            return redirect()->route('home');
        } catch (ModelNotFoundException $e) {
            flash()->error('Solicita????o para se tornar um agente recebida com sucesso.<br>Por favor aguarde libera????o do sistema!');

            return redirect()->route('home');
        }
    }

    /**
     * faz o pagamento do pedido conforme o metodo.
     *
     * @param Request $request
     * @param         $user
     * @param         $id
     *
     * @return RedirectResponse|string
     */
    public function pagarPedido(Request $request, $user, $id)
    {
        Log::info('Entrou pagar pedido #'.$id);
        try {
            $this->pedido = Pedidos::with('dadosPagamento', 'boleto')->whereId($request->get('pedido_id'))->first();

            switch ($request->get('metodo_pagamento')) {
                case 1:
                    DB::beginTransaction();
                    Log::info('Pagamento Boleto');

                    $boletoService = new BoletoService($request->get('user_id'));

                    $vencimento = Carbon::parse(implode('-', array_reverse(explode('/', $request->get('data_vencimento')))));

                    // verifica se tem boleto
                    if (! $this->temBoleto()) {
                        // separa ID do boleto
                        $boletoCreated = Boletos::create(['vencimento' => $vencimento]);
                    } else {
                        $boletoCreated = $this->pedido->getRelation('boleto');
                    }

                    $dadosPagamento = [
                        'vencimento'  => $vencimento,
                        'valor'       => $this->pedido->valor_total,
                        'contrato_id' => $boletoCreated->id,
                        'boleto_id'   => $boletoCreated->id,
                        //'nParcela'    => $this->pedido->user_id,
                        'parcela'     => '1/1',
                    ];

                    $boleto = $boletoService->montarBoleto($dadosPagamento);

                    // faz update se boleto foi gerado agora
                    if (! $this->temBoleto()) {
                        $boletoCreated->update([
                            'codigo_de_barras' => $boleto->getCodigoBarras(),
                            'nosso_numero'     => $boleto->getNossoNumero(),
                            'numero_documento' => $boleto->getNumeroDocumento(),
                        ]);
                    }

                    // salva dados para pagamento e vincula boelto ao pedido
                    if (! $this->pedido->boleto_id) {
                        Log::info('Gerado boleto:', ['pedido' => $id, 'codigo_de_barras' => $boleto->getCodigoBarras(), 'user a????o' => Auth::user()->id]);

                        // salva ID do boleto no pedido e muda status do pedido
                        $this->pedido->boleto_id = $boletoCreated->id;
                        $this->pedido->status = 4;
                        $this->pedido->save();

                        $this->pedido->getRelation('dadosPagamento')->status = 4;
                        $this->pedido->getRelation('dadosPagamento')->metodo_pagamento_id = 1;
                        $this->pedido->getRelation('dadosPagamento')->save();

                        DB::commit();
                    }

                    $pdf = new Pdf();
                    $pdf->addBoleto($boleto);
                    flash()->success('Boleto gerado com sucesso!');
                    $pdf->gerarBoleto('I', $boleto->getNossoNumero().'.pdf');

                    break;

                case 6:
                    Log::info('Pagamento Saldo, saldo de #'.Auth::user()->id);

                    $this->pedido = Pedidos::with('dadosPagamento', 'user', 'itens')->findOrFail($request->get('pedido_id'));
                    $valorTotalPedido = $this->pedido->valor_total;

                    $saldo = 0;

                    if (Auth::user()->ultimoMovimento()) {
                        $saldo = Auth::user()->ultimoMovimento()->saldo;
                    }

                    if ($valorTotalPedido > 0 && $this->pedido->itens->first()->item->tipo_pedido_id != 4 && $saldo >= $valorTotalPedido) { /*?? um pedido covencional, tipo 3 ?? agente*/

                        Log::info('tem saldo');

                        (new Pagamentos($this->pedido))->pagarComSaldo();

                        flash()->success('Parab??ns por sua contrata????o, voc?? escolheu um excelente licen??a!');

                        return redirect()->route('pedidos.confirmados');
                    } else {
                        Log::info('N??o tem saldo');

                        flash()->warning('N??o tem saldo');
                    }

                    return redirect()->back();

                    break;
            }

            return redirect()->back();
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            flash()->error('Desculpe, erro ao pagar o pedido. Tente novamente, se o erro persistir entre em contato conosco!');

            Log::info('Erro ao pagar pedido', ['user' => Auth::user()->id]);

            return redirect()->back();
        }
    }

    public function pagarComBoleto(Request $request)
    {
        //os dados j?? s??o obrigat??rios antes de fazer o pedido, ent??o n??o preciso validar

        $boletoService = new BoletoGerencianetService($request->pedido_id, true);
        /*$boletoService->cancelarBoleto('763471', false);
        dd('pago manualmente');*/
        $boleto = $boletoService->gerarBoleto();

        if ($boleto['status'] == true) {
            //$data = Carbon::now()->format('Y-m-d H:i:s');
            $dadosPagamento = DadosPagamento::where('pedido_id', $request->pedido_id)->first();
            $pedido = Pedidos::where('id', $request->pedido_id)->first();

            DB::beginTransaction();
            try {
                $dadosPagamento->status = 4; //status de aguardando confirma????o
                $dadosPagamento->data_vencimento = $boleto['data_vencimento'];
                $dadosPagamento->metodo_pagamento_id = 1; //boleto
                $dadosPagamento->invoice_id = $boleto['charge_id'];
                $dadosPagamento->tarifa_boleto = $boleto['tarifa_boleto'];
                $dadosPagamento->dados_boleto = $boleto;
                $dadosPagamento->data_geracao_boleto = Carbon::now();
                $dadosPagamento->save();

                //atualizo o pedido
                $pedido->status = 4; //aguardando confirma????o
                $pedido->save();

                DB::commit();

                return redirect()->route('pedido.boleto.visualizar.dados', [$request->pedido_id, 1]);
            } catch (Exception $e) {
                DB::rollBack();

                //excluo o boleto gerado
                //n??o recupero os dados de pagamento, pois nesse caso deu erro na hora de salvar os dados de pagamento, ent??o n??o vai ter os dados de pagamento do boleto
                $boletoService->cancelarBoleto($boleto['charge_id'], false);

                flash()->error('N??o foi poss??vel prosseguir com a gera????o do boleto.<br>Se o erro persistir, por favor contate o suporte t??cnico.');

                return redirect()->route('pedido.usuario.pedido', [$request->user_id, $request->pedido_id]);
            }
        } else {
            $msg = $boleto['header'].'<br>';
            foreach ($boleto as $chave => $value) {
                if (is_numeric($chave)) {
                    $msg .= '   - '.$value.'<br>';
                }
            }
            flash()->error($msg);

            return redirect()->route('pedido.usuario.pedido', [$request->user_id, $request->pedido_id]);
        }
    }

    public function confirmarTed(Request $request, string $id): RedirectResponse
    {
        $log_id = str_pad($id, 10, '0', STR_PAD_LEFT);
        Log::info('[~]==================================================================[~]');
        Log::info("[~]===[ Confirma????o de pagamento por TED/DOC pedido # $log_id ]===[~]");
        Log::info('[~]==================================================================[~]');
        try {
            DB::beginTransaction();
            $this->pedido = Pedidos::with('dadosPagamento', 'user', 'itens')->findOrFail($id);
            $valor_efetivo_real = str_replace('.', '', $request->get('valor_efetivo_real'));
            $valor_efetivo_real = str_replace(',', '.', $valor_efetivo_real);
            $cotacao_dolar_dia_efetivo = $request->get('cotacao_dolar_dia_efetivo');
            $valor_efetivo = str_replace('.', '', $request->get('valor_efetivo'));
            $valor_efetivo = str_replace(',', '.', $valor_efetivo);
            $valor_autorizado_diretoria = str_replace('.', '', $request->get('valor_autorizado_diretoria'));
            $valor_autorizado_diretoria = str_replace(',', '.', $valor_autorizado_diretoria);

            $dados_pagamento = $this->pedido->getRelation('dadosPagamento');
            $dados_pagamento->documento = $request->get('documento');
            $dados_pagamento->status = 2;
            $dados_pagamento->valor_efetivo = $valor_efetivo;
            $dados_pagamento->valor_efetivo_real = $valor_efetivo_real;
            $dados_pagamento->cotacao_dolar_dia_efetivo = $cotacao_dolar_dia_efetivo;
            $dados_pagamento->valor_autorizado_diretoria = $valor_autorizado_diretoria;
            // $dados_pagamento->metodo_pagamento_id = $request->get('metodo_pagamento_id'); // libera????o sistema
            $dados_pagamento->data_pagamento_efetivo = implode('-', array_reverse(explode('/', $request->get('data_pagamento_efetivo'))));
            $dados_pagamento->responsavel_user_id = Auth::user()->id;
            $dados_pagamento->save();
            $this->pedido->status = 2;
            $this->pedido->save();

            $pagamento = new Pagamentos($this->pedido);
            $erros = $pagamento->efetivarPagamento();

            if ($this->houveErros($erros)) {
                Log::info('Houve erros no pagamento');
                Log::info('');
                flash()->error('Houve alguns erros no processamento do pagamento!');
                return redirect()->route('pedido.show', $id);
            }
            Log::info("pagamento OK # {$this->pedido->id}");
            Log::info('');
//            $this->dispatch(new SendPedidoConfirmadoEmail($this->pedido));
            flash()->success('Deposito conferido com sucesso');
            return redirect()->route('pedido.index');
        } catch (ModelNotFoundException|\Exception $e) {
            flash()->success('Erro ao pagar pedido!');
            return redirect()->route('pedido.show', $id);
        }
    }

    public function verificarPagamento(Request $request)
    {
        $pedidoLocal = Pedidos::with('dadosPagamento', 'user', 'itens')->findOrFail($request->pedido_id);
        //$cotacaoMoeda = (new Cotacao())->dolar();
        //$cotacaoMoeda = str_replace(',', '.', $cotacaoMoeda);
        //$cotacaoMoeda = floatval($cotacaoMoeda);
        //$valorEmReais = floatval($pedidoLocal->getRelation('dadosPagamento')->valor * $cotacaoMoeda);

        return view('default.pagamentos.verificar-pagamento', [
            'title'          => 'Verifica????o de pagamento',
            'pedido' => $pedidoLocal,
            'metodoPagamento' => MetodoPagamento::findOrFail($request->metodo_pagamento_id),
            //verificar como fazer para executar esse comando apenas se houver essa variavel
            'contasTed'    => ContasEmpresa::with('banco')->find($request->conta_empresa_id),
            'dados'         => $request,
            //'cotacaoMoeda' => $cotacaoMoeda,
            //'valorEmReais' => $valorEmReais,
        ]);
    }

    public function confirmarTransactionGatewayPagamento(Request $request)
    {
        $msg = '';
        $acao = ''; /*ativar = pagamento efetuado, retornar = ocorreu algum erro no pagamento ou com a chave*/

        switch ($request->metodo_pagamento_id) {
            case 9:/*transferencia bancaria*/
                $data = Carbon::now()->format('Y-m-d H:i:s');
                //$dataHora = new \DateTime();
                $dataHora = str_replace(':', '-', $data);
                $dataHora = str_replace(' ', '_', $dataHora);
                //$metodoPagamento = MetodoPagamento::findOrFail($request->metodo_pagamento_id);
                $dadosPagamento = DadosPagamento::where('pedido_id', $request->pedido_id)->first();
                $pedido = Pedidos::find($request->pedido_id);

                $acao = 'retornar';

                if (! $request->hasFile('path_comprovante_ted')) {
                    $msg = 'O comprovante de transfer??ncia deve ser informado!';
                    break;
                }

                //falta verificar a extens??o do arquivo no lado do servidor
                Log::info('Inicio envio do comprovante de TED pedido '.$pedido->id);
                try {
                    DB::beginTransaction();

                    //se existir um comprovante com o mesmo nome ele ?? deletado
                    if (Storage::exists('comprovantes/'.$dadosPagamento->path_comprovante_ted)) {
                        Storage::delete('comprovantes/'.$dadosPagamento->path_comprovante_ted);
                    }

                    $nameImage = "comprovante_ted_user_{$pedido->user_id}_pedido_{$pedido->id}_{$dataHora}.".strtolower($request->file('path_comprovante_ted')->getClientOriginalExtension());
                    $request->file('path_comprovante_ted')->move(Storage::disk('interno')->getDriver()->getAdapter()->getPathPrefix().'/comprovantes', $nameImage);

                    /* utilizado quando era valor em dolar
                    $valor_real = str_replace('.', '', $request->valor_real);
                    $valor_real = str_replace(',', '.', $valor_real);
                    $cotacao_dolar_dia = $request->cotacao_dolar_dia_compra;*/

                    //dd("Cota????o: " . $cotacao_dolar_dia . '   Valor: ' . $valor_real);

                    $dadosPagamento->path_comprovante_ted = $nameImage;
                    $dadosPagamento->status = 4; //status de aguardando confirma????o
                    $dadosPagamento->data_pagamento = $data;
                    $dadosPagamento->metodo_pagamento_id = $request->metodo_pagamento_id;
                    /*utilizado quando era dolar
                     * $dadosPagamento->cotacao_dolar_dia_compra = $cotacao_dolar_dia;
                    $dadosPagamento->valor_real = $valor_real;*/

                    $dadosPagamento->conta_empresa_id = $request->conta_empresa_id;
                    $dadosPagamento->save();

                    //atualizo o pedido
                    $pedido->status = 4; //aguardando confirma????o
                    $pedido->save();

                    $user = User::find($pedido->user_id);

                    /* notificar usu??rio e contato sobre o pedido. */
                    $this->dispatch(new SendPagamentoEmail($pedido, $user));

                    DB::commit();

                    Log::info('Comprovante de TED enviado com sucesso pedido '.$pedido->id);
                    flash()->success('Comprovante enviado com sucesso! <br>Seu pedido ser?? confirmado ap??s realizarmos a confer??ncia do seu envio.');

                    if ($pedido->tipo_pedido === 4) {
                        return redirect()->route('depositos.aguardando.conferencia');
                    }

                    return Redirect()->route('pedido.usuario.pedidos', Auth::user()->id);
                } catch (Exception $e) {
                    DB::rollBack();
                    $msg = 'Erro ao efetuar o envio do comprovante. <br>Contate o suporte t??cnico. <br> Descri????o do erro: '.$e;
                }

                break;
        }

        if ($acao == 'ativar') {
        } elseif ($acao == 'retornar') {
            flash()->error($msg);

            return $this->verificarPagamento($request);
        }
    }
}
