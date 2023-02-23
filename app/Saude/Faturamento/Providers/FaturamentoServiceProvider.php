<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Faturamento\Providers;

use Illuminate\Support\ServiceProvider;

class FaturamentoServiceProvider extends ServiceProvider
{
    public function register()
    {
        // TODO: Implement register() method.
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'fat');
    }
}
