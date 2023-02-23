<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

use Carbon\Carbon;
use http\Exception;
use App\Models\Pedidos;
use Gerencianet\Gerencianet;
use App\Models\MetodoPagamento;
use App\Models\EnderecosUsuarios;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Gerencianet\Exception\GerencianetException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

require_once __DIR__.'/../../vendor/autoload.php'; // caminho relacionado a SDK

class BoletoGerencianetService
{
    private $ambiente;
    private $clientId;
    private $clientSecret;
    private $pedido;
    private $dias_vencimento;
    private $usuario;
    private $validacao = [];
    private $valor_reais;
    private $verificarBoletos;
    private $tarifa_boleto;

    public function __construct($pedido_id, $validarDados = true, $verificarBoletos = false)
    {
        $this->verificarBoletos = $verificarBoletos;
        if ($this->verificarBoletos == false) {
            $this->pedido = Pedidos::with('itens.itens', 'dadosPagamento', 'usuario')->find($pedido_id);
            $this->usuario = $this->pedido->getRelation('usuario');

            if ($validarDados) {
                if ($this->validarDadosUsuario()) {
                    $this->validarMetodoPagamento();
                }
            } else {//vai usar somente a função de cancelamento
                $this->validarMetodoPagamento();
            }
        } else {//vai usar somente a verificação dos boletos
            $this->validarMetodoPagamento();
        }
    }

    private function validarDadosUsuario()
    {
        $this->validacao = [];

        //valido o nome
        if (! preg_match('/^[ ]*(.+[ ]+)+.+[ ]*$/', $this->usuario->name)) {
            $this->validacao['0'] = "({$this->usuario->name}) O nome não está em um formato válido";
        }

        //valido o cpf, tem que ter 11 digitos
        $cpf = preg_replace('/[^0-9]/', '', $this->usuario->cpf);
        if (strlen($cpf) != 11) {
            $this->validacao['1'] = "({$this->usuario->cpf}) O CPF é inválido";
        }

        $celular = preg_replace('/[^0-9]/', '', $this->usuario->celular);
        if (! preg_match('/^[1-9]{2}9?[0-9]{8}$/', $celular)) {
            $this->validacao['2'] = "({$this->usuario->celular}) O numero do celular não é válido, deve possuir o DDD + numero do celular (digito 9 é opcional). Ex: (19) 91234-5678";
        }

        if (count($this->validacao) > 0) {
            $this->validacao['status'] = false;
            $this->validacao['header'] = 'Dados inválidos no seu cadastro pessoal. Por favor verifique!';

            return false;
        } else {
            return true;
        }
    }

    private function validarMetodoPagamento()
    {
        $this->validacao = [];

        $metodoPagamento = MetodoPagamento::find(1); //boleto

        if (isset($metodoPagamento->configuracao['ambiente_ativo'])) {
            if ($metodoPagamento->configuracao['ambiente_ativo'] == (env('APP_ENV') == 'local') ? 'H' : 'P') {
                if ($metodoPagamento->configuracao['dias_vencimento'] != '') {
                    $this->dias_vencimento = $metodoPagamento->configuracao['dias_vencimento'];
                } else {
                    $this->dias_vencimento = 3;
                }

                if ($metodoPagamento->configuracao['tarifa_boleto'] != '') {
                    $this->tarifa_boleto = floatval($metodoPagamento->configuracao['tarifa_boleto']);
                } else {
                    $this->tarifa_boleto = floatval('0.00');
                }

                $this->ambiente = $metodoPagamento->configuracao['ambiente_ativo'];

                //deixo para verificar a homologação, para usar na base de testes
                if ($this->ambiente == 'H') {//homologação
                    $this->clientId = $metodoPagamento->configuracao['client_id_homolog'];
                    $this->clientSecret = $metodoPagamento->configuracao['client_secret_homolog'];
                    $this->ambiente = true;
                } elseif ($this->ambiente == 'P') {//Produção
                    $this->clientId = $metodoPagamento->configuracao['client_id_prod'];
                    $this->clientSecret = $metodoPagamento->configuracao['client_secret_prod'];
                    $this->ambiente = false;
                }

                if ($this->clientId == '' || $this->clientSecret == '') {
                    $this->validacao['status'] = false;
                    if ($this->verificarBoletos == false) {
                        Log::error("Erro no metodo de pagamento Boleto, Uma ou mais chave de identificação não foi informada. Pedido #{$this->pedido->id} - Usuário {$this->pedido->getRelation('usuario')->id}");
                        $this->validacao['header'] = 'Não foi possível prosseguir com a geração do boleto.<br>Se o erro persistir, por favor contate o suporte técnico';
                    } else {
                        Log::error('Erro no metodo de pagamento Boleto, Uma ou mais chave de identificação não foi informada. Verificação nos estatos dos boletos');
                    }

                    return false;
                }

                $this->validacao['status'] = true;

                return true;
            } else {
                $this->validacao['status'] = false;
                if ($this->verificarBoletos == false) {
                    Log::error("Erro no metodo de pagamento Boleto, Ambiente configurado como Homologação. Pedido #{$this->pedido->id} - Usuário {$this->pedido->getRelation('usuario')->id}");
                    $this->validacao['header'] = 'Não foi possível prosseguir com a geração do boleto.<br>Se o erro persistir, por favor contate o suporte técnico';
                } else {
                    Log::error('Erro no metodo de pagamento Boleto, Ambiente configurado como Homologação. Verificação nos estatos dos boletos');
                }

                return false;
            }
        } else {
            $this->validacao['status'] = false;
            if ($this->verificarBoletos == false) {
                Log::error("Erro no metodo de pagamento Boleto, Ambiente não configurado. Pedido #{$this->pedido->id} - Usuário {$this->pedido->getRelation('usuario')->id}");
                $this->validacao['header'] = 'Não foi possível prosseguir com a geração do boleto.<br>Se o erro persistir, por favor contate o suporte técnico';
            } else {
                Log::error('Erro no metodo de pagamento Boleto, Ambiente não configurado. Verificação nos estatos dos boletos');
            }

            return false;
        }
    }

    public function gerarBoleto()
    {
        if ($this->validacao['status'] == true) {
            $this->validacao = [];
            $options = [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'sandbox' => $this->ambiente, //(true = desenvolvimento e false = producao)
            ];

            //somo o valor do deposito com o valor da tarifa
            $valor = $this->tarifa_boleto + $this->pedido->getRelation('dadosPagamento')->valor;
            $valor = number_format($valor, 2, ',', '.');
            $valor = str_replace('.', '', $valor);
            $valor = str_replace(',', '', $valor);
            $valor = intval($valor);
            $this->valor_reais = substr_replace($valor, '.', -2, 0);
            $item = [
                'name' => $this->pedido->getRelation('itens')->first()->name_item,
                'amount' => 1, // quantidade
                'value' => $valor, // Ex: valor (1000 = R$ 10,00)
            ];

            //tem que mandar em array, é o tipo aceito no Gerencianet
            $items = [
                $item,
            ];

            //receber notificações da alteração do status da transação e permite associar um custom_id
            $metadata = [
                'custom_id' => (string) $this->pedido->id,
                //'notification_url'=>'sua_url_de_notificacao_.com.br'
            ];

            //dados do usuario
            $endereco = EnderecosUsuarios::where('user_id', $this->usuario->id)->first();
            $dadosEndereco = [
                'street' => preg_replace('/[^a-zA-Z]+$/', '', $endereco->logradouro),
                'number' => (string) preg_replace('/( )+$/', '', $endereco->numero),
                'neighborhood' => preg_replace('/[^a-zA-Z]+$/', '', $endereco->bairro),
                'zipcode' => str_replace('-', '', $endereco->cep),
                'city' => preg_replace('/[^a-zA-Z]+$/', '', $endereco->cidade),
                'complement' => preg_replace('/\s+$/', '', $endereco->complemento),
                'state' => preg_replace('/[^a-zA-Z]+$/', '', $endereco->estado),
            ];

            $customer = [
                'name' => trim($this->usuario->name), // nome do cliente
                'cpf' => preg_replace('/[^0-9]/', '', $this->usuario->cpf), // cpf válido do cliente
                'phone_number' => preg_replace('/[^0-9]/', '', $this->usuario->celular), // telefone do cliente
                'address' => $dadosEndereco,
            ];

            $mensagemBoleto = 'Finalidade: Depósito na carteira';
            $mensagemBoleto .= "\nAgência: 0001 Carteira: {$this->usuario->conta} \n";
            $mensagemBoleto .= "Sr. Caixa, não receber após o vencimento.\nTarifa do boleto de ".mascaraMoeda('R$', $this->tarifa_boleto, 2, true);

            $bankingBillet = [
                'expire_at' => Carbon::now()->addDays(intval($this->dias_vencimento))->format('Y-m-d'), // data de vencimento do boleto (formato: YYYY-MM-DD)
                'message' => $mensagemBoleto, // mensagem a ser exibida no boleto
                'customer' => $customer,
            ];

            $payment = [
                'banking_billet' => $bankingBillet, // forma de pagamento (banking_billet = boleto)
            ];

            $body = [
                'items' => $items,
                'metadata' =>$metadata,
                'payment' => $payment,
            ];

            try {
                $api = new Gerencianet($options);
                $pay_charge = $api->oneStep([], $body);
                /*echo '<pre>';
                print_r($pay_charge);
                echo '<pre>';
                dd($pay_charge);*/
                if ($pay_charge['code'] == 200) {
                    Log::info("Boleto GerenciaNet Gerado com sucesso, transação {$pay_charge['data']['charge_id']}. Pedido #{$this->pedido->id} - Usuário {$this->pedido->getRelation('usuario')->id}");
                    $this->validacao['status'] = true;
                    $this->validacao['header'] = 'O boleto gerado com sucesso!';
                    $this->validacao['charge_id'] = $pay_charge['data']['charge_id'];
                    $this->validacao['valor_reais'] = $this->valor_reais;
                    $this->validacao['tarifa_boleto'] = $this->tarifa_boleto;
                    $this->validacao['status_boleto'] = $pay_charge['data']['status'];
                    $this->validacao['data_vencimento'] = Carbon::now()->addDays(intval($this->dias_vencimento));
                    $this->validacao['codigo_barra'] = $pay_charge['data']['barcode'];
                    $this->validacao['link'] = $pay_charge['data']['link'];
                    $this->validacao['pdf'] = $pay_charge['data']['pdf']['charge'];
                } else {
                    Log::error("Erro ao gerar boleto no GerenciaNet. Pedido #{$this->pedido->id} - Usuário {$this->pedido->getRelation('usuario')->id}");
                    $this->validacao['status'] = false;
                    $this->validacao['header'] = 'Não foi possível prosseguir com a geração do boleto.<br>Se o erro persistir, por favor contate o suporte técnico';
                }

                return $this->validacao;
            } catch (GerencianetException $e) {
                Log::error("Erro ao gerar boleto no GerenciaNet. Pedido #{$this->pedido->id} - Usuário {$this->pedido->getRelation('usuario')->id}");
                Log::error('Código do Erro: '.$e->getCode());
                Log::error('Erro: '.$e->error);
                Log::error('Descrição do erro: '.$e->errorDescription);
                $this->validacao['status'] = false;
                $this->validacao['header'] = 'Não foi possível prosseguir com a geração do boleto.<br>Se o erro persistir, por favor contate o suporte técnico';

                return $this->validacao;
            } catch (Exception $e) {
                Log::error("Erro ao gerar boleto no GerenciaNet. Pedido #{$this->pedido->id} - Usuário {$this->pedido->getRelation('usuario')->id}");
                $this->validacao['status'] = false;
                $this->validacao['header'] = 'Não foi possível prosseguir com a geração do boleto.<br>Se o erro persistir, por favor contate o suporte técnico';

                return $this->validacao;
            }
        } else {
            return $this->validacao;
        }
    }

    public function cancelarBoleto($charge_id, $recuperarDadosBoleto = true)
    {
        if ($this->validacao['status'] == true) {
            $this->validacao = [];

            $options = [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'sandbox' => $this->ambiente, //(true = desenvolvimento e false = producao)
            ];

            // $charge_id refere-se ao ID da transação gerada anteriormente
            $params = [
                'id' => $charge_id,
            ];

            try {
                $api = new Gerencianet($options);
                $charge = $api->cancelCharge($params, []);

                /*//marcar como paga determinada transação
                $api = new Gerencianet($options);
                $charge = $api->settleCharge($params, []);*/

                if ($charge['code'] == 200) {
                    Log::info("Boleto cancelado com sucesso, transação {$charge_id}. Pedido #{$this->pedido->id} - Usuário {$this->pedido->getRelation('usuario')->id}");
                    if ($recuperarDadosBoleto) {
                        $this->validacao = $this->pedido->getRelation('dadosPagamento')->dados_boleto;
                    }
                    $this->validacao['status'] = true;
                    $this->validacao['header'] = 'Boleto cancelado com sucesso!';
                    $this->validacao['data_cancelamento'] = Carbon::now()->format('Y-m-d H:i:s');
                    $this->validacao['status_boleto'] = 'canceled';
                } else {
                    Log::error("Erro ao cancelar o boleto no GerenciaNet, transação {$charge_id}. Pedido #{$this->pedido->id} - Usuário {$this->pedido->getRelation('usuario')->id}");
                    $this->validacao['status'] = false;
                    $this->validacao['header'] = 'Não foi possível prosseguir com o cancelamento do boleto.<br>Se o erro persistir, por favor contate o suporte técnico';
                }

                return $this->validacao;
            } catch (GerencianetException $e) {
                Log::error("Erro ao cancelar boleto no GerenciaNet, transação {$charge_id}. Pedido #{$this->pedido->id} - Usuário {$this->pedido->getRelation('usuario')->id}");
                Log::error('Código do Erro: '.$e->getCode());
                Log::error('Erro: '.$e->error);
                Log::error('Descrição do erro: '.$e->errorDescription);
                $this->validacao['status'] = false;
                $this->validacao['header'] = 'Não foi possível prosseguir com o cancelamento do boleto.<br>Se o erro persistir, por favor contate o suporte técnico';

                return $this->validacao;
            } catch (Exception $e) {
                Log::error("Erro ao cancelar o boleto no GerenciaNet, transação {$charge_id}. Pedido #{$this->pedido->id} - Usuário {$this->pedido->getRelation('usuario')->id}");
                $this->validacao['status'] = false;
                $this->validacao['header'] = 'Não foi possível prosseguir com o cancelamento do boleto.<br>Se o erro persistir, por favor contate o suporte técnico';

                return $this->validacao;
            }
        } else {
            return $this->validacao;
        }
    }

    public function verificaBoletos()
    {
        if ($this->validacao['status'] == true) {
            $options = [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'sandbox' => $this->ambiente, //(true = desenvolvimento e false = producao)
            ];

            //seleciono os pedidos que estão aguardando a confirmação do pagamento
            //e o metodo de pagamento é boleto
            $pedidos = Pedidos::with('itens.itens', 'dadosPagamento', 'usuario')
                ->whereHas('dadosPagamento', function ($query) {
                    $query->where('status', 4)->where('metodo_pagamento_id', 1);
                })
                ->where('status', 4)->get();

            foreach ($pedidos as $pedido) {
                $dados_boleto = $pedido->getRelation('dadosPagamento')->dados_boleto;
                $charge_id = $dados_boleto['charge_id'];
                $params = [
                    'id' => $charge_id, // $charge_id refere-se ao ID da transação ("charge_id")
                ];

                try {
                    $api = new Gerencianet($options);
                    $charge = $api->detailCharge($params, []);

                    if ($charge['code'] == 200) {
                        $status_boleto = $charge['data']['status'];
                        if ($status_boleto == 'paid' || $status_boleto == 'settled') {
                            //boleto foi pago, então verifico se o valor pago é igual ou maior que o valor do pedido
                            $valorPago = $charge['data']['paid_value'];
                            $valorPedido = $pedido->getRelation('dadosPagamento')->dados_boleto['valor_reais'];
                            $valorPedido = number_format($valorPedido, 2, ',', '.');
                            $valorPedido = str_replace('.', '', $valorPedido);
                            $valorPedido = str_replace(',', '', $valorPedido);
                            $valorPedido = intval($valorPedido);

                            if ($valorPago >= $valorPedido) {
                                //está tudo certo, então dou baixa no pagamento
                                $historico = $charge['data']['history'];
                                // Conta o tamanho do array data (que armazena o resultado)
                                $i = count($historico);
                                $ultimoStatus = $historico[$i - 1];
                                $data_pagamento = $ultimoStatus['created_at'];
                                //faço o processo de confirmação do pagamento
                                Log::info('##########################################');
                                Log::warning('Confirmação do pagamento do pedido #'.$pedido->id.' por boleto');

                                try {
                                    DB::beginTransaction();
                                    $dados_boleto['status_boleto'] = $status_boleto;
                                    $dados_boleto['header'] = 'Boleto pago';
                                    $dados_boleto['data_pagamento'] = $data_pagamento;
                                    $dados_boleto['valor_pago'] = substr_replace($valorPago, '.', -2, 0);
                                    $pedido->getRelation('dadosPagamento')->documento = 'Confirmação automática de boleto via sistema';
                                    $pedido->getRelation('dadosPagamento')->status = 2;
                                    $pedido->getRelation('dadosPagamento')->data_pagamento = $data_pagamento;
                                    $pedido->getRelation('dadosPagamento')->data_pagamento_efetivo = $data_pagamento;
                                    $pedido->getRelation('dadosPagamento')->responsavel_user_id = 1;
                                    $pedido->getRelation('dadosPagamento')->dados_boleto = $dados_boleto;
                                    $pedido->getRelation('dadosPagamento')->save();

                                    $pedido->status = 2;
                                    $pedido->save();

                                    $pagamento = new Pagamentos($pedido);

                                    $erros = $pagamento->efetivarPagamento();

                                    if ($erros > 0) {
                                        DB::rollback();
                                        Log::info('Houve erros ao processar a confirmação de pagamento de boleto do pedido #'.$pedido->id);
                                        Log::info('');
                                    } else {
                                        DB::commit();
                                        Log::info('Boleto do pedido #'.$pedido->id.' confirmado pagamento com sucesso');
                                        Log::info('');
                                    }
                                } catch (ModelNotFoundException $e) {
                                    DB::rollback();
                                    Log::info('Houve erros ao processar a confirmação de pagamento de boleto do pedido #'.$pedido->id);
                                    Log::info('');
                                }
                            } else {
                                Log::info("Verificar pedido #{$pedido->id}, boleto foi pago com valor menor");
                            }
                        } else {
                            //boleto ainda não está pago então verifico se já passou da data de vencimento
                            $dataVencimento = $pedido->getRelation('dadosPagamento')->data_vencimento;
                            //adiciono 5 dias na data de vencimento, pois é o prazo de compensação
                            $dataVencimento = Carbon::parse($dataVencimento)->addDays(5);
                            $dataAtual = Carbon::now();
                            //$dias = $dataAtual->diffInDays($dataVencimento);

                            if ($dataAtual > $dataVencimento) {
                                //cancelo o pedido e o boleto
                                Log::info('##########################################');
                                Log::warning('Cancelamento do pedido #'.$pedido->id.' por não pagamento do boleto dentro do prazo');

                                try {
                                    DB::beginTransaction();
                                    $pedido->getRelation('dadosPagamento')->documento = 'Cancelamento automatico de boleto via sistema';
                                    $pedido->getRelation('dadosPagamento')->status = 3;
                                    $pedido->getRelation('dadosPagamento')->responsavel_user_id = 1;
                                    $pedido->getRelation('dadosPagamento')->save();

                                    $pedido->status = 3;
                                    $pedido->save();

                                    DB::commit();
                                    Log::info('Pedido #'.$pedido->id.' cancelado com sucesso');

                                    //cancelo o boleto
                                    $this->pedido = $pedido;
                                    $this->cancelarBoleto($charge_id, true);
                                    if ($this->validacao['status'] == true) {
                                        //faço o update das informações nos dados de pagamento
                                        $pedido->getRelation('dadosPagamento')->update(['dados_boleto' => $this->validacao]);
                                    }
                                } catch (ModelNotFoundException $e) {
                                    DB::rollback();
                                    Log::info('Houve erros ao processar o cancelamento do pedido #'.$pedido->id);
                                    Log::info('');
                                }
                            }
                        }
                    } else {
                        Log::error("Erro ao verificar boleto no GerenciaNet, transação {$charge_id}. Pedido #{$pedido->id} - Usuário {$pedido->getRelation('usuario')->id}");
                    }
                } catch (GerencianetException $e) {
                    Log::error("Erro ao verificar boleto no GerenciaNet, transação {$charge_id}. Pedido #{$pedido->id} - Usuário {$pedido->getRelation('usuario')->id}");
                    Log::error('Código do Erro: '.$e->getCode());
                    Log::error('Erro: '.$e->error);
                    Log::error('Descrição do erro: '.$e->errorDescription);
                } catch (Exception $e) {
                    Log::error("Erro ao verificar boleto no GerenciaNet, transação {$charge_id}. Pedido #{$pedido->id} - Usuário {$pedido->getRelation('usuario')->id}");
                }

                //sempre deixo a validacação como true para poder verificar o proximo boleto
                $this->validacao = [];
                $this->validacao['status'] = true;
                $this->pedido = null;
            }
        } else {
            Log::error('Não foi possível verificar os boletos, pois não foi validado dos dados do metodo de pagamento');
        }
    }
}
