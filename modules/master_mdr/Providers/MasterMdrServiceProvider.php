<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace MasterMdr\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class MasterMdrServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerRouter($this->app['router']);
        $this->loadViewsFrom(base_path('modules/master_mdr/Resources/Views'), 'master');
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
                'namespace' => 'MasterMdr\Http\Controllers',
                'prefix'    => '',
            ], function ($router) {
                require base_path('modules/master_mdr/Http/routes.php');
            });
    }
}
