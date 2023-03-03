<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

class ContratosCapitalController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master|admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ativos()
    {
        return view('default.contratos-capital.ativos', [
             'title' => 'Credenciamentos Ativos',
        ]);
    }

    public function finalizados()
    {
        return view('default.contratos-capital.finalizados', [
            'title' => 'Credenciamentos Finalizados',
        ]);
    }
}
