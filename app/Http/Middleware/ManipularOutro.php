<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Middleware;

use Closure;
use App\Models\Pedidos;
use App\Models\Contrato;
use App\Saude\Domains\Guia;
use App\Saude\Domains\Mensalidade;
use Illuminate\Support\Facades\Auth;

class ManipularOutro
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
        $prosseguir = true;

        $paramentroNome = $request->route()->parameterNames()[0];
        $id = $request->route($paramentroNome);

        if (! Auth::user()->can(['master', 'admin'])) {
            switch ($paramentroNome) {
                case 'contrato':
                    $qtdContrato = Contrato::where('user_id', Auth::user()->id)->where('id', $id)->count();
                    if ($qtdContrato < 1) {
                        $prosseguir = false;
                    }
                    break;

                case 'mensalidade':
                    $qtdMensalidade = Mensalidade::where('user_id', Auth::user()->id)->where('id', $id)->count();
                    if ($qtdMensalidade < 1) {
                        $prosseguir = false;
                    }
                    break;

                case 'guia':
                    $qtdMensalidade = Guia::where('clinica_id', Auth::user()->id)->where('id', $id)->count();
                    if ($qtdMensalidade < 1 && ! Auth::user()->can(['guia-imprimir-qualquer'])) {
                        $prosseguir = false;
                    }
                    break;

                case 'guias':
                    $qtdMensalidade = Guia::where('clinica_id', Auth::user()->id)->where('id', $id)->count();
                    if ($qtdMensalidade < 1 && ! Auth::user()->can(['guia-editar-qualquer'])) {
                        $prosseguir = false;
                    }
                    break;

                case 'pedido':
                    $qtd = Pedidos::where('user_id', Auth::user()->id)->where('id', $id)->count();
                    if ($qtd < 1) {
                        $prosseguir = false;
                    }
                    break;

                default:
                    if ($id != $request->user()->id) {
                        $prosseguir = false;
                    }
                    break;
            }
        }

        if ($prosseguir) {
            return $next($request);
        } else {
            flash()->error('Você não tem privilégio suficiente para esta ação!');

            return redirect()->back()->with(['mostrar_erro' => true]);
        }
    }
}
