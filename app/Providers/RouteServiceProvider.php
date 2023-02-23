<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Providers;

use App\Models\Galeria;
use App\Models\GaleriaImagens;
use Illuminate\Routing\Router;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    //protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        //

        parent::boot($router);

        $router->model('galeria', Galeria::class, function () {
            throw new ModelNotFoundException;
        });

        $router->model('imagem', GaleriaImagens::class, function () {
            throw new ModelNotFoundException;
        });
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $this->mapApiRoutes($router);
        $this->mapAdminRoutes($router);
        $this->mapRelatoriosRoutes($router);
    }

    protected function mapApiRoutes(Router $router)
    {
        $router->group([
            'namespace' => 'App\Http\Controllers\api',
            'prefix'    => 'api',
        ], function ($router) {
            require app_path('routes/api.php');
        });
    }

    protected function mapRelatoriosRoutes(Router $router)
    {
        $router->group([
            'namespace' => 'App\Http\Controllers',
        ], function ($router) {
            require app_path('routes/relatorios.php');
        });
    }

    protected function mapAdminRoutes(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
