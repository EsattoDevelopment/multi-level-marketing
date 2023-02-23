<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RedeController extends Controller
{
    public function diretos(Request $request)
    {
        $user = User::with('diretos', 'titulo')->where('id', $request->user_id)->first();
        $diretos = [];

        foreach ($user->getRelation('diretos') as $direto) {
            array_push($diretos, ['id' => $direto->id, 'nome' => $direto->name, 'diretosqtde' => $direto->diretos()->count(), 'titulo' => $direto->titulo->name, 'cor' => $direto->titulo->cor, 'statusativo' => $direto->status_ativo, 'corstatus' => $direto->cor_status]);
        }

        return response()->json($diretos);
    }
}
