<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 *
 */

namespace App\Http\ViewComposers;

use App\Models\Empresa;
use Illuminate\Contracts\View\View;

class DadosEmpresa
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
        $dadosEmpresa = Empresa::findOrFail(1);
        $view->with('dadosEmpresa', $dadosEmpresa);
    }
}
