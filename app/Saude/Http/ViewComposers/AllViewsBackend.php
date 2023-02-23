<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Http\ViewComposers;

use App\Models\Empresa;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class AllViewsBackend
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    protected $master;
    protected $admin;
    protected $usuarioComum;

    /**
     * Create a new profile composer.
     *
     * @param Linhas $linhas
     */
    public function __construct()
    {
        if (Auth::user()->hasRole('usuario-comum')) {
            $this->usuarioComum = true;
            $this->admin = false;
            $this->master = false;
        }

        if (Auth::user()->hasRole('master')) {
            $this->usuarioComum = false;
            $this->admin = true;
            $this->master = true;
        }

        if (Auth::user()->hasRole('admin')) {
            $this->usuarioComum = true;
            $this->admin = true;
            $this->master = false;
        }
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('master', $this->master);
        $view->with('admin', $this->admin);
        $view->with('usuarioComum', $this->usuarioComum);

        $empresa = Empresa::findOrFail(1);
        $view->with('empresa', $empresa);
    }
}
