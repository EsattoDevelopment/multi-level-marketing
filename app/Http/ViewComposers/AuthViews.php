<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\ViewComposers;

use App\Models\Empresa;
use Illuminate\Contracts\View\View;

class AuthViews
{
    /**
     * Create a new profile composer.
     *
     * @param Linhas $linhas
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
        $empresa = Empresa::select('nome_fantasia', 'cor', 'logo', 'site', 'background', 'favicon', 'logo_flutuante', 'logo_email', 'link_facebook', 'link_instagram', 'cidade', 'uf', 'nome_termo_inicial', 'termo_inicial', 'background_manutencao')->findOrFail(1);
        $view->with('empresa', $empresa);
    }
}
