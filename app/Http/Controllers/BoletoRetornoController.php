<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Log;
use App\Models\Boletos;
use App\Models\Sistema;
use Illuminate\Http\Request;
use App\Events\PedidoFoiPago;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Eduardokum\LaravelBoleto\Cnab\Retorno\Factory;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BoletoRetornoController extends Controller
{
    private $sistema;

    public function __construct()
    {
        $this->middleware('permission:master|admin');
        $this->sistema = Sistema::findOrFail(1);
    }

    public function retorno()
    {
        return view('default.boletoRetorno.index', [
                'title' => 'Receber Boleto',
            ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function processarRetorno(Request $request)
    {
        $file = fopen(storage_path('app/retorno_boleto/'.date('d-m-Y_H-i-s').'_id'.Auth::user()->id.'.txt'), 'w');

        try {
            DB::beginTransaction();

            foreach ($request->file('retorno') as $arquivoRetorno) {
                $nomeArquivo = $arquivoRetorno->getClientOriginalName();

                fwrite($file, "$$$$$$$$$$$$$ Inicia pagamento Boleto - arquivo $nomeArquivo $$$$$$$$$$$$$\r\n");

                Log::info("\n\n $$$$$$$$$ Inicia pagamento Boleto - arquivo $nomeArquivo $$$$$$$$$$$$$");

                $retorno = Factory::make($arquivoRetorno->getRealPath());
                $retorno->processar();
                $detalhes = $retorno->getDetalhes();

                //TODO contador de pedidos pagos
                $pago = 0;
                $pedidosPagos = '';
                $msg = '';

                //TODO anda pelos registro do retorno
                foreach ($detalhes as $detalhe) {
                    $msg = '';
                    if ($detalhe->hasOcorrencia('06')) {
                        if ($detalhe->getValorRecebido() > 0) {
                            $nossoNumero = $detalhe->getNossoNumero();
                            $numeroDocumento = $detalhe->getNumeroDocumento();
                            $valorRecebido = $detalhe->getValorRecebido();
                            $dataCredito = $detalhe->getDataCredito();
                            $dataPagamento = $detalhe->getDataOcorrencia();

                            //TODO instancia pedido
                            $boleto = Boletos::with('pedido', 'mensalidade')->where('nosso_numero', $nossoNumero)->first();

                            if ($boleto) {
                                $pedido = $boleto->getRelation('pedido');

                                if ($pedido) {
                                    $pedido->load('user');

                                    Log::info('##########################################');
                                    Log::info('Nosso numero #'.$nossoNumero);
                                    Log::info('retorno boleto Pagar pedido #'.$pedido->id);
                                    Log::info('Valor pago {{ $sistema->moeda }}'.$valorRecebido);

                                    $msg .= "\r\n Adesão #$pedido->id \r\n";
                                    $msg .= 'Usuário - #'.$pedido->getRelation('user')->id.' - '.$pedido->getRelation('user')->name."\r\n";
                                    $msg .= "Data do pagamento $dataPagamento \r\n";
                                    $msg .= "Data do Credito $dataCredito \r\n";
                                    $msg .= "Boleto #$nossoNumero \r\n";
                                    $msg .= "Valor pago {{ $sistema->moeda }} $valorRecebido \r\n";

                                    if ($pedido->status == 4 && $pedido->valor_total == $valorRecebido) {
                                        $dadosPagamento = $pedido->load('dadosPagamento')->getRelation('dadosPagamento');

                                        if ($dadosPagamento->metodo_pagamento_id == 1 && $dadosPagamento->status == 4) {
                                            if ($valorRecebido >= $dadosPagamento->valor) {

                                                    //TODO seta pedido como pago
                                                $pedido->status = 2;
                                                $pedido->save();

                                                //TODO seta dados de pagamento
                                                    $dadosPagamento->status = 2; //liberação sistema
                                                    $dadosPagamento->data_pagamento = implode('-', array_reverse(explode('/', $dataCredito)));
                                                $dadosPagamento->responsavel_user_id = Auth::user()->id;
                                                $dadosPagamento->documento = 'Boleto do pedido #'.$pedido->id.' confirmado!';

                                                $dadosPagamento->save();

                                                //TODO dispara eventos de compra
                                                $erros = $this->efetivarPagamento($pedido);

                                                if ($erros > 0) {
                                                    DB::rollback();
                                                    $msg .= "Houve erros no pagamento \r\n";
                                                    Log::info('Houve erros no pagamento');
                                                    flash()->error('Houve alguns erros no processamento do pagamento!');
                                                    fwrite($file, $msg);
                                                    fopen($file);

                                                    return redirect()->back();
                                                } else {
                                                    $pago++;
                                                    $pedidosPagos .= $pedido->id.',';
                                                    $msg .= "Pagamento efetivado com sucesso! \r\n";
                                                    Log::info('pagamento OK #'.$pedido->id);
                                                }
                                            } else {
                                                flash()->error('Valor pago é menor do que o valor devido');
                                                $msg .= "Valor pago é menor do que o valor da adesão! \r\n";
                                                Log::info('Valor pago é menor do que o valor devido, pedido #'.$pedido->id);
                                                fwrite($file, $msg);
                                                fopen($file);

                                                return redirect()->back();
                                            }
                                        } else {
                                            $msg .= "Pedido não esta aguardando mais o pagamento \r\n";
                                            Log::info('Pedido não esta aguardando mais o pagamento - dados não esperam pagamentos');
                                        }
                                    } else {
                                        $msg .= "Pedido não esta aguardando mais o pagamento \r\n \r\n";
                                        Log::info('Pedido não esta aguardando mais o pagamento');
                                    }

                                    fwrite($file, $msg);
                                } else {
                                    $mensalidade = $boleto->getRelation('mensalidade');

                                    if ($mensalidade) {
                                        $mensalidade->load([
                                                'contrato' => function ($query) {
                                                    $query->select([
                                                        'id',
                                                        'user_id',
                                                    ]);
                                                },
                                            ]);

                                        $mensalidade->getRelation('contrato')->load([
                                                'usuario' => function ($query) {
                                                    $query->select([
                                                        'id',
                                                        'name',
                                                        'username',
                                                    ]);
                                                },
                                            ]);

                                        $contrato = $mensalidade->getRelation('contrato');

                                        Log::info('##########################################');
                                        Log::info('Nosso numero #'.$nossoNumero);
                                        Log::info('retorno boleto Pagar mensalidade #'.$mensalidade->id);
                                        Log::info("Referente a $mensalidade->mes_referencia/$mensalidade->ano_referencia");
                                        Log::info("Parcela $mensalidade->parcela");
                                        Log::info('Valor pago {{ $this->sistema->moeda }}'.$valorRecebido);

                                        $msg .= "\r\n Mensalidade #$mensalidade->id \r\n";
                                        $msg .= 'Usuário - #'.$contrato->getRelation('usuario')->id.' - '.$contrato->getRelation('usuario')->name."\r\n";
                                        $msg .= 'Contrato - #'.$contrato->id."\r\n";
                                        $msg .= "Boleto #$nossoNumero \r\n";
                                        $msg .= "Referente a $mensalidade->mes_referencia/$mensalidade->ano_referencia \r\n";
                                        $msg .= "Parcela $mensalidade->parcela \r\n";
                                        $msg .= "Data Pagamento $dataPagamento \r\n";
                                        $msg .= "Data Credito $dataCredito \r\n";
                                        $msg .= "Valor pago {{ $this->sistema->moeda }} $valorRecebido \r\n";

                                        if (in_array($mensalidade->statusPivot, [1, 2, 3])) {
                                            //TODO seta pedido como pago
                                            $dados = [

                                                    'status'              => 4,
                                                    'valor_pago'          => $valorRecebido,
                                                    'metodo_pagamento_id' => 1,
                                                    //'dt_pagamento'        => $dataPagamento,
                                                    'dt_baixa'            => $dataCredito,
                                                ];

                                            $mensalidade->update($dados);
                                            $pago++;

                                            Log::info('Mensalidade paga com sucesso!o');

                                            $msg .= "Mensalidade paga com sucesso! \r\n";
                                        } else {
                                            $msg .= "Mensalidade não esta aguardando mais o pagamento \r\n";
                                            Log::info('Mensalidade não esta aguardando mais o pagamento');
                                        }
                                    } else {
                                        $msg .= "Mensalidade não foi encontrada #$numeroDocumento \r\n";
                                        Log::info('Mensalidade não foi encontrada #'.$numeroDocumento);
                                    }
                                    fwrite($file, $msg);
                                }
                            }
                            // você já tem as informações, pode dar baixa no boleto aqui
                        } else {
                            $msg .= "Valor recebido é menor ou igual a 0, ou fim do boletoo \r\n";
                            fwrite($file, $msg);
                            Log::info('Valor recebido é menor ou igual a 0, ou fim do boleto');
                        }
                    }
                }

                fwrite($file, "\r\n Fim pagamento Boleto - arquivo $nomeArquivo \r\n");
                fwrite($file, "$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$\r\n\r\n");
            }

            DB::commit();

            fwrite($file, "\r\n $$$$$$$$$$$$$ Processados boletos com sucesso $$$$$$$$$$$$$");
            fclose($file);

            //TODO deletar arquivos antigos após 3 meses
            $arquivosAnteriores = scandir(storage_path('app/retorno_boleto/'));
            foreach ($arquivosAnteriores as $item) {
                $ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));
                if ($ext == 'txt') {
                    if (filectime(storage_path("app/retorno_boleto/$item")) < strtotime('-3 month')) {
                        unlink(storage_path("app/retorno_boleto/$item"));
                    }
                }
            }

            Log::info('****************************');
            Log::info("Processados boletos com sucesso \n");
            flash()->success('Processados boletos com sucesso');

            return redirect()->back();
        } catch (ModelNotFoundException $e) {
            fclose($file);
            DB::rollback();
            flash()->error('Erro ao processar o boleto');
        }
    }

    private function efetivarPagamento($pedido)
    {
        try {
            Log::info('@@@@@@@@@@@@@@@@@@@  Rodar sistema apos pagamento @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@');
            $respostaEventos = \Event::fire(new PedidoFoiPago($pedido));
            $count = 0;

            if (count($respostaEventos) == 10) {
                foreach ($respostaEventos as $key => $respostas) {
                    if (! $respostas) {
                        Log::info('Erro no evento #'.$key);
                        $count++;
                    }

                    /*if ($key == 1) {
                        $posicionou = false;
                        if ($respostas instanceof RedeBinaria) {
                            if ($respostas->esquerda == $pedido->getRelation('user')->id) {
                                $posicionou = true;
                            } elseif ($respostas->direita == $pedido->getRelation('user')->id) {
                                $posicionou = true;
                            }


                    /*if ($key == 1) {
                    $posicionou = false;
                    if ($respostas instanceof RedeBinaria) {
                        if ($respostas->esquerda == $pedido->getRelation('user')->id) {
                            $posicionou = true;
                        } elseif ($respostas->direita == $pedido->getRelation('user')->id) {
                            $posicionou = true;
                        }

                        if ($pedido->getRelation('user')->id > 2) {

                            $testeRede = RedeBinaria::where('esquerda', $pedido->getRelation('user')->id)->orWhere('direita', $pedido->getRelation('user')->id)->first();

                            Log::info('Carregou teste posicionamento', $testeRede->toArray());

                            if (($respostas->esquerda == $testeRede->esquerda) || ($respostas->direita == $testeRede->direita)) {
                                $posicionou = true;
                            } elseif (($respostas->user_id == $testeRede->esquerda) || ($respostas->user_id == $testeRede->direita)) {
                                $posicionou = true;
                            }

                        } else {
                            Log::info('pedido da Galaxy');
                            $posicionou = true;
                        }
                    }
                    if (!$posicionou) {
                        $count++;
                        Log::info('Não posicionou %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%');
                    } else {
                        Log::info('Posicionou corretamente &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&');
                    }
                    }*/
                }
            } else {
                $count++;
            }

            return $count;
        } catch (ModelNotFoundException $e) {
            Log::error('Erro ao efetivar pagamento!');

            return 1;
        }
    }
}
