<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Modules\NumerosPorExtenso;

use Illuminate\Support\ServiceProvider;

class NumeroPorExtensoServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register blade directives
        $this->bladeDirectives();
    }

    public function register()
    {
        $this->registerNumeroPorExtenso();
    }

    private function bladeDirectives()
    {
        \Blade::directive('numToTxt', function ($expression) {
            return "<?php echo NumeroPorExtenso::converter{$expression}; ?>";
        });
    }

    /**
     * Register the application bindings.
     *
     * @return void
     */
    private function registerNumeroPorExtenso()
    {
        $this->app->bind('numeroPorExtenso', function ($app) {
            return new NumeroPorExtenso($app);
        });

        $this->app->alias('numeroPorExtenso', 'App\Modules\NumerosPorExtenso\NumerosPorExtenso');
    }
}
