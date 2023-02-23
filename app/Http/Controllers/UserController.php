<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Sistema;
use App\Models\Titulos;
use App\Models\RedeBinaria;
use Illuminate\Http\Request;
use App\Models\DadosBancarios;
use App\Models\EnderecosUsuarios;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Models\UsersTitulosHitorico;
use Illuminate\Support\Facades\Auth;
use App\Models\EnderecosUsuariosEdit;
use App\Services\TituloUpdateService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class UserController.
 */
class UserController extends Controller
{
    /**
     * @var
     */
    private $sistema;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('permission:master', [
                'only' => [
                    'delete',
                    'destroy',
                ],
            ]);

        $this->middleware('permission:master|admin', [
                'except' => [
                    'indexEmpresa',
                    //'createEmpresa',
                    //'storeEmpresa',
                    'editEmpresa',
                    'empresaUsers',
                    //'updateEmpresa',

                    'apiBusca',
                    'apiBuscaGuia',
                    'apiBuscaEmpresa',
                    'apiBuscaConsultor',
                    'apiBuscaMedicos',
                    'indicador',
                    'pendentes',
                    'diretos',
                    'predefinirEquipe',
                    'redeBinaria',
                    'logarComoBack',
                    'redeBinariaIndex',
                    'verificarUpdateTitulos',
                    'updateUserTitulo',
                    'updateUserTituloAll',
                ],
            ]);

        $this->middleware('role:user-empresa', [
                'only' => [
                    'indexEmpresa',
                    'empresaUsers',
                    //'createEmpresa',
                    //'storeEmpresa',
                    'editEmpresa',
                    //'updateEmpresa',
                ],
            ]);

        $this->middleware('permission:master|admin|gerar-guia-consulta', [
                'only' => [
                    'apiBuscaMedicos',
                ],
            ]);

        $this->middleware('manipularOutro', ['only' => ['predefinirEquipe']]);

        $this->sistema = Sistema::findOrFail(1);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logarComo($id)
    {
        if (Auth::user()->can('master')) {
            session()->put('hasClonedUser', auth()->user()->id);

            Auth::loginUsingId($id);

            return redirect()->route('home');
        } else {
            flash()->error('Você não tem privilégios suficientes para esta operação!');

            return redirect()->route('user.index');
        }
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logarComoBack()
    {
        //if session exists remove it and return login to original user
        if (in_array(session()->get('hasClonedUser'), [1, 2])) {
            auth()->loginUsingId(session()->remove('hasClonedUser'));
            session()->remove('hasClonedUser');

            return redirect()->route('user.index');
        } else {
            flash()->error('Você não tem privilégios suficientes para esta operação!');

            return redirect()->back();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('default.user.index', [
                'title' => 'Lista de Usuários ',
            ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function disabled()
    {
        return view('default.user.disabled', [
                'title' => 'Lista de Usuários desabilitados',
            ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function consultor()
    {
        return view('default.user.consultor', [
                'title' => 'Lista de usuários consultores',
            ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clinica()
    {
        return view('default.user.clinica', [
            'title' => 'Lista de usuários clinica',
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function aprovacaoDoc()
    {
        return view('default.user.aprovacaoDoc', [
            'title' => 'Lista para aprovar documentação',
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function inativo()
    {
        return view('default.user.inativo', [
                'title' => 'Lista de Usuários inativos',
            ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function inadimplente()
    {
        return view('default.user.inadimplente', [
                'title' => 'Lista de Usuários com mensalidade atrasada',
            ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function finalizado()
    {
        return view('default.user.finalizado', [
                'title' => 'Lista de Usuários com contratos finalizados',
            ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexEmpresa()
    {
        return view('default.user.indexEmpresa', [
                'title' => 'Lista de Colaboradores ',
            ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function colaboradores($id)
    {
        $user = User::select('name')->find($id);

        return view('default.user.indexEmpresa', [
                'title' => 'Lista de Colaboradores de '.$user->name,
                'id'    => $id,
            ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createEmpresa()
    {
        return view('default.user.createEmpresa', [
                'title' => 'Cadastro de Usuários ',
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('default.user.create', [
                'title'   => 'Cadastro de Usuários ',
                'roles'   => Auth::user()->id == 1 ? Role::all() : Role::where('id', '>', 2)->get(),
                'titulos' => Titulos::all(),
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->storeDefault($request);

            return redirect()->route('user.index');
        } catch (ModelNotFoundException $e) {
            DB::rollback();

            flash()->error('Desculpe, erro ao salvar usuário');

            return redirect()->back()->withInput();
        }
    }

    /**
     * @param UserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeEmpresa(UserRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->storeDefault($request, true);
        } catch (ModelNotFoundException $e) {
            DB::rollback();

            flash()->error('Desculpe, erro ao salvar usuário');

            return redirect()->back()->withInput();
        }
    }

    /**
     * @param UserRequest $request
     * @param bool $empresa
     */
    private function storeDefault(UserRequest $request, $empresa = false)
    {
        if ($empresa) {
            $request->merge(['empresa_id' => Auth::user()->id]);
            $request->merge(['titulo_id' => 5]);
            $roles[] = Role::where('name', 'usuario-comum')->first()->id;
        } else {
            $roles = $request->get('roles');
        }

        $request->merge(['username' => str_slug($request->email)]);
        $user = User::create($request->except('imagem'));

        //crio a conta
        $contaP1 = rand(1, 9);
        $contaP3 = rand(0, 9);
        $contaDv = rand(1, 9);
        $conta = $contaP1.$user->id.$contaP3.'-'.$contaDv;
        $username = $contaP1.$user->id.$contaP3.$contaDv;

        $user->update(['conta' => $conta, 'username' => $username]);

        $user->attachRoles($roles);

        if ($this->sistema->endereco == 1) {
            $enderecos = $request->get('endereco');
            $enderecos['user_id'] = $user->id;
            $enderecos['user_id_editor'] = $user->id;

            EnderecosUsuarios::create($enderecos);
        } else {
            EnderecosUsuarios::create([
                    'user_id' => $user->id,
                    'cep' => '',
                    'logradouro' => '',
                    'numero' => '',
                    'bairro' => '',
                    'cidade' => '',
                    'estado' => '',
                    'telefone1' => '',
                    'telefone2' => '',
                    'celular' => '',
                    'user_id_editor' => Auth::user()->id,
                ]);
        }

        /*        DadosBancarios::create([
                        'banco' => 0,
                        'agencia' => 0,
                        'agencia_digito' => 0,
                        'conta' => 0,
                        'conta_digito' => 0,
                        'user_id' => $user->id,
                        'user_id_editor' => Auth::user()->id,
                        'tipo_conta' => 0,
                        'receber_bonus' => 0,
                        'banco_id' => 1,
                    ]);*/

        \Log::info('Endereço Cadastrado', $enderecos);

        if ($request->has('clinica_procedimentos')) {
            $user->procedimentos()->attach($request->get('clinica_procedimentos'));
        }

        if ($request->hasFile('imagem')) {
            $nameImage = str_slug(uniqid(), '_').'.'.strtolower($request->file('imagem')->getClientOriginalExtension());

            Storage::put('/images/user/'.$nameImage, file_get_contents($request->file('imagem')->getRealPath()));

            $user->image = $nameImage;
        }

        DB::commit();

        \Log::info('Usuario salvo - '.$request->name);

        flash()->success('Usuário <strong>'.$request->name.'</strong> adicionado com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $usuario = User::findOrFail($id);

        if (! Auth::user()->hasRole('master')) {
            if ($usuario->hasRole(['admin', 'master'])) {
                return redirect()->route('user.index');
            }
        }

        try {
            return view('default.user.edit', [
                    'dados'    => User::withTrashed()->with('indicador', 'empresa', 'clinica')->findOrFail($id),
                    'title'    => 'Edição de usuário ',
                    'roles'    => Auth::user()->id == 1 ? Role::all() : Role::where('id', '>', 2)->get(),
                    'endereco' => $endereco = EnderecosUsuarios::whereUserId($id)->first(),
                    'titulos'  => Titulos::all(),
                ]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, ocorreu um erro ao buscar o Usuário!');

            return redirect()->route('user.index');
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function editEmpresa($id)
    {
        try {
            return view('default.user.editEmpresa', [
                    'dados'    => User::with('indicador')->findOrFail($id),
                    'title'    => 'Edição de usuário ',
                    'roles'    => Auth::user()->id == 1 ? Role::all() : Role::where('id', '>', 2)->get(),
                    'endereco' => $endereco = EnderecosUsuarios::whereUserId($id)->first(),
                    'titulos'  => Titulos::all(),
                ]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, ocorreu um erro ao buscar o Usuário!');

            return redirect()->route('user.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserRequest $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);

            if (empty($request->password)) {
                $user->update($request->except('password', 'password_confirmation', 'imagem'));
            } else {
                $user->update($request->except('imagem'));
            }

            $user->roles()->sync($request->get('roles'));

            if ($request->has('clinica_procedimentos')) {
                $user->procedimentos()->sync($request->get('clinica_procedimentos'));
            } else {
                $user->procedimentos()->detach();
            }

            //$user->save();

            // carrega endereço anterior
            $endereco = EnderecosUsuarios::whereUserId($id)->first();

            if (! $endereco) {
                $endereco = EnderecosUsuarios::create(['user_id' => $id, 'user_id_editor' => null]);
            }

            $enderecoOld = $endereco->toArray();

            //TODO separa variaveis de endereço
            $enderecosNew = $request->get('endereco');
            $enderecosNew['telefone1'] = $request->get('telefone');
            $enderecosNew['celular'] = $request->get('celular');

            $enderecoOld['user_id_editor'] = Auth::user()->id;
            $enderecoOld['enderecos_usuario_id'] = $enderecoOld['id'];

            // salva novo endereco
            $endereco->update($enderecosNew);
            EnderecosUsuariosEdit::create($enderecoOld);
            \Log::info('Endereço alterado', $enderecosNew);

            if ($request->hasFile('imagem')) {
                if ($user->imagem) {
                    if (Storage::exists('/images/user/'.$user->imagem)) {
                        Storage::delete('/images/user/'.$user->imagem);
                    }
                }

                $nameImage = str_slug(uniqid(), '_').'.'.strtolower($request->file('imagem')->getClientOriginalExtension());

                Storage::put('images/user/'.$nameImage, file_get_contents($request->file('imagem')->getRealPath()));

                $user->image = $nameImage;
            }
            DB::commit();

            flash()->success('Usuário  '.$request->get('name').' editado com sucesso!');

            return redirect()->route('user.index');
        } catch (ModelNotFoundException $e) {
            DB::rollback();

            flash()->error('Desculpe, erro ao editar o Usuário.');

            return redirect()->route('user.index');
        }
    }

    /**
     * @param UserRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateEmpresa(UserRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);

            if (empty($request->password)) {
                $user->update($request->except('password', 'password_confirmation', 'imagem'));
            } else {
                $user->update($request->except('imagem'));
            }

            //$user->roles()->sync($request->get('roles'));
            // dd($user);
            $user->save();

            // carrega endereço anterior
            $endereco = EnderecosUsuarios::whereUserId($id)->first();
            $enderecoOld = $endereco->toArray();

            // separa variaveis de endereço
            $enderecosNew = $request->get('endereco');
            $enderecosNew['telefone1'] = $request->get('telefone');
            $enderecosNew['celular'] = $request->get('celular');

            $enderecoOld['user_id_editor'] = Auth::user()->id;
            $enderecoOld['enderecos_usuario_id'] = $enderecoOld['id'];

            // salva novo endereco
            $endereco->update($enderecosNew);
            EnderecosUsuariosEdit::create($enderecoOld);
            \Log::info('Endereço alterado', $enderecosNew);

            if ($request->hasFile('imagem')) {
                if ($user->imagem) {
                    if (Storage::exists('/images/user/'.$user->imagem)) {
                        Storage::delete('/images/user/'.$user->imagem);
                    }
                }

                $nameImage = str_slug(uniqid(), '_').'.'.strtolower($request->file('imagem')->getClientOriginalExtension());

                Storage::put('images/user/'.$nameImage, file_get_contents($request->file('imagem')->getRealPath()));

                $user->image = $nameImage;
            }
            DB::commit();

            flash()->success('Usuário  '.$request->get('name').' editado com sucesso!');

            return redirect()->route('user.empresa');
        } catch (ModelNotFoundException $e) {
            DB::rollback();

            flash()->error('Desculpe, erro ao editar o Usuário.');

            return redirect()->route('user.empresa');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->can('master')) {
            try {
                $user = User::findOrFail($id);

                $user->forceDelete();

                flash()->success('Usuário deletado da base de dados com sucesso!');

                return redirect()->route('user.index');
            } catch (ModelNotFoundException $e) {
                flash()->error('Erro ao deletar o usuário da base de dados!');

                return redirect()->route('user.index');
            }
        } else {
            flash()->error('Você não tem privilégios suficientes para esta operação!');

            return redirect()->route('user.index');
        }
    }

    /**
     * Remove the specified resource from storage.(with soft deletes).
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            User::destroy($id);

            flash()->warning(sprintf('Usuário desativado com sucesso. Caso queira reativar a Usuário <a href="%s">clique aqui</a>.', route('user.recovery', $id)));

            return redirect()->route('user.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao desativar o usuário.');

            return redirect()->route('user.index');
        }
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function recovery($id)
    {
        try {
            User::onlyTrashed()->findOrFail($id)->restore();

            flash()->success('Usuário ativado com sucesso!');

            return redirect()->back();
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, ocorreu um erro ao ativar o Usuário.');

            return redirect()->back();
        }
    }

    /**
     * Busca indicador para cadastro.
     *
     * @return mixed
     */
    public function indicador(Request $request)
    {
        $user = User::whereUsername($request->indicador)
                ->RolesUsuarios()
                ->Ativo()
                ->whereRaw('(select count(*) from role_user ru where ru.user_id = id and ru.role_id in (1)) = 0')
                ->whereNotIn('status', [\Config::get('constants.status.ativacao_pendente')])
                ->select('id', 'name', 'cpf', 'empresa')
                ->first();

        if ($user) {
            return \Response::json(json_encode(['indicador' => $user->id, 'nome' => 'Agênte '.$user->name]), 200);
        } else {
            return \Response::json(json_encode(['message' => 'Não encontrado']), 500);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pendentes()
    {
        return view('default.user.pendentes', [
                'title' => 'Cadastros pendentes',
                'dados' => User::with('endereco')->whereStatus(0)->whereIndicadorId(Auth::user()->id)->get(),
            ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function diretos()
    {
        return view('default.user.diretos', [
                'title' => 'Cadastros diretos',
                'dados' => User::whereIndicadorId(Auth::user()->id)
                    ->select('id', 'name', 'username', 'indicador_id', 'email', 'status')
                    ->get(),
            ]);
    }

    /**
     * @param $user
     * @param $direto
     * @param $equipe
     * @return \Illuminate\Http\RedirectResponse
     */
    public function predefinirEquipe($user, $direto, $equipe)
    {
        try {
            DB::beginTransaction();
            $direto = User::whereIndicadorId($user)->whereId($direto)->first();

            $direto->equipe_predefinida = $equipe;
            $direto->save();

            DB::commit();
            flash()->success('Usuário predefinido com sucesso!');

            return redirect()->route('user.pendentes');
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            flash()->error('Desculpe, ocorreu um erro ao predefinir direção do usuário.');

            return redirect()->route('user.pendentes');
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function redeBinariaIndex()
    {
        if (Auth::user()->status) {
            return view('default.user.rede-binaria', [
                    'title'   => 'Rede Binária',
                    'usuario' => User::findOrFail(Auth::user()->id),
                ]);
        } else {
            flash()->error('Cadastro inativo.');

            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function redeBinaria(Request $request)
    {
        //dd(User::with('redeBinarioSelect.userEsquerda.titulo','redeBinarioSelect.userDireita.titulo', 'titulo')->find($request->get('id')));
        return view('default.user.rede-binaria', [
                'title'   => 'Clube ',
                'usuario' => User::with('redeBinarioSelect.userEsquerda.titulo', 'redeBinarioSelect.userDireita.titulo', 'titulo')->find($request->get('id')),
                'rede'    => RedeBinaria::where('esquerda', $request->get('id'))->orWhere('direita', $request->get('id'))->first(),
            ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function aprovarDoc(Request $request)
    {
        try {
            $user = User::findOrFail($request->get('id'));
            if ($request->get('action') == 'aprovaDoc') {
                $user->status_cpf = 'validado';
            } else {
                $user->status_cpf = 'recusado';
            }
            $user->save();

            return response()->json(['status' => true, 'action' => ($request->get('action') == 'aprovaDoc' ? 'aprovada' : 'recusada')]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => false]);
        }
    }

    public function verificarUpdateTitulos()
    {
        $titulosUpdate = new TituloUpdateService();
        $titulosUpdate->titulosUpdate();
        $lista = $titulosUpdate->getTitulosParaUpdate();

        return view('default.user.titulos-update', [
            'title' => 'Títulos para Update',
            'dados' => $lista->sortBy('name'),
        ]);
    }

    public function updateUserTitulo(Request $request, $user)
    {
        $usuario = User::where('id', $user)->first();

        if ($usuario != null) {
            DB::beginTransaction();

            try {
                $usuario->update(['titulo_id' => $request->titulo_atual_id]);

                UsersTitulosHitorico::create([
                    'user_id' => $user,
                    'titulo_atual_id' => $request->titulo_atual_id,
                    'titulo_antigo_id' => $request->titulo_antigo_id,
                    'responsavel_id' => Auth::user() ? Auth::user()->id : 1,
                    /*'historico' => $linha,*/
                ]);

                DB::commit();

                flash()->success('Update de título efetuado com sucesso!');

                return redirect()->route('user.verificar.update.titulo');
            } catch (ModelNotFoundException $e) {
                DB::rollback();
                flash()->error('Erro ao fazer o update do título!');

                return redirect()->back();
            }
        } else {
            flash()->error('Usúario não localizado!');

            return redirect()->back();
        }
    }

    public function updateUserTituloAll()
    {
        $updateTitulo = new TituloUpdateService();
        if ($updateTitulo->subirTitulos()) {
            flash()->success('Update de títulos efetuado com sucesso!');
        } else {
            flash()->error('Não foi possível fazer o update de título de todos os usúarios');
        }

        return redirect()->route('user.verificar.update.titulo');
    }
}
