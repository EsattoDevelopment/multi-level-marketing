<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Log;
use App\Models\User;
use App\Http\Requests;
use App\Models\Bancos;
use App\Models\Sistema;
use http\Client\Response;
use App\Events\AcoesSistema;
use App\Models\Responsaveis;
use Illuminate\Http\Request;
use App\Models\DadosBancarios;
use App\Models\EnderecosUsuarios;
use App\Models\DadosBancariosEdit;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendDocumentacaoEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\EnderecosUsuariosEdit;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Notifications\CadastroInicialFinalizado;
use Illuminate\Auth\Access\UnauthorizedException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
     * Class DadosUsuariosController.
     */
    class DadosUsuariosController extends Controller
    {
        /**
         * DadosUsuariosController constructor.
         */
        public function __construct()
        {
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param DadosUsuarioRequest|Request $request
         *
         * @return \Illuminate\Http\Response
         */
        public function store(Requests\DadosUsuarioRequest $request)
        {
            $flashAfter = '';
            try {
                DB::beginTransaction();
                $enderecos = $request->get('endereco');
                $enderecos['user_id_editor'] = Auth::user()->id;

                $dBancarios = $request->get('d_bancarios');
                $dBancarios['user_id_editor'] = Auth::user()->id;

                EnderecosUsuarios::create($enderecos);
                Log::info('Endereço Cadastrado', $enderecos);

                DadosBancarios::create($dBancarios);
                Log::info('Dados bancarios Cadastrado', $dBancarios);

                $user = User::FindOrFail($request->get('user_id'));

                $user->update($request->except(['pessoal.cpf'])['pessoal']);

                if (strlen($request->get('password')) > 0) {
                    $user->password = $request->get('password');
                    $user->save();
                    Log::info('Senha de '.$user->name.' - '.$user->id.' mudada por id:'.Auth::user()->id);
                }

                if ($request->hasFile('imagem')) {
                    $nameImage = str_slug(uniqid(), '_').'.'.strtolower($request->file('imagem')->getClientOriginalExtension());

                    Storage::put('/images/user/'.$nameImage, file_get_contents($request->file('imagem')->getRealPath()));

                    $user->image = $nameImage;
                }

                // Imagem de validaçao do CPF
                if ($request->hasFile('image_cpf')) {
                    if ($user->image_cpf) {
                        if (Storage::disk('interno')->exists('documentos/'.$user->image_cpf)) {
                            Storage::disk('interno')->delete('documentos/'.$user->image_cpf);
                        }
                    }
                    $cpfImage = str_slug(uniqid(), '_').'.'.strtolower($request->file('image_cpf')->getClientOriginalExtension());
                    $request->file('image_cpf')->move(Storage::disk('interno')->getDriver()->getAdapter()->getPathPrefix().'/documentos', $cpfImage);
                    $user->image_cpf = $cpfImage;
                    $user->status_cpf = 'em_analise';
                    $this->dispatch(new SendDocumentacaoEmail($user));
                    $flashAfter = '<br>Sua documentação foi enviada para análise.';
                }

                // Imagem de validaçao do Comprovante de endereco
                if ($request->hasFile('image_comprovante_endereco')) {
                    if ($user->image_comprovante_endereco) {
                        if (Storage::disk('interno')->exists('documentos/'.$user->image_comprovante_endereco)) {
                            Storage::disk('interno')->delete('documentos/'.$user->image_comprovante_endereco);
                        }
                    }
                    $comprovanteEnderecoImage = str_slug(uniqid(), '_').'.'.strtolower($request->file('image_comprovante_endereco')->getClientOriginalExtension());
                    $request->file('image_comprovante_endereco')->move(Storage::disk('interno')->getDriver()->getAdapter()->getPathPrefix().'/documentos', $comprovanteEnderecoImage);
                    $user->image_comprovante_endereco = $comprovanteEnderecoImage;
                    $user->status_comprovante_endereco = 'em_analise';
//                $this->dispatch(new SendDocumentacaoEmail($user));
                    $flashAfter = '<br>O seu comprovante de endereço foi enviado para análise.';
                }

                $user->equipe_preferencial = $request->get('equipe_preferencial');
                $user->save();

                //Pagamento de bonus no cadastro
                $retornoEvento = \Event::fire(new AcoesSistema($user));

                $countErros = 0;

                foreach ($retornoEvento as $key => $respostas) {
                    if (! $respostas) {
                        Log::info('Erro no evento #'.$key);
                        $countErros++;
                    }
                }

                if ($countErros == 0) {
                    DB::commit();

                    if ($user->status_cpf == null) {
                        flash()->error('Dados Atualizados com sucesso, envio de documentos pendente!');
                    } elseif ($user->status_cpf == 'em_analise') {
                        flash()->warning('Dados Atualizados com sucesso, documentos em analise!');
                    } else {
                        flash()->success('Dados Atualizados com sucesso!');
                    }
                } else {
                    DB::rollback();
                    flash()->error('Desculpe, erro ao salvar o Dados do usuário');
                }

                return redirect()->route('pedido.create');
            } catch (ModelNotFoundException $e) {
                DB::rollback();
                flash()->error('Desculpe, erro ao salvar o Dados do usuário');

                Log::info('Erro ao cadastrar dados do usuario: '.$request->get('user_id'), ['user' => Auth::user()->id]);

                return redirect()->route('dados-usuario.show');
            }
        }

        /**
         * Display the specified resource.
         *
         *
         * @return \Illuminate\Http\Response
         */
        public function show()
        {
            return redirect()->route('dados-usuario.pessoais');

            $sistema = Sistema::findOrFail(1);

            $id = Auth::user()->id;
            if ($sistema->endereco == 1) {
                $endereco = EnderecosUsuarios::whereUserId($id)->first();
            } else {
                $endereco = EnderecosUsuarios::create([
                    'user_id' => $id,
                    'cep' => '',
                    'logradouro' => '',
                    'numero' => '',
                    'bairro' => '',
                    'cidade' => '',
                    'estado' => '',
                    'telefone1' => '',
                    'telefone2' => '',
                    'celular' => '',
                    'user_id_editor' => 1,
                ]);
            }

            if ($sistema->dados_bancarios == 1) {
                $bancarios = DadosBancarios::whereUserId($id)->first();
            } else {
                $bancarios = DadosBancarios::create([
                    'banco' => 0,
                    'agencia' => 0,
                    'agencia_digito' => 0,
                    'conta' => 0,
                    'conta_digito' => 0,
                    'user_id' => $id,
                    'user_id_editor' => $id,
                    'tipo_conta' => 0,
                    'receber_bonus' => 0,
                    'banco_id' => 1,
                ]);
            }

            return view('default.dados-usuario.show', [
                'usuario'        => User::with('titulo')->findOrFail($id),
                'title'          => 'Dados do usuário',
                'endereco'       => $endereco,
                'dadosBancarios' => $bancarios,
                'bancos'         => Bancos::all(),
                'hasPedido'      => (Auth::user()->pedidos->count() > 0 ? true : false),
            ]);
        }

        /**
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
         */
        public function endereco()
        {
            $sistema = Sistema::findOrFail(1);

            $id = Auth::user()->id;
            if ($sistema->endereco == 1) {
                $endereco = EnderecosUsuarios::whereUserId($id)->first();
            } else {
                $endereco = EnderecosUsuarios::create([
                    'user_id' => $id,
                    'cep' => '',
                    'logradouro' => '',
                    'numero' => '',
                    'bairro' => '',
                    'cidade' => '',
                    'estado' => '',
                    'telefone1' => '',
                    'telefone2' => '',
                    'celular' => '',
                    'user_id_editor' => 1,
                ]);
            }

            return view('default.dados-usuario.endereco', [
                'usuario'        => User::with('titulo')->findOrFail($id),
                'title'          => 'Endereço - Dados do usuário',
                'endereco'       => $endereco,
                'hasPedido'      => (Auth::user()->pedidos->count() > 0 ? true : false),
            ]);
        }

        /**
         * Update the specified resource in storage.
         *
         * @param Requests\DadosUsuarioRequest|Request $request
         * @param  int                                 $id
         *
         * @return \Illuminate\Http\Response
         */
        public function update(Requests\DadosUsuarioRequest $request)
        {
            return redirect()->route('dados-usuario.pessoais');

            $hasPedido = Auth::user()->pedidos->count() > 0 ? true : false;
            $id = Auth::user()->id;
            $flashAfter = '';

            try {
                DB::beginTransaction();

                if (! $hasPedido) {
                    // carrega endereço anterior
                    $endereco = EnderecosUsuarios::whereUserId($id)->first();
                    $enderecoOld = $endereco->toArray();

                    // carrega dados bancarios anterior
                    $bancarios = DadosBancarios::whereUserId($id)->first();
                    if ($bancarios != null) {
                        $bancariosOld = $bancarios->toArray();
                        // separa variaveis bancarias
                        $dBancariosNew = $request->get('d_bancarios');
                        $bancariosOld['user_id_editor'] = Auth::user()->id;
                        $bancariosOld['dados_bancarios_id'] = $bancariosOld['id'];
                        // salva novos dados bancarios
                        $bancarios->update($dBancariosNew);
                        DadosBancariosEdit::create($bancariosOld);
                        Log::info('Dados bancarios alterado', $dBancariosNew);
                    } else {
                        $dBancarios = $request->get('d_bancarios');
                        $dBancarios['user_id_editor'] = Auth::user()->id;

                        DadosBancarios::create($dBancarios);
                        Log::info('Dados bancarios Cadastrado', $dBancarios);
                    }

                    // separa variaveis de endereço
                    $enderecosNew = $request->get('endereco');
                    $enderecoOld['user_id_editor'] = Auth::user()->id;
                    $enderecoOld['enderecos_usuario_id'] = $enderecoOld['id'];

                    // salva novo endereco
                    $endereco->update($enderecosNew);
                    EnderecosUsuariosEdit::create($enderecoOld);
                    Log::info('Endereço alterado', $enderecosNew);
                }

                $user = User::FindOrFail($request->get('user_id'));

                if (! $hasPedido) {
                    $user->update($request->except(['pessoal.cpf'])['pessoal']);
                }

                // salva alteração de senha
                if (strlen($request->get('password')) > 0) {
                    $user->password = $request->get('password');
                    $user->save();
                    Log::info('Senha de '.$user->name.' - '.$user->id.' mudada por id:'.Auth::user()->id);
                }

                // Imagem de validaçao do CPF
                if ($request->hasFile('image_cpf')) {
                    if ($user->image_cpf) {
                        if (Storage::disk('interno')->exists('documentos/'.$user->image_cpf)) {
                            Storage::disk('interno')->delete('documentos/'.$user->image_cpf);
                        }
                    }
                    $cpfImage = str_slug(uniqid(), '_').'.'.strtolower($request->file('image_cpf')->getClientOriginalExtension());
                    $request->file('image_cpf')->move(Storage::disk('interno')->getDriver()->getAdapter()->getPathPrefix().'/documentos', $cpfImage);
                    $user->image_cpf = $cpfImage;
                    $user->status_cpf = 'em_analise';
                    $this->dispatch(new SendDocumentacaoEmail($user));
                    $flashAfter = '<br>Sua documentação foi enviada para análise.';
                }

                // Imagem de validaçao do Comprovante de endereco
                if ($request->hasFile('image_comprovante_endereco')) {
                    if ($user->image_comprovante_endereco) {
                        if (Storage::disk('interno')->exists('documentos/'.$user->image_comprovante_endereco)) {
                            Storage::disk('interno')->delete('documentos/'.$user->image_comprovante_endereco);
                        }
                    }
                    $comprovanteEnderecoImage = str_slug(uniqid(), '_').'.'.strtolower($request->file('image_comprovante_endereco')->getClientOriginalExtension());
                    $request->file('image_comprovante_endereco')->move(Storage::disk('interno')->getDriver()->getAdapter()->getPathPrefix().'/documentos', $comprovanteEnderecoImage);
                    $user->image_comprovante_endereco = $comprovanteEnderecoImage;
                    $user->status_comprovante_endereco = 'em_analise';
//                $this->dispatch(new SendDocumentacaoEmail($user));
                    $flashAfter = '<br>O seu comprovante de endereço foi enviado para análise.';
                }

                $user->equipe_preferencial = $request->get('equipe_preferencial');
                $user->avisa_recebimento_rentabilidade = ! $request->has('pessoal.avisa_recebimento_rentabilidade') ? 0 : 1;

                $user->save();
                DB::commit();

                flash()->success('Dados atualizados com sucesso!'.$flashAfter);

                return redirect()->route('dados-usuario.show');
            } catch (ModelNotFoundException $e) {
                DB::rollback();
                flash()->error('Desculpe, erro ao atualizar o Dados do usuário');

                Log::info('Erro ao atualizar dados do usuario: '.$request->get('user_id'), ['user' => Auth::user()->id]);

                return redirect()->route('dados-usuario.show');
            }
        }

        /**
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
         */
        public function pessoais()
        {
            $id = Auth::user()->id;

            return view('default.dados-usuario.pessoais', [
                'usuario'        => User::with('titulo', 'responsavel')->findOrFail($id),
                'responsavel' => Responsaveis::where('status', '<>', 3)->where('user_id', $id)->first(),
                'title'          => 'Dados do usuário',
            ]);
        }

        /**
         * @param Requests\DadosUsuarioPessoaisRequest $request
         * @return \Illuminate\Http\RedirectResponse
         */
        public function updatePessoais(Requests\DadosUsuarioPessoaisRequest $request)
        {
            try {
                DB::beginTransaction();

                $user = User::FindOrFail($request->get('user_id'));

                $dados = $request->except(['pessoal.cpf'])['pessoal'];

                if ($user->validado) {
                    $dados = $request->only(['pessoal.telefone', 'pessoal.celular', 'pessoal.avisa_recebimento_rentabilidade'])['pessoal'];
                }

                $user->update($dados);
                $user->equipe_preferencial = $request->get('equipe_preferencial');
                $user->avisa_recebimento_rentabilidade = ! $request->has('pessoal.avisa_recebimento_rentabilidade') ? 0 : 1;

                $user->save();

                if ($user->idade < 18) {
                    $responsavel = $user->responsavel->where('status', 0)->first();

                    if (! $responsavel && ! $user->validado):
                        Responsaveis::create($request->get('responsavel')); else:
                        $responsavel->update($request->get('responsavel'));
                    endif;
                }

                DB::commit();

                //verificar se tem endereço
                $endereco = EnderecosUsuarios::select('id')->whereUserId($request->user()->id)->first();
                if (! $endereco) {
                    flash()->success('Dados atualizados com sucesso, por favor preencha seu endereço!');

                    return redirect()->route('dados-usuario.endereco');
                }

                flash()->success('Dados atualizados com sucesso!');

                return redirect()->route('dados-usuario.pessoais');
            } catch (ModelNotFoundException $e) {
                DB::rollback();
                flash()->error('Desculpe, erro ao atualizar o Dados do usuário');

                Log::info('Erro ao atualizar dados do usuario: '.$request->get('user_id'), ['user' => Auth::user()->id]);

                return redirect()->route('dados-usuario.pessoais');
            }
        }

        /**
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
         */
        public function seguranca()
        {
            $id = Auth::user()->id;

            return view('default.dados-usuario.seguranca', [
                'usuario'        => User::with('titulo')->findOrFail($id),
                'title'          => 'Segurança - Dados do usuário',
            ]);
        }

        /**
         * @param Requests\DadosUsuarioSegurancaRequest $request
         * @return \Illuminate\Http\RedirectResponse
         */
        public function updateSeguranca(Requests\DadosUsuarioSegurancaRequest $request)
        {
            $id = Auth::user()->id;

            try {
                DB::beginTransaction();

                $user = User::FindOrFail($id);

                //Crypt::decrypt

                // salva alteração de senha
                if (strlen($request->get('password')) > 0) {
                    //verifico a senha atual
                    if (Hash::check($request->get('passwordatual'), $user->password)) {
                        $user->password = $request->get('password');
                        $user->save();
                        Log::info('Senha de '.$user->name.' - '.$user->id.' mudada por id:'.Auth::user()->id);
                    } else {
                        Log::info('Senha atual digitada não confere com a senha atual cadastrada');
                        flash()->error('A senha atual digitada não confere com sua senha atual cadastrada.<br>Por favor, verifique!');

                        return redirect()->back();
                    }
                }

                if ($user->google2fa_secret) {
                    $user->google2fa_login = ! $request->has('google2fa_login') ? 0 : 1;
                }

                $user->save();

                DB::commit();

                flash()->success('Dados de segurança atualizado com sucesso!');

                return redirect()->route('dados-usuario.seguranca');
            } catch (ModelNotFoundException $e) {
                DB::rollback();
                flash()->error('Desculpe, erro ao atualizar seus dados.');

                Log::info('Erro ao atualizar dados de segurança do usuario: '.$request->get('user_id'), ['user' => Auth::user()->id]);

                return redirect()->route('dados-usuario.seguranca');
            }
        }

        /**
         * @param Requests\DadosUsuarioEnderecoRequest $request
         * @return \Illuminate\Http\RedirectResponse
         */
        public function updateEndereco(Requests\DadosUsuarioEnderecoRequest $request)
        {
            $id = Auth::user()->id;

            try {
                DB::beginTransaction();

                //criar endereço se não tiver
                if (! Auth::user()->endereco) {
                    $cria_endereco = EnderecosUsuarios::create([
                        'user_id' => $id,
                        'cep' => '',
                        'logradouro' => '',
                        'numero' => '',
                        'bairro' => '',
                        'cidade' => '',
                        'estado' => '',
                        'telefone1' => '',
                        'telefone2' => '',
                        'celular' => '',
                        'user_id_editor' => 1,
                    ]);
                }

                // carrega endereço anterior
                $endereco = EnderecosUsuarios::whereUserId($id)->first();
                $enderecoOld = $endereco->toArray();

                // separa variaveis de endereço
                $enderecosNew = $request->get('endereco');
                $enderecoOld['user_id_editor'] = Auth::user()->id;
                $enderecoOld['enderecos_usuario_id'] = $enderecoOld['id'];

                // salva novo endereco
                $endereco->update($enderecosNew);
                EnderecosUsuariosEdit::create($enderecoOld);
                Log::info('Endereço alterado', $enderecosNew);

                DB::commit();

                // após criar cadastrar todos os dados enviar para novo depósito
                if (isset($cria_endereco)) {
                    flash()->success('Parabéns, você completou seu cadastro, você já pode adquirir uma licença!');

                    Auth::user()->notify(new CadastroInicialFinalizado());

                    return redirect()->route('portfolio.lista');
                }

                flash()->success('Endereço atualizado com sucesso!');

                return redirect()->route('dados-usuario.endereco');
            } catch (ModelNotFoundException $e) {
                DB::rollback();
                flash()->error('Desculpe, erro ao atualizar seu endereço.');

                Log::info('Erro ao atualizar dados do endereço do usuario: '.$request->get('user_id'), ['user' => Auth::user()->id]);

                return redirect()->route('dados-usuario.endereco');
            }
        }

        /**
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
         */
        public function identificacao()
        {
            $id = Auth::user()->id;

            return view('default.dados-usuario.identificacao', [
                'usuario'        => User::with('titulo')->findOrFail($id),
                'responsavel' => Responsaveis::whereIn('status', [0, 1])->where('user_id', $id)->first(),
                'title'          => 'Enviar Documentos - Dados do usuário',
            ]);
        }

        /**
         * @param Request $request
         * @return \Illuminate\Http\JsonResponse
         */
        public function storeImagem(Request $request)
        {
            try {
                $user = Auth::user();

                if ($request->hasFile('imagem')) {
                    if ($user->image) {
                        if (Storage::exists('/images/user/'.$user->image)) {
                            Storage::delete('/images/user/'.$user->image);
                        }
                    }

                    $nameImage = str_slug(uniqid(), '_').'.'.strtolower($request->file('imagem')->getClientOriginalExtension());
                    $request->file('imagem')->move(Storage::getDriver()->getAdapter()->getPathPrefix().'/images/user/', $nameImage);
                    $user->image = $nameImage;

                    $user->save();

                    return response()->json(['imagem' => route('imagecache', ['user', 'user/'.$user->image])]);
                }
            } catch (UnauthorizedException $e) {
                return response()->json([]);
            }
        }

        /**
         * @param Request $request
         * @return \Illuminate\Http\JsonResponse
         */
        public function storeImagemDocumentos(Request $request)
        {
            if ($request->has('action')) {
                //CPF frente
                if ($request->get('action') === 'identidade') {
                    return $this->storeDocCpf($request);
                }

                //CPF frente
                if ($request->get('action') === 'identidade_verso') {
                    return $this->storeDocCpfVerso($request);
                }

                //DOCUMENTO
                if ($request->get('action') === 'endereco') {
                    return $this->storeDocEndereco($request);
                }

                //SELFIE
                if ($request->get('action') === 'selfie') {
                    return $this->storeDocSelfie($request);
                }

                //SELFIE RESPONSAVEL
                if ($request->get('action') === 'selfie_responsavel') {
                    return $this->storeDoc($request, 'selfie', 'status_selfie');
                }

                //DOCUMENTO RESPONSAVEL FRENTE
                if ($request->get('action') === 'identidade_responsavel') {
                    return $this->storeDoc($request, 'documento', 'status_documento');
                }

                //DOCUMENTO RESPONSAVEL VERSO
                if ($request->get('action') === 'identidade_responsavel_verso') {
                    return $this->storeDocVerso($request, 'documento_verso', 'status_documento');
                }

                //REPRESENTACAO RESPONSAVEL
                if ($request->get('action') === 'representacao_responsavel') {
                    return $this->storeDoc($request, 'documento_representacao', 'status_documento_representacao');
                }
            }

            return response()->json([

            ], 500);
        }

        /**
         * @param Request $request
         * @return \Illuminate\Http\JsonResponse
         */
        private function storeDocCpf(Request $request)
        {
            if ($request->hasFile('imagem') && Auth::user()->status_cpf !== 'validado') {
                if (Auth::user()->image_cpf) {
                    if (Storage::disk('interno')->exists('documentos/'.Auth::user()->image_cpf)) {
                        Storage::disk('interno')->delete('documentos/'.Auth::user()->image_cpf);
                    }
                }

                $cpfImage = str_slug(uniqid(), '_').'.'.strtolower($request->file('imagem')->getClientOriginalExtension());
                $request->file('imagem')->move(Storage::disk('interno')->getDriver()->getAdapter()->getPathPrefix().'/documentos', $cpfImage);

                $user = Auth::user();
                $user->image_cpf = $cpfImage;
                $user->status_cpf = 'em_analise';
                $user->save();

                $this->dispatch(new SendDocumentacaoEmail($user));

                return response()->json([
                    'success' => true,
                    'msg' => 'Sua documentação foi enviada para análise.',
                    'flash' => 'warning',
                ], 200);
            }
        }

        private function storeDocCpfVerso(Request $request)
        {
            if ($request->hasFile('imagem') && Auth::user()->status_cpf !== 'validado') {
                if (Auth::user()->image_cpf_verso) {
                    if (Storage::disk('interno')->exists('documentos/'.Auth::user()->image_cpf_verso)) {
                        Storage::disk('interno')->delete('documentos/'.Auth::user()->image_cpf_verso);
                    }
                }

                $cpfImage = str_slug(uniqid(), '_').'.'.strtolower($request->file('imagem')->getClientOriginalExtension());
                $request->file('imagem')->move(Storage::disk('interno')->getDriver()->getAdapter()->getPathPrefix().'/documentos', $cpfImage);

                $user = Auth::user();
                $user->image_cpf_verso = $cpfImage;
                $user->status_cpf = 'em_analise';
                $user->save();

                $this->dispatch(new SendDocumentacaoEmail($user));

                return response()->json([
                    'success' => true,
                    'msg' => 'Sua documentação foi enviada para análise.',
                    'flash' => 'warning',
                ], 200);
            }
        }

        /**
         * @param Request $request
         * @return \Illuminate\Http\JsonResponse
         */
        private function storeDocEndereco(Request $request)
        {
            if ($request->hasFile('imagem') && Auth::user()->status_comprovante_endereco !== 'validado') {
                if (Auth::user()->image_comprovante_endereco) {
                    if (Storage::disk('interno')->exists('documentos/'.Auth::user()->image_comprovante_endereco)) {
                        Storage::disk('interno')->delete('documentos/'.Auth::user()->image_comprovante_endereco);
                    }
                }

                $comprovanteEnderecoImage = str_slug(uniqid(), '_').'.'.strtolower($request->file('imagem')->getClientOriginalExtension());
                $request->file('imagem')->move(Storage::disk('interno')->getDriver()->getAdapter()->getPathPrefix().'/documentos', $comprovanteEnderecoImage);

                $user = Auth::user();
                $user->image_comprovante_endereco = $comprovanteEnderecoImage;
                $user->status_comprovante_endereco = 'em_analise';
                $user->save();

                $this->dispatch(new SendDocumentacaoEmail($user));

                return response()->json([
                    'success' => true,
                    'msg' => 'O seu comprovante de endereço foi enviado para análise.',
                    'flash' => 'warning',
                ], 200);
            }
        }

        /**
         * @param Request $request
         * @return \Illuminate\Http\JsonResponse
         */
        private function storeDocSelfie(Request $request)
        {
            if ($request->hasFile('imagem') && Auth::user()->status_selfie !== 'validado') {
                if (Auth::user()->image_selfie) {
                    if (Storage::disk('interno')->exists('documentos/'.Auth::user()->image_selfie)) {
                        Storage::disk('interno')->delete('documentos/'.Auth::user()->image_selfie);
                    }
                }

                $selfieImage = str_slug(uniqid(), '_').'.'.strtolower($request->file('imagem')->getClientOriginalExtension());
                $request->file('imagem')->move(Storage::disk('interno')->getDriver()->getAdapter()->getPathPrefix().'/documentos', $selfieImage);

                $user = Auth::user();
                $user->image_selfie = $selfieImage;
                $user->status_selfie = 'em_analise';
                $user->save();

                $this->dispatch(new SendDocumentacaoEmail($user));

                return response()->json([
                    'success' => true,
                    'msg' => 'Sua selfie foi enviada para análise.',
                    'flash' => 'warning',
                ], 200);
            }
        }

        private function storeDoc(Request $request, $campo, $status)
        {
            $responsavel = Auth::user()->responsavel->where('status', 0)->first();

            if ($request->hasFile('imagem') && $responsavel->$status !== 'validado') {
                if ($responsavel->$campo) {
                    if (Storage::disk('interno')->exists('documentos/responsavel/'.$responsavel->$campo)) {
                        Storage::disk('interno')->delete('documentos/responsavel/'.$responsavel->$campo);
                    }
                }

                $image = str_slug(uniqid(), '_').'.'.strtolower($request->file('imagem')->getClientOriginalExtension());
                $request->file('imagem')->move(Storage::disk('interno')->getDriver()->getAdapter()->getPathPrefix().'/documentos/responsavel', $image);

                $user = $responsavel;
                $user->$campo = $image;
                $user->$status = 'em_analise';
                $user->save();

                $this->dispatch(new SendDocumentacaoEmail(Auth::user()));

                return response()->json([
                    'success' => true,
                    'msg' => 'Seu documento foi enviada para análise.',
                    'flash' => 'warning',
                ], 200);
            }
        }

        private function storeDocVerso(Request $request, $campo, $status)
        {
            $responsavel = Auth::user()->responsavel->where('status', 0)->first();

            if ($request->hasFile('imagem') && $responsavel->$status !== 'validado') {
                if ($responsavel->$campo) {
                    if (Storage::disk('interno')->exists('documentos/responsavel/'.$responsavel->$campo)) {
                        Storage::disk('interno')->delete('documentos/responsavel/'.$responsavel->$campo);
                    }
                }

                $image = str_slug(uniqid(), '_').'.'.strtolower($request->file('imagem')->getClientOriginalExtension());
                $request->file('imagem')->move(Storage::disk('interno')->getDriver()->getAdapter()->getPathPrefix().'/documentos/responsavel', $image);

                $user = $responsavel;
                $user->$campo = $image;
                $user->$status = 'em_analise';
                $user->save();

                $this->dispatch(new SendDocumentacaoEmail(Auth::user()));

                return response()->json([
                    'success' => true,
                    'msg' => 'Seu documento foi enviada para análise.',
                    'flash' => 'warning',
                ], 200);
            }
        }
    }
