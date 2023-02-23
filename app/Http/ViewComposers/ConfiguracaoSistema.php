<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\ViewComposers;

use App\Models\Sistema;
use Illuminate\Contracts\View\View;

class ConfiguracaoSistema
{
    /**
     * Create a new profile composer.
     */
    public function __construct()
    {
        //
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $sistema = Sistema::findOrFail(1);
        $view->with('sistema', $sistema);
    }
}
