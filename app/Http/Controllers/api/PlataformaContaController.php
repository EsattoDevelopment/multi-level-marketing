<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Models\PlataformaConta;
use App\Http\Controllers\Controller;

class PlataformaContaController extends Controller
{
    public function contas(Request $request)
    {
        $contas = PlataformaConta::where('plataforma_id', $request->plataforma_id)->get();

        return response()->json($contas);
    }
}
