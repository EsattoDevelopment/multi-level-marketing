<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Responsaveis;
use Illuminate\Http\Request;
use App\Models\DadosBancarios;
use Illuminate\Support\Facades\DB;
use App\Models\DocumentosRecusados;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Jobs\SendDocumentacaoAprovacaoEmail;

class DocumentosController extends Controller
{
    protected $dadosDocumentosRecusados;
    protected $usuario;
    protected $responsavel;

    public function __construct()
    {
        $this->middleware('role:master|admin|manipulador-documento', [
            'except' => [
                'create',
                'store',
                'show',
                'edit',
                'update',
                'destroy',
            ],
        ]);
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

    public function associadoDocNaoEnviados()
    {
        $usuarios = User::with('dadosBancarios', 'responsavel')
            ->where(function ($query) {
                $query->whereHas('dadosBancarios', function ($query) {
                    $query->where('status_comprovante', null);
                })->orWhereHas('responsavel', function ($query) {
                    $query->where('status', 0)
                            ->where(function ($query) {
                                $query->where('status_selfie', null)
                                   ->orWhere('status_documento', null)
                                   ->orWhere('status_documento_representacao', null);
                            });
                })
                ->orWhere('status_comprovante_endereco', null)
                ->orWhere('status_selfie', null)
                ->orWhere('status_cpf', null);
            })
            ->get(['id', 'name', 'empresa', 'cpf', 'conta', 'status_comprovante_endereco', 'status_selfie', 'status_cpf', 'data_nasc']);

        return $this->view('default.documentos.nao-enviados', [
            'title' => 'Documentos Não Enviados',
            'dados' => $usuarios,
        ]);
    }

    public function associadoDocAguardando()
    {
        $usuarios = User::with('dadosBancarios', 'responsavel')
            ->where(function ($query) {
                $query->whereHas('dadosBancarios', function ($query) {
                    $query->where('status_comprovante', 'em_analise');
                })->orWhereHas('responsavel', function ($query) {
                    $query->where('status', 0)
                        ->where(function ($query) {
                            $query->where('status_selfie', 'em_analise')
                                ->orWhere('status_documento', 'em_analise')
                                ->orWhere('status_documento_representacao', 'em_analise');
                        });
                })
                    ->orWhere('status_comprovante_endereco', 'em_analise')
                    ->orWhere('status_selfie', 'em_analise')
                    ->orWhere('status_cpf', 'em_analise');
            })
            ->get(['id', 'name', 'empresa', 'cpf', 'conta', 'status_comprovante_endereco', 'status_selfie', 'status_cpf', 'data_nasc']);

        return $this->view('default.documentos.aguardando', [
            'title' => 'Documentos Aguardando Aprovação',
            'dados' => $usuarios,
        ]);
    }

    public function associadoDocAprovados()
    {
        $usuarios = User::with('dadosBancarios', 'responsavel')
            ->where(function ($query) {
                $query->whereHas('dadosBancarios', function ($query) {
                    $query->where('status_comprovante', 'validado');
                })->orWhereHas('responsavel', function ($query) {
                    $query->whereIn('status', [0, 1])
                        ->where(function ($query) {
                            $query->where('status_selfie', 'validado')
                                ->orWhere('status_documento', 'validado')
                                ->orWhere('status_documento_representacao', 'validado');
                        });
                })
                    ->orWhere('status_comprovante_endereco', 'validado')
                    ->orWhere('status_selfie', 'validado')
                    ->orWhere('status_cpf', 'validado');
            })
            ->get(['id', 'name', 'empresa', 'cpf', 'conta', 'status_comprovante_endereco', 'status_selfie', 'status_cpf', 'data_nasc']);

        return $this->view('default.documentos.aprovados', [
            'title' => 'Documentos Aprovados',
            'dados' => $usuarios,
        ]);
    }

    public function associadoDocReprovados()
    {
        $usuarios = User::with('dadosBancarios', 'responsavel')
            ->where(function ($query) {
                $query->whereHas('dadosBancarios', function ($query) {
                    $query->where('status_comprovante', 'recusado');
                })->orWhereHas('responsavel', function ($query) {
                    $query->where('status', 0)
                        ->where(function ($query) {
                            $query->where('status_selfie', 'recusado')
                                ->orWhere('status_documento', 'recusado')
                                ->orWhere('status_documento_representacao', 'recusado');
                        });
                })
                    ->orWhere('status_comprovante_endereco', 'recusado')
                    ->orWhere('status_selfie', 'recusado')
                    ->orWhere('status_cpf', 'recusado');
            })
            ->get(['id', 'name', 'empresa', 'cpf', 'conta', 'status_comprovante_endereco', 'status_selfie', 'status_cpf', 'data_nasc']);

        return $this->view('default.documentos.reprovados', [
            'title' => 'Documentos Reprovados',
            'dados' => $usuarios,
        ]);
    }

    public function associadoDocAguardandoVisualizacao(Request $request, $id)
    {
        $request->session()->forget('flash_notification');

        $usuarioAtual = User::where('id', $id)->first();

        $usuarios = User::with('dadosBancarios', 'endereco')
            ->where(function ($query) {
                $query->whereHas('dadosBancarios', function ($query) {
                    $query->where('status_comprovante', 'em_analise');
                })
                ->orWhere('status_comprovante_endereco', 'em_analise')
                ->orWhere('status_selfie', 'em_analise')
                ->orWhere('status_cpf', 'em_analise');
            })
            ->where('id', $id)
            ->first();

        $responsavel = Responsaveis::with('usuario')
        ->where('user_id', $id)
            ->where('status', 0)
            ->where(function ($query) {
                $query->where('status_selfie', 'em_analise')
                    ->orWhere('status_documento', 'em_analise')
                    ->orWhere('status_documento_representacao', 'em_analise');
            })
            ->first();

        if ($usuarios != null || $responsavel != null) {
            return $this->view('default.documentos.aguardando-usuario', [
                'title' => 'Documentos Aguardando Aprovação',
                'usuario' => $usuarios,
                'responsavel' => $responsavel,
                'usuarioatual' => $usuarioAtual,
            ]);
        } else {
            flash()->warning('Não foram encontrados as informações do usuário selecionado.<br>Se persistir o erro contate o suporte técnico');

            return redirect()->back();
        }
    }

    public function associadoDocAprovadosVisualizacao(Request $request, $id)
    {
        $request->session()->forget('flash_notification');

        $usuarioAtual = User::where('id', $id)->first();

        $usuarios = User::with('dadosBancarios', 'endereco')
            ->where(function ($query) {
                $query->whereHas('dadosBancarios', function ($query) {
                    $query->where('status_comprovante', 'validado');
                })
                    ->orWhere('status_comprovante_endereco', 'validado')
                    ->orWhere('status_selfie', 'validado')
                    ->orWhere('status_cpf', 'validado');
            })
            ->where('id', $id)
            ->first();

        $responsavel = Responsaveis::with('usuario')
            ->where('user_id', $id)
            ->whereIn('status', [0, 1])
            ->where(function ($query) {
                $query->where('status_selfie', 'validado')
                    ->orWhere('status_documento', 'validado')
                    ->orWhere('status_documento_representacao', 'validado');
            })
            ->first();

        if ($usuarios != null || $responsavel != null) {
            return $this->view('default.documentos.aprovados-usuario', [
                'title' => 'Documentos Aprovados',
                'usuario' => $usuarios,
                'responsavel' => $responsavel,
                'usuarioatual' => $usuarioAtual,
            ]);
        } else {
            flash()->warning('Não foram encontrados as informações do usuário selecionado.<br>Se persistir o erro contate o suporte técnico');

            return redirect()->back();
        }
    }

    public function associadoDocReprovadosVisualizacao(Request $request, $id)
    {
        $request->session()->forget('flash_notification');

        $usuarioAtual = User::where('id', $id)->first();

        $usuarios = User::with('dadosBancarios', 'endereco')
            ->where(function ($query) {
                $query->whereHas('dadosBancarios', function ($query) {
                    $query->where('status_comprovante', 'recusado');
                })
                    ->orWhere('status_comprovante_endereco', 'recusado')
                    ->orWhere('status_selfie', 'recusado')
                    ->orWhere('status_cpf', 'recusado');
            })
            ->where('id', $id)
            ->first();

        $responsavel = Responsaveis::with('usuario')
            ->where('user_id', $id)
            ->where('status', 0)
            ->where(function ($query) {
                $query->where('status_selfie', 'recusado')
                    ->orWhere('status_documento', 'recusado')
                    ->orWhere('status_documento_representacao', 'recusado');
            })
            ->first();

        if ($usuarios != null || $responsavel != null) {
            return $this->view('default.documentos.reprovados-usuario', [
                'title' => 'Documentos Reprovados',
                'usuario' => $usuarios,
                'responsavel' => $responsavel,
                'usuarioatual' => $usuarioAtual,
            ]);
        } else {
            flash()->warning('Não foram encontrados as informações do usuário selecionado.<br>Se persistir o erro contate o suporte técnico');

            return redirect()->back();
        }
    }

    public function associadoDocAguardandoConfirmacao(Request $request)
    {
        //0 = recusado
        //1 = aprovado
        //2 = em analise

        $dados_update_doc = [];
        $dados_update_resp = [];
        $mensagemEmail = [];
        $mensagemEmailResp = [];
        $this->dadosDocumentosRecusados = [];

        //verifico os dados do usuario
        if ($request->has('documento_aprovacao')) {
            if ($request->documento_aprovacao != 2) {
                $dados_update_doc['status_cpf'] = $this->getStatusDocumentosPendente($request->documento_aprovacao);
            }
        }

        if ($request->has('selfie_aprovacao')) {
            if ($request->selfie_aprovacao != 2) {
                $dados_update_doc['status_selfie'] = $this->getStatusDocumentosPendente($request->selfie_aprovacao);
            }
        }
        if ($request->has('endereco_aprovacao')) {
            if ($request->endereco_aprovacao != 2) {
                $dados_update_doc['status_comprovante_endereco'] = $this->getStatusDocumentosPendente($request->endereco_aprovacao);
            }
        }

        //verifico os dados do responsavel
        if ($request->has('responsavel_documento_aprovacao')) {
            if ($request->responsavel_documento_aprovacao != 2) {
                $dados_update_resp['status_documento'] = $this->getStatusDocumentosPendente($request->responsavel_documento_aprovacao);
            }
        }

        if ($request->has('responsavel_selfie_aprovacao')) {
            if ($request->responsavel_selfie_aprovacao != 2) {
                $dados_update_resp['status_selfie'] = $this->getStatusDocumentosPendente($request->responsavel_selfie_aprovacao);
            }
        }
        if ($request->has('responsavel_documento_legal_aprovacao')) {
            if ($request->responsavel_documento_legal_aprovacao != 2) {
                $dados_update_resp['status_documento_representacao'] = $this->getStatusDocumentosPendente($request->responsavel_documento_legal_aprovacao);
            }
        }

        try {
            DB::beginTransaction();
            $usuario = User::where('id', $request->user_id)->first();
            $this->usuario = $usuario;
            if (count($dados_update_doc) > 0) {
                $usuario->update($dados_update_doc);

                $mensagemEmail = $this->getMsgArray($dados_update_doc, $request, 'user');
            }

            $responsavel = Responsaveis::where('id', $request->responsavel_id)->where('user_id', $request->user_id)->first();
            $this->responsavel = $responsavel;
            if (count($dados_update_resp) > 0) {
                $responsavel->update($dados_update_resp);

                $mensagemEmailResp = $this->getMsgArray($dados_update_resp, $request, 'resp');
            }

            if ($request->has('banco_aprovacao')) {
                foreach ($request->banco_aprovacao as $chave => $value) {
                    $motivo = '';
                    if ($value == 0 || $value == 1) {
                        $conta = DadosBancarios::with('bancoReferencia')->where('id', $chave)->where('user_id', $request->user_id)->first();
                        $msg = '<b>Conta '.$conta->getRelation('bancoReferencia')->nome." (Ag: {$conta->agencia}-{$conta->agencia_digito} Conta: {$conta->conta}-{$conta->conta_digito}):</b> ";
                        $chaveBanco = 'Conta bancária '.$conta->getRelation('bancoReferencia')->nome;
                        if ($value == 0) {
                            $motivo = ' ('.$request->get('motivo_banco_aprovacao_'.$chave).')';
                            $conta->update(['status_comprovante' => 'recusado']);
                            $mensagemEmail[$chaveBanco] = $msg.'recusado'.$motivo;
                            array_push($this->dadosDocumentosRecusados, [
                                'documento' => 'banco',
                                'motivo_recusa' => str_replace(')', '', str_replace('(', '', $motivo)),
                                'path_documento' => $conta->imagem_comprovante,
                                'user_id' => $conta->user_id,
                                'banco_id' => $conta->id,
                            ]);
                        } elseif ($value == 1) {
                            $conta->update(['status_comprovante' => 'validado']);
                            $mensagemEmail[$chaveBanco] = $msg.'validado';
                        }
                    }
                }
            }

            //gravo os dados dos documentos recusados
            if (count($this->dadosDocumentosRecusados) > 0) {
                foreach ($this->dadosDocumentosRecusados as $documento) {
                    $doc = DocumentosRecusados::create($documento);
                    //copio a imagem para a pasta de documentos recusados
                    $path = 'documentos/';
                    if ($doc->responsavel_id != null) {
                        $path .= 'responsavel/';
                    }
                    if (Storage::disk('interno')->exists($path.$doc->path_documento)) {
                        //antes de copiar eu verifico se a imagem não existe na pasta de arquivos recusados
                        if (! Storage::disk('interno')->exists('documentos/recusados/'.$doc->path_documento)) {
                            Storage::disk('interno')->copy($path.$doc->path_documento, 'documentos/recusados/'.$doc->path_documento);
                        }
                    }
                }
            }

            DB::commit();

            //envio o email avisando sobre a documentação do usuario
            foreach ($mensagemEmail as $chave => $value) {
                $headerMsg = 'O documento abaixo foi verificado:';
                $subject = "Verificamos seu documento - {$chave}";

                $this->dispatch(new SendDocumentacaoAprovacaoEmail($usuario, $value, $headerMsg, $subject));
            }

            //envio o email avisando sobre a documentação do responsavel
            foreach ($mensagemEmailResp as $chave => $value) {
                $headerMsg = 'O documento abaixo foi verificado:';
                $subject = "Verificamos o documento do responsável - {$chave}";

                $this->dispatch(new SendDocumentacaoAprovacaoEmail($usuario, $value, $headerMsg, $subject));
            }

            Log::info('Documentos verificados com sucesso. Usuario '.$request->user_id);
            flash()->success('Documentos verificados com sucesso');

            return redirect()->route('documentos.associado.aguardando');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erro ao verificar documentos. Usuario '.$request->user_id.'Erro: '.$e->getMessage());
            flash()->error('Erro ao verificar os documentos.<br>Caso persista entre em contato com o suporte técnico');

            return redirect()->back();
        }
    }

    public function associadoDocAprovadosConfirmacao(Request $request)
    {
        //0 = recusado
        //1 = aprovado
        //2 = em analise

        $dados_update_doc = [];
        $dados_update_resp = [];
        $mensagemEmail = [];
        $mensagemEmailResp = [];
        $this->dadosDocumentosRecusados = [];

        if ($request->has('documento_aprovacao')) {
            if ($request->documento_aprovacao != 1) {
                $dados_update_doc['status_cpf'] = $this->getStatusassociadoDocAprovados($request->documento_aprovacao);
            }
        }

        if ($request->has('selfie_aprovacao')) {
            if ($request->selfie_aprovacao != 1) {
                $dados_update_doc['status_selfie'] = $this->getStatusassociadoDocAprovados($request->selfie_aprovacao);
            }
        }
        if ($request->has('endereco_aprovacao')) {
            if ($request->endereco_aprovacao != 1) {
                $dados_update_doc['status_comprovante_endereco'] = $this->getStatusassociadoDocAprovados($request->endereco_aprovacao);
            }
        }

        //verifico os dados do responsavel
        if ($request->has('responsavel_documento_aprovacao')) {
            if ($request->responsavel_documento_aprovacao != 1) {
                $dados_update_resp['status_documento'] = $this->getStatusassociadoDocAprovados($request->responsavel_documento_aprovacao);
            }
        }
        //dd($request->all());

        if ($request->has('responsavel_selfie_aprovacao')) {
            if ($request->responsavel_selfie_aprovacao != 1) {
                $dados_update_resp['status_selfie'] = $this->getStatusassociadoDocAprovados($request->responsavel_selfie_aprovacao);
            }
        }
        if ($request->has('responsavel_documento_legal_aprovacao')) {
            if ($request->responsavel_documento_legal_aprovacao != 1) {
                $dados_update_resp['status_documento_representacao'] = $this->getStatusassociadoDocAprovados($request->responsavel_documento_legal_aprovacao);
            }
        }

        try {
            DB::beginTransaction();
            $usuario = User::where('id', $request->user_id)->first();
            $this->usuario = $usuario;
            if (count($dados_update_doc) > 0) {
                $usuario->update($dados_update_doc);

                $mensagemEmail = $this->getMsgArray($dados_update_doc, $request, 'user');
            }

            $responsavel = Responsaveis::where('id', $request->responsavel_id)->where('user_id', $request->user_id)->first();
            $this->responsavel = $responsavel;
            if (count($dados_update_resp) > 0) {
                $responsavel->update($dados_update_resp);

                $mensagemEmailResp = $this->getMsgArray($dados_update_resp, $request, 'resp');
            }

            if ($request->has('banco_aprovacao')) {
                foreach ($request->banco_aprovacao as $chave => $value) {
                    $motivo = '';
                    if ($value == 0 || $value == 2) {
                        $conta = DadosBancarios::with('bancoReferencia')->where('id', $chave)->where('user_id', $request->user_id)->first();
                        $msg = '<b>Conta '.$conta->getRelation('bancoReferencia')->nome." (Ag: {$conta->agencia}-{$conta->agencia_digito} Conta: {$conta->conta}-{$conta->conta_digito}):</b> ";
                        $chaveBanco = 'Conta bancária '.$conta->getRelation('bancoReferencia')->nome;
                        if ($value == 0) {
                            $motivo = ' ('.$request->get('motivo_banco_aprovacao_'.$chave).')';
                            $conta->update(['status_comprovante' => 'recusado']);
                            $mensagemEmail[$chaveBanco] = $msg.'recusado'.$motivo;
                            array_push($this->dadosDocumentosRecusados, [
                                'documento' => 'banco',
                                'motivo_recusa' => str_replace(')', '', str_replace('(', '', $motivo)),
                                'path_documento' => $conta->imagem_comprovante,
                                'user_id' => $conta->user_id,
                                'banco_id' => $conta->id,
                            ]);
                        } elseif ($value == 2) {
                            $conta->update(['status_comprovante' => 'em_analise']);
                            $mensagemEmail[$chaveBanco] = $msg.'Voltou para análise';
                        }
                    }
                }
            }

            //gravo os dados dos documentos recusados
            if (count($this->dadosDocumentosRecusados) > 0) {
                foreach ($this->dadosDocumentosRecusados as $documento) {
                    $doc = DocumentosRecusados::create($documento);
                    //copio a imagem para a pasta de documentos recusados
                    $path = 'documentos/';
                    if ($doc->responsavel_id != null) {
                        $path .= 'responsavel/';
                    }
                    if (Storage::disk('interno')->exists($path.$doc->path_documento)) {
                        //antes de copiar eu verifico se a imagem não existe na pasta de arquivos recusados
                        if (! Storage::disk('interno')->exists('documentos/recusados/'.$doc->path_documento)) {
                            Storage::disk('interno')->copy($path.$doc->path_documento, 'documentos/recusados/'.$doc->path_documento);
                        }
                    }
                }
            }

            DB::commit();

            //envio o email avisando sobre a documentação do usuario
            foreach ($mensagemEmail as $chave => $value) {
                $headerMsg = 'O documento abaixo foi verificado novamente:';
                $subject = "Verificamos seu documento - {$chave}";

                $this->dispatch(new SendDocumentacaoAprovacaoEmail($usuario, $value, $headerMsg, $subject));
            }

            //envio o email avisando sobre a documentação do responsavel
            foreach ($mensagemEmailResp as $chave => $value) {
                $headerMsg = 'O documento abaixo foi verificado novamente:';
                $subject = "Verificamos o documento do responsável - {$chave}";

                $this->dispatch(new SendDocumentacaoAprovacaoEmail($usuario, $value, $headerMsg, $subject));
            }

            Log::info('Documentos verificados com sucesso. Usuario '.$request->user_id);
            flash()->success('Documentos verificados com sucesso');

            return redirect()->route('documentos.associado.aprovados');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erro ao verificar documentos. Usuario '.$request->user_id.'Erro: '.$e->getMessage());
            flash()->error('Erro ao verificar os documentos.<br>Caso persista entre em contato com o suporte técnico');

            return redirect()->back();
        }
    }

    public function associadoDocReprovadosConfirmacao(Request $request)
    {
        //0 = recusado
        //1 = aprovado
        //2 = em analise

        $dados_update_doc = [];
        $dados_update_resp = [];
        $mensagemEmail = [];
        $mensagemEmailResp = [];

        if ($request->has('documento_aprovacao')) {
            if ($request->documento_aprovacao != 0) {
                $dados_update_doc['status_cpf'] = $this->getStatusDocumentosReprovados($request->documento_aprovacao);
            }
        }

        if ($request->has('selfie_aprovacao')) {
            if ($request->selfie_aprovacao != 0) {
                $dados_update_doc['status_selfie'] = $this->getStatusDocumentosReprovados($request->selfie_aprovacao);
            }
        }
        if ($request->has('endereco_aprovacao')) {
            if ($request->endereco_aprovacao != 0) {
                $dados_update_doc['status_comprovante_endereco'] = $this->getStatusDocumentosReprovados($request->endereco_aprovacao);
            }
        }

        //verifico os dados do responsavel
        if ($request->has('responsavel_documento_aprovacao')) {
            if ($request->responsavel_documento_aprovacao != 0) {
                $dados_update_resp['status_documento'] = $this->getStatusDocumentosReprovados($request->responsavel_documento_aprovacao);
            }
        }

        if ($request->has('responsavel_selfie_aprovacao')) {
            if ($request->responsavel_selfie_aprovacao != 0) {
                $dados_update_resp['status_selfie'] = $this->getStatusDocumentosReprovados($request->responsavel_selfie_aprovacao);
            }
        }
        if ($request->has('responsavel_documento_legal_aprovacao')) {
            if ($request->responsavel_documento_legal_aprovacao != 0) {
                $dados_update_resp['status_documento_representacao'] = $this->getStatusDocumentosReprovados($request->responsavel_documento_legal_aprovacao);
            }
        }

        try {
            DB::beginTransaction();
            $usuario = User::where('id', $request->user_id)->first();
            if (count($dados_update_doc) > 0) {
                $usuario->update($dados_update_doc);

                $mensagemEmail = $this->getMsgArray($dados_update_doc, $request, 'user');
            }

            $responsavel = Responsaveis::where('id', $request->responsavel_id)->where('user_id', $request->user_id)->first();
            if (count($dados_update_resp) > 0) {
                $responsavel->update($dados_update_resp);

                $mensagemEmailResp = $this->getMsgArray($dados_update_resp, $request, 'resp');
            }

            if ($request->has('banco_aprovacao')) {
                foreach ($request->banco_aprovacao as $chave => $value) {
                    if ($value == 1 || $value == 2) {
                        $conta = DadosBancarios::with('bancoReferencia')->where('id', $chave)->where('user_id', $request->user_id)->first();
                        $msg = '<b>Conta '.$conta->getRelation('bancoReferencia')->nome." (Ag: {$conta->agencia}-{$conta->agencia_digito} Conta: {$conta->conta}-{$conta->conta_digito}):</b> ";
                        $chaveBanco = 'Conta bancária '.$conta->getRelation('bancoReferencia')->nome;
                        if ($value == 1) {
                            $conta->update(['status_comprovante' => 'validado']);
                            $mensagemEmail[$chaveBanco] = $msg.'validado';
                        } elseif ($value == 2) {
                            $conta->update(['status_comprovante' => 'em_analise']);
                            $mensagemEmail[$chaveBanco] = $msg.'Voltou para análise';
                        }
                    }
                }
            }
            DB::commit();

            //envio o email avisando sobre a documentação do usuario
            foreach ($mensagemEmail as $chave => $value) {
                $headerMsg = 'O documento abaixo foi verificado novamente:';
                $subject = "Verificamos seu documento - {$chave}";

                $this->dispatch(new SendDocumentacaoAprovacaoEmail($usuario, $value, $headerMsg, $subject));
            }

            //envio o email avisando sobre a documentação do responsavel
            foreach ($mensagemEmailResp as $chave => $value) {
                $headerMsg = 'O documento abaixo foi verificado novamente:';
                $subject = "Verificamos o documento do responsável - {$chave}";

                $this->dispatch(new SendDocumentacaoAprovacaoEmail($usuario, $value, $headerMsg, $subject));
            }

            Log::info('Documentos verificados com sucesso. Usuario '.$request->user_id);
            flash()->success('Documentos verificados com sucesso');

            return redirect()->route('documentos.associado.reprovados');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erro ao verificar documentos. Usuario '.$request->user_id.'Erro: '.$e->getMessage());
            flash()->error('Erro ao verificar os documentos.<br>Caso persista entre em contato com o suporte técnico');

            return redirect()->back();
        }
    }

    private function getStatusDocumentosPendente($status)
    {
        if ($status == 0) {
            return 'recusado';
        } elseif ($status == 1) {
            return 'validado';
        }
    }

    private function getStatusassociadoDocAprovados($status)
    {
        if ($status == 0) {
            return 'recusado';
        } elseif ($status == 2) {
            return 'em_analise';
        }
    }

    private function getStatusDocumentosReprovados($status)
    {
        if ($status == 1) {
            return 'validado';
        } elseif ($status == 2) {
            return 'em_analise';
        }
    }

    private function getMsgArray($arrayInfo, Request $request, $tipo)
    {
        $arrayFinal = [];

        foreach ($arrayInfo as $chave => $value) {
            $motivo = '';
            if ($chave == 'status_cpf') {
                if ($value == 'recusado') {
                    if ($tipo == 'user') {
                        $motivo = " ({$request->motivo_documento_aprovacao})";
                    }

                    array_push($this->dadosDocumentosRecusados, [
                        'documento' => 'documento usuário',
                        'motivo_recusa' => str_replace(')', '', str_replace('(', '', $motivo)),
                        'path_documento' => $this->usuario->image_cpf,
                        'user_id' => $this->usuario->id,
                    ]);
                }
                $arrayFinal['Documento Pessoal'] = '<b>Documento pessoal:</b> '.str_replace('em_analise', 'Voltou para análise', $value.$motivo);
            }
            if ($chave == 'status_selfie') {
                if ($value == 'recusado') {
                    if ($tipo == 'resp') {
                        $motivo = " ({$request->motivo_responsavel_selfie_aprovacao})";
                        array_push($this->dadosDocumentosRecusados, [
                            'documento' => 'selfie responsável',
                            'motivo_recusa' => str_replace(')', '', str_replace('(', '', $motivo)),
                            'path_documento' => $this->responsavel->selfie,
                            'user_id' => $this->usuario->id,
                            'responsavel_id' => $this->responsavel->id,
                        ]);
                    } else {
                        $motivo = " ({$request->motivo_selfie_aprovacao})";
                        array_push($this->dadosDocumentosRecusados, [
                            'documento' => 'selfie usuário',
                            'motivo_recusa' => str_replace(')', '', str_replace('(', '', $motivo)),
                            'path_documento' => $this->usuario->image_selfie,
                            'user_id' => $this->usuario->id,
                        ]);
                    }
                }
                if ($tipo == 'resp') {
                    $arrayFinal['Selfie responsável'] = '<b>Selfie responsável:</b> '.str_replace('em_analise', 'Voltou para análise', $value.$motivo);
                } else {
                    $arrayFinal['Selfie'] = '<b>Selfie:</b> '.str_replace('em_analise', 'Voltou para análise', $value.$motivo);
                }
            }
            if ($chave == 'status_comprovante_endereco') {
                if ($value == 'recusado') {
                    if ($tipo == 'user') {
                        $motivo = " ({$request->motivo_endereco_aprovacao})";
                        array_push($this->dadosDocumentosRecusados, [
                            'documento' => 'endereço usuário',
                            'motivo_recusa' => str_replace(')', '', str_replace('(', '', $motivo)),
                            'path_documento' => $this->usuario->image_comprovante_endereco,
                            'user_id' => $this->usuario->id,
                        ]);
                    }
                }
                $arrayFinal['Comprovante de endereço'] = '<b>Comprovante de endereço:</b> '.str_replace('em_analise', 'Voltou para análise', $value.$motivo);
            }
            if ($chave == 'status_documento') {
                if ($value == 'recusado') {
                    if ($tipo == 'resp') {
                        $motivo = " ({$request->motivo_responsavel_documento_aprovacao})";
                        array_push($this->dadosDocumentosRecusados, [
                            'documento' => 'documento responsável',
                            'motivo_recusa' => str_replace(')', '', str_replace('(', '', $motivo)),
                            'path_documento' => $this->responsavel->documento,
                            'user_id' => $this->usuario->id,
                            'responsavel_id' => $this->responsavel->id,
                        ]);
                    }
                }
                $arrayFinal['Documento do responsável'] = '<b>Documento responsável:</b> '.str_replace('em_analise', 'Voltou para análise', $value.$motivo);
            }
            if ($chave == 'status_documento_representacao') {
                if ($value == 'recusado') {
                    if ($tipo == 'resp') {
                        $motivo = " ({$request->motivo_responsavel_documento_legal_aprovacao})";
                        array_push($this->dadosDocumentosRecusados, [
                            'documento' => 'documento responsável representação legal',
                            'motivo_recusa' => str_replace(')', '', str_replace('(', '', $motivo)),
                            'path_documento' => $this->responsavel->documento_representacao,
                            'user_id' => $this->usuario->id,
                            'responsavel_id' => $this->responsavel->id,
                        ]);
                    }
                }
                $arrayFinal['Documento de representação legal'] = '<b>Documento representação legal:</b> '.str_replace('em_analise', 'Voltou para análise', $value.$motivo);
            }
        }

        return $arrayFinal;
    }

    public function verComprovante($id, $tipo)
    {
        $dados = null;
        $filename = '';
        $path = '';

        if ($tipo == 1) {
            //usuario
            $dados = User::where('id', $id)->first();
            $filename = $dados->image_comprovante_endereco;
            $path = storage_path('app/documentos/'.$filename);
        } elseif ($tipo == 2) {
            //comprovante de resposavel legal sobre o menor
            $dados = Responsaveis::where('id', $id)->first();
            $filename = $dados->documento_representacao;
            $path = storage_path('app/documentos/responsavel/'.$filename);
        }

        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ]);
    }
}
