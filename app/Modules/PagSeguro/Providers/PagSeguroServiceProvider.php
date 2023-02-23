<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Modules\PagSeguro\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class PagSeguroServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerRouter($this->app['router']);
        $this->loadViewsFrom(__DIR__.'/../Resources/Views', 'pagseguro');

        $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations'),
            ], 'migrations');
    }

    public function register()
    {
        // TODO: Implement register() method.
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function registerRouter(Router $router)
    {
        $router->group([
                'namespace' => 'App\Modules\PagSeguro\Http\Controllers',
                'prefix' => 'pagseguro',
            ], function ($router) {
                require app_path('Modules/PagSeguro/Http/routes.php');
            });
    }
}
