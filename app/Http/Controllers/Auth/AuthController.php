<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers\Auth;

use Log;
use Cache;
use Config;
use Validator;
use App\Models\Role;
use App\Models\User;
use App\Models\Sistema;
use App\Models\Titulos;
use Illuminate\Http\Request;
use App\Notifications\BoasVindas;
use Illuminate\Support\Facades\DB;
use App\Notifications\LoginUsuario;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ValidateSecretRequest;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

/**
 * Class AuthController.
 */
class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    //protected $redirectTo;
    /**
     * @var string
     */
    protected $username = 'username';
    /**
     * @var
     */
    private $sistema;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth', ['except' => 'getLogout']);
        $this->sistema = Sistema::findOrFail(1);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $campos['name'] = 'required|max:255|palavras:2';
        $campos['email'] = 'required|email|max:255|unique:users';
        $campos['indicador'] = 'required';

        $validarDocumentosNacionais = (! $this->sistema->habilita_estrangeiro || ! ($this->sistema->habilita_estrangeiro && isset($data['estrangeiro'])));

        if ($validarDocumentosNacionais) {
            if ($this->sistema->campo_cpf) {
                if (strlen($data['cpf']) == 18) {
                    $campos['cpf'] = 'required|cnpj|unique:users';
                } else {
                    $campos['cpf'] = 'required|cpf|unique:users';
                    if ($this->sistema->campo_dtnasc) {
                        $campos['data_nasc'] = 'date_format:"d/m/Y"|required';
                    }
                    if ($this->sistema->campo_rg) {
                        $campos['rg'] = 'required';
                    }
                }
            }
        }

        $campos['indicadorID'] = 'required';
//        $campos['username'] = 'required|alpha_dash|max:20|min:3|unique:users';
        $campos['password'] = 'required|confirmed|min:6';
        $campos['termo'] = 'required';

        return Validator::make($data, $campos);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        try {
            $titulo = Titulos::whereTituloInicial(1)->first();

            DB::beginTransaction();

            $dados['name'] = $data['name'];
            $dados['email'] = $data['email'];
//            $dados['username'] = $data['username'];
            $dados['username'] = str_slug($dados['email']);
            $dados['indicador_id'] = $data['indicadorID'];
            $dados['termo'] = $data['termo'];

            if ($this->sistema->campo_dtnasc) {
                $dados['data_nasc'] = $data['data_nasc'];
            }

            if (! isset($data['estrangeiro']) && ! $this->sistema->habilita_estrangeiro) {
                if ($this->sistema->campo_rg) {
                    $dados['rg'] = $data['rg'];
                }

                if ($this->sistema->campo_cpf) {
                    $dados['cpf'] = $data['cpf'];
                    $cpf = preg_replace('/[^0-9]/', '', $dados['cpf']);
                    $dados['cpf'] = $this->mask($cpf, '###.###.###-##');

                    if (strlen($cpf) == 14) {
                        $dados['cpf'] = $this->mask($cpf, '##.###.###/####-##');
                    }
                }
            }

            $dados['cpf'] = isset($data['cpf']) ? $data['cpf'] : null;

            $dados['titulo_id'] = $titulo->id;
            $dados['status'] = Config::get('constants.status.ativacao_pendente');
            $dados['password'] = $data['password'];
            $dados['empresa'] = null;
            $dados['estrangeiro'] = isset($data['estrangeiro']) ? true : false;

            $user = User::create($dados);

            //crio a conta
            $contaP1 = rand(1, 9);
            $contaP3 = rand(0, 9);
            $contaDv = rand(1, 9);
            $conta = $contaP1.$user->id.$contaP3.'-'.$contaDv;
            $username = $contaP1.$user->id.$contaP3.$contaDv;

            $user->update(['conta' => $conta, 'username' => $username]);

            unset($data['_token']);

            Log::info('Usuario Cadastrado', $data);

            flash()->warning('Por favor entre no sistema para termino do cadastro!');

            $user->attachRole(Role::whereName('usuario-comum')->first()->id);

            DB::commit();

            $user->notify(new BoasVindas());

            return $user;
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            flash()->error('Desculpe, erro ao salvar usuário');

            Log::error('Erro ao cadastrar usuário');

            return redirect()->route('auth.register');
        }
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        if (strlen($request->get('cpf')) > 14) {
            $request->merge(['empresa' => $request->get('name')]);
        }

        $user = $this->create($request->all());

        Auth::loginUsingId($user->id);

        return redirect(route('dados-usuario', $user)); // Change this route to your needs
    }

    /**
     * @param $conta
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getRegisterIndicador($conta)
    {
        //$validator = $this->validatorUser(['conta' => $conta]);

        //if (!$validator->fails())

        $indicador = User::with('role')->whereConta($conta)
                ->Ativo()
                ->RolesUsuarios()
                ->whereHas('titulo', function ($query) {
                    $query->where('habilita_rede', 1);
                })
                ->whereNull('empresa_id')
                ->select('id', 'name', 'empresa', 'cpf')
                ->first();

        if ($indicador != null) {
            $indicador->name = 'Agente '.$indicador->name;

            return view('auth.register', [
                    'indicador' => $indicador,
                ]);
        } else {
            flash()->error('Não foi possível prosseguir com o cadastro, por gentileza entre em contato com seu agente!');

            return view('auth.register');
        }
    }

    /**
     * Valida usuario.
     *
     * @param array $data
     * @return mixed
     */
    public function validatorUser(array $data)
    {
        return Validator::make($data, [
            'conta' => 'conta',
        ]);
    }

    /**
     * @param $val
     * @param $mask
     * @return string
     */
    private function mask($val, $mask)
    {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k])) {
                    $maskared .= $val[$k++];
                }
            } else {
                if (isset($mask[$i])) {
                    $maskared .= $mask[$i];
                }
            }
        }

        return $maskared;
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            $this->loginUsername() => 'required', 'password' => 'required',
        ]);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);

        if (filter_var(request('username'), FILTER_VALIDATE_EMAIL)) { //email
            $credentials['email'] = $credentials['username'];
            unset($credentials['username']);
        } elseif (Validator::make(['username' => $request->get('username')], ['username' => 'cpf'])->passes()) {
            $cpf = preg_replace('/[^0-9]/', '', $credentials['username']);
            $credentials['cpf'] = $this->mask($cpf, '###.###.###-##');
            if (strlen($cpf) == 14) {
                $credentials['cpf'] = $this->mask($credentials['cpf'], '##.###.###/####-##');
            }
            unset($credentials['username']);
        } elseif (preg_match('/^[0-9]{3,}$|^[0-9]{2,}.[0-9]$/', $credentials['username'])) {
            $credentials['username'] = preg_replace('/[^0-9]/', '', $credentials['username']);
        } else {
            $credentials['username'] = '';
        }

        if (Auth::attempt($credentials, $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }

        return redirect($this->loginPath())
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                $this->loginUsername() => $this->getFailedLoginMessage(),
            ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getLogout()
    {
        if (session()->has('hasClonedUser')) {
            session()->remove('hasClonedUser');
        }

        Auth::logout();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    /**
     * Send the post-authentication response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @return \Illuminate\Http\Response
     */
    private function authenticated(Request $request, Authenticatable $user)
    {
        if ($user->google2fa_secret && $user->google2fa_login) {
            Auth::logout();

            $request->session()->put('2fa:user:id', $user->id);
            $request->session()->put('2fa:user:name', $user->name);
            $request->session()->put('2fa:user:image', $user->image);
            $request->session()->put('2fa:user:titulo', $user->titulo);

            return redirect('2fa/validate');
        }

        Auth::user()->notify(new LoginUsuario(\Request::ip()));

        return redirect('home');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function getValidateToken()
    {
        if (session('2fa:user:id')) {
            return view('default.2fa/validate');
        }

        return redirect('home');
    }

    /**
     * @param  App\Http\Requests\ValidateSecretRequest $request
     * @return \Illuminate\Http\Response
     */
    public function postValidateToken(ValidateSecretRequest $request)
    {
        //get user id and create cache key
        $userId = $request->session()->pull('2fa:user:id');
        $key = $userId.':'.$request->totp;

        //use cache to store token to blacklist
        Cache::add($key, true, 4);

        //add cookie
        // Cookie::make('2fa:user:id:' . $userId, true, 60);

        //login and redirect user
        Auth::loginUsingId($userId);

        /*
         * enviar email de notificação
         */
        Auth::user()->notify(new LoginUsuario(\Request::ip()));
        //$this->dispatch(new \App\Jobs\Send2faEmail(Auth::user(), \Request::ip()));

        return redirect('home');
    }
}
