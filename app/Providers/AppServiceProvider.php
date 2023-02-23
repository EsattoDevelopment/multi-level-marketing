<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Providers;

use App\Services\TransferenciaService;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Sistema;
use Faker\Factory as FakerFactory;
use App\Services\FerramentasServices;
use Faker\Generator as FakerGenerator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['validator']->extend('tipoAcomodacoes', function ($attribute, $value, $parameters) {
            foreach ($value as $key => $v) {
                if ($v['valor'] < 1) {
                    return false;
                }
            }

            return true;
        });

        $this->app['validator']->extend('maiorDe18', function ($attribute, $value, $parameters) {
            try {
                $tipoData = strpos($value, '/'); //verifica o formato da data se YYYY-MM-DD ou DD/MM/YYYY

                if ($tipoData !== false) {
                    $value = implode('-', array_reverse(explode('/', $value)));
                }

                $idade = Carbon::parse($value)->diffInYears(Carbon::now());

                if ($idade < 18) {
                    return false;
                }

                return true;
            } catch (\InvalidArgumentException $e) {
            }
        });

        $this->app['validator']->extend('menor21', function ($attribute, $value, $parameters) {
            try {
                $data = explode('/', $value);
                $idade = Carbon::createFromDate($data[2], $data[1], $data[0])->diff(Carbon::now())->y;

                if ($idade > 21) {
                    return false;
                }

                return true;
            } catch (\InvalidArgumentException $e) {
            }
        });

        Validator::extend('idade', function ($attribute, $value, $parameters) {
            try {
                $tipoData = strpos($value, '/'); //verifica o formato da data se YYYY-MM-DD ou DD/MM/YYYY

                if ($tipoData !== false) {
                    $value = implode('-', array_reverse(explode('/', $value)));
                }

                $idade = Carbon::parse($value)->diffInYears(Carbon::now());

                if ($idade < $parameters[0]) {
                    return false;
                }

                return true;
            } catch (\InvalidArgumentException $e) {
            }
        });

        Validator::replacer('idade', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':min', $parameters[0], $message);
        });

        $this->app['validator']->extend('usuario_valido', function ($attribute, $value, $parameters) {
            $usuario = User::whereUsername($value)->first();

            if (! $usuario) {
                return false;
            }

            return true;
        });

        $this->app['validator']->extend('username', function ($attribute, $value, $parameters) {
            $usuario = User::whereUsername($value)
                    ->RolesUsuarios()
                    ->first();

            if (! $usuario) {
                return false;
            }

            return true;
        });

        Validator::extend('palavras', function ($attribute, $value, $parameters) {
            $total = str_word_count($value);
            if (isset($parameters[1])) {
                $caracteres = $parameters[1]; //Uma lista de caracteres adicionais que serão considerados como 'palavra'.
                $total = str_word_count($value, 0, $caracteres);
            }

            if ($total < $parameters[0]) {
                return false;
            }

            return true;
        });

        Validator::replacer('palavras', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':min', $parameters[0], $message);
        });

        /*
         * Verifica se saldo disponivel e suficiente
         * Transferencia em liquidação nao conta como saldo disponivel
         */
        $this->app['validator']->extend('saldo', function ($attribute, $value, $parameters) {
            $saldo = \Auth::user()->ultimoMovimento() ? \Auth::user()->ultimoMovimento()->saldo : 0;

            if ($saldo < 0) {
                return false;
            }

            $saldoBloqueado = \Auth::user()->transferencias->where('status', 1)->sum('valor');
            $saldo = $saldo > $saldoBloqueado ? $saldo - $saldoBloqueado : $saldoBloqueado - $saldo;

            $valor = (new FerramentasServices())->getMoney($value);

            if ($valor > $saldo) {
                return false;
            }

            return true;
        });

        $this->app['validator']->extend('saldocomtaxainterno', function ($attribute, $value, $parameters) {
            $saldo = \Auth::user()->ultimoMovimento() ? \Auth::user()->ultimoMovimento()->saldo : 0;

            if ($saldo < 0) {
                return false;
            }

            /*$saldoBloqueado = \Auth::user()->transferencias->where('status', 1)->sum('valor');
            $saldo = $saldo > $saldoBloqueado ? $saldo - $saldoBloqueado : $saldoBloqueado - $saldo;*/

            $valor = (new FerramentasServices())->getMoney($value);

            $transfService = new TransferenciaService();
            $valor_taxa = $transfService->transferenciaInternaValorTaxa(\Auth::user()->id, $valor);
            $valor += $valor_taxa;

            if ($valor > $saldo) {
                return false;
            }

            return true;
        });

        $this->app['validator']->extend('saldocomtaxaexterno', function ($attribute, $value, $parameters) {
            $saldo = \Auth::user()->ultimoMovimento() ? \Auth::user()->ultimoMovimento()->saldo : 0;

            if ($saldo < 0) {
                return false;
            }

            /*$saldoBloqueado = \Auth::user()->transferencias->where('status', 1)->sum('valor');
            $saldo = $saldo > $saldoBloqueado ? $saldo - $saldoBloqueado : $saldoBloqueado - $saldo;*/

            $valor = (new FerramentasServices())->getMoney($value);

            $transfService = new TransferenciaService();
            $valor_taxa = $transfService->transferenciaExternaValorTaxa(\Auth::user()->id, $valor);
            $valor += $valor_taxa;

            if ($valor > $saldo) {
                return false;
            }

            return true;
        });

        $this->app['validator']->extend('transferenciainterna', function ($attribute, $value, $parameters) {
            $sistema = Sistema::findOrFail(1);

            $valor = (new FerramentasServices())->getMoney($value);

            if ($valor < $sistema->transferencia_interna_valor_minimo) {
                return false;
            }

            return true;
        });

        $this->app['validator']->extend('transferenciaexterna', function ($attribute, $value, $parameters) {
            $sistema = Sistema::findOrFail(1);

            $valor = (new FerramentasServices())->getMoney($value);

            if ($valor < $sistema->transferencia_externa_valor_minimo) {
                return false;
            }

            return true;
        });

        $this->app['validator']->extend('validate2fa', function ($attribute, $value, $parameters) {
            if (\Google2FA::verifyKey(\Crypt::decrypt(\Auth::user()->google2fa_secret), $value)) {
                return true;
            }

            return false;
        });

        $this->app['validator']->extend('data_expiracao', function ($attribute, $value, $parameters) {
            $data = explode("/","$value"); // fatia a string $dat em pedados, usando / como referência
            $d = 1;
            $m = (int)$data[0];
            $y = (int)$data[1];

            // verifica se a data é válida!
            // 1 = true (válida)
            // 0 = false (inválida)
            $res = checkdate($m,$d,$y);
            return $res;
        });

        \Carbon\Carbon::setLocale($this->app->getLocale());
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register('Iber\Generator\ModelGeneratorProvider');
        }

        $this->app->singleton(FakerGenerator::class, function () {
            return FakerFactory::create('pt_BR');
        });
    }
}
