<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Providers;

use App\Models\User;
use App\Models\Contrato;
use Illuminate\Routing\Router;
use App\Events\BonusMensalidade;
use App\Saude\Domains\Mensalidade;
use App\Events\MensalidadeSemBonus;
use Illuminate\Support\ServiceProvider;
use App\Saude\Events\CancelamentoContrato;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SaudeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerRouter($this->app['router']);
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'saude');

        $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations'),
            ], 'migrations');

        //TODO muda a dependencia de mensalidade do contrato
        Mensalidade::updated(function (Mensalidade $mensalidade) {
            if (3 != $mensalidade->statusPivot) {
                \Log::info("\n $$$$$$$$$$$$$$$$$$$$$$$$ Edição de mensalidade $$$$$$$$$$$$$$$$$$$$$$$$");
            }

            if (4 == $mensalidade->statusPivot) {
                try {
                    $contrato = $mensalidade->contratoDependente()->first();

                    if (! $contrato) {
                        $contrato = $mensalidade->contrato()->first();
                    }

                    if ($contrato instanceof Contrato) {

                            //executa os eventos necessários
                        if (1 == $mensalidade->paga_bonus) {
                            \Log::info('Mensalidade setada para pagar bonus');
                            \Event::fire(new BonusMensalidade($mensalidade));
                        } else {
                            \Log::info('Mensalidade setada para não pagar bonus');
                            \Event::fire(new MensalidadeSemBonus($mensalidade));
                        }
                    }

                    return true;
                } catch (ModelNotFoundException $e) {
                    return false;
                }
            } else {
                \Log::info("Update da mensalidade #{$mensalidade->id}");
            }

            return true;
        });

        //TODO tratamento caso contrato seja cancelado
        Contrato::updated(function (Contrato $contrato) {
            if (in_array($contrato->status, [4, 7])) {
                \Log::info("\n\n $$$$$$$$$$$$$$$$$$$$$$$$ Cancelamento de contrato {$contrato->id} $$$$$$$$$$$$$$$$$$$$$$$$");

                \Event::fire(new CancelamentoContrato($contrato));

                \Log::info("\n $$$$$$$$$$$$$$$$$$$$$$$$ Fim - Cancelamento de contrato $$$$$$$$$$$$$$$$$$$$$$$$");
            }

            return true;
        });

        $this->app['validator']->extend('max_tipo_parentes', function ($attribute, $value, $parameters, \Illuminate\Validation\Validator $validator) {
            $user = User::select(['id'])->find($validator->getData()[$parameters[0]]);

            switch ($value) {
                    case 1:
                        $total = $user->conjuge();
                        $max = 1;
                        break;

                    case 2:
                        $total = $user->filhos();
                        $max = 10;
                        break;
                }

            if ($total < $max) {
                return true;
            } else {
                return false;
            }
        });
    }

    public function register()
    {
        // TODO: Implement register() method.
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function registerRouter(Router $router)
    {
        $router->group([
                'namespace' => 'App\Saude\Http\Controllers',
                'prefix'    => 'saude',
            ], function ($router) {
                require app_path('Saude/Http/routes.php');
            });
    }
}
