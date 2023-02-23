<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers\api;

use App\Models\Itens;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItensController extends Controller
{
    public function apiBusca(Request $request)
    {
        return \Response::json(
            Itens::where('name', 'like', "%{$request->get('search')}%")
                ->select('id', 'name')
                ->take(15)->get(),
            200);
    }

    public function order(Request $request)
    {
        try {
            foreach ($request->item as $key => $item_id) {
                Itens::where('id', $item_id)->update(['ordem_exibicao' => $key]);
            }

            return response()->json(['status' => 'success', 'message' => 'Registros ordenadas com sucesso!'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => 'danger', 'message' => 'Ocorreu um erro ao mudar a ordenação. Tente novamente, por favor!'], 500);
        }
    }
}
