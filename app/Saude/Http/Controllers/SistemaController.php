<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Http\Controllers;

use App\Events\RodarSistema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SistemaController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function verificar_mensalidade_contratos()
    {
        if (Auth::user()->can(['master', 'admin'])) {
            DB::beginTransaction();
            \Event::fire(new RodarSistema());
            DB::commit();

            flash()->success('Mensalidade e contratos verificados com sucesso!');

            return redirect()->back();
        } else {
            DB::rollback();

            flash()->error('Você não tem privilégios suficientes para esta operação!');

            return redirect()->route('home');
        }
    }
}
