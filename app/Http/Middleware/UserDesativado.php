<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserDesativado
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
        if (0 == Auth::user()->status || Auth::user()->can(['master', 'admin']) || is_null(Auth::user()->empresa_id)) {
            return $next($request);
        } else {
            if (is_null(Auth::user()->empresa_id)) {
                flash()->error('Operação permitida somente antes da ativação. <br> Para alterações contate a '.env('COMPANY_NAME_SHORT').'!');
            } else {
                flash()->error('Você não tem autorização para incluir ou alterar dependentes. <br> Para inclusão contate sua empresa ou a EMPRESA DE '.env('COMPANY_NAME_SHORT').'!');
            }

            return redirect()->back();
        }
    }
}
