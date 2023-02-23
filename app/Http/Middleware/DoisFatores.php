<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class DoisFatores
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->google2fa_secret) {
            return $next($request);
        }

        flash()->warning('Esta operação necessita que a verificação de 2 fatores esteja ativa, <a href="'.route('dados-usuario.seguranca').'">clique aqui</a> para ativar.');

        return redirect()->back();
        // return $next($request);
    }
}
