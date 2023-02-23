<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends BaseController
{
    public function paciente(Request $request)
    {
        $teste = DB::table('users as u')
            ->join('dependentes as d', 'd.user_id', '=', 'u.id')
            ->WhereRaw('u.id not in (1,2)')
            ->WhereRaw('u.tipo not in (2,3)')
            ->Where('u.status', 1)
            ->orWhere('u.name', 'like', "%{$request->get('search')}%")
            ->orWhere('u.codigo', 'like', "%{$request->get('search')}%")
            ->orWhere('u.username', 'like', "%{$request->get('search')}%")
            ->orWhere('u.rg', 'like', "%{$request->get('search')}%")
            ->orWhere('u.cpf', 'like', "%{$request->get('search')}%")
            ->orWhere('d.name', "{$request->get('search')}")
            ->orWhere('d.rg', "{$request->get('search')}")
            ->orWhere('d.cpf', "{$request->get('search')}")
            ->orWhere('u.codigo', "{$request->get('search')}");

        return \Response::json(
            DB::table('users as u')
            ->join('dependentes as d', 'd.user_id', '=', 'u.id')
                ->WhereRaw('u.id not in (1,2)')
                ->WhereRaw('u.tipo not in (2,3)')
                ->Where('u.status', 1)
                ->orWhere('u.name', 'like', "%{$request->get('search')}%")
                ->orWhere('u.codigo', 'like', "%{$request->get('search')}%")
                ->orWhere('u.username', 'like', "%{$request->get('search')}%")
                ->orWhere('u.rg', 'like', "%{$request->get('search')}%")
                ->orWhere('u.cpf', 'like', "%{$request->get('search')}%")
                ->orWhere('d.name', "{$request->get('search')}")
                ->orWhere('d.rg', "{$request->get('search')}")
                ->orWhere('d.cpf', "{$request->get('search')}")
                ->orWhere('codigo', "{$request->get('search')}")
/*                ->select([
                    'id',
                    'name',
                    DB::raw('CASE
                            WHEN i.tipo_pedido_id = 1 THEN "Adesão"
                            WHEN i.tipo_pedido_id = 2 THEN "Mensalidade"
                            WHEN i.tipo_pedido_id = 3 THEN "Renovação"
                            WHEN i.tipo_pedido_id = 4 THEN "MMN"
                            END as "tipo"'),
                ])*/
                ->take(15)->get(),
            200);
    }
}
