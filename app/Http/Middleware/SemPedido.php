<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SemPedido
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
        if (Auth::user()->can(['master', 'admin'])) {
            return $next($request);
        } else {
            $pedidos = Auth::user()->pedidos()->count();

            if ($pedidos > 0) {
                flash()->error('Após realização do pedido não é mais possivel incluir ou editar dependentes. <br> Para inclusão contate sua empresa ou a '.env('COMPANY_NAME_SHORT').'!');

                return redirect()->back();
            } else {
                return $next($request);
            }
        }
    }
}
