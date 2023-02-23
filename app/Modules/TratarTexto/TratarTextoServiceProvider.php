<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Modules\TratarTexto;

use Illuminate\Support\ServiceProvider;

class TratarTextoServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register blade directives
        $this->bladeDirectives();
    }

    public function register()
    {
        $this->registerTratarTexto();
    }

    /**
     * Register the blade directives.
     *
     * @return void
     */
    private function bladeDirectives()
    {
        \Blade::directive('abreviar', function ($expression) {
            return "<?php echo TratarTexto::abreviar({$expression}); ?>";
        });
    }

    /**
     * Register the application bindings.
     *
     * @return void
     */
    private function registerTratarTexto()
    {
        $this->app->bind('tratarTexto', function ($app) {
            return new TratarTexto($app);
        });

        $this->app->alias('tratarTexto', 'App\Modules\TratarTexto\TratarTexto');
    }
}
