<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Modules\PagSeguro\Http\Controllers;

use App\Models\Pedidos;
use App\Modules\PagSeguro\Code;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PagSeguroController extends BaseController
{
    public function codigo($pedido)
    {
        try {
            $pedido = Pedidos::with('itens')->findOrFail($pedido);

            $codigoPagseguro = new Code($pedido->getRelation('itens')->first());

            $retorno = $codigoPagseguro->getCode();

            if (is_array($retorno)) {
                \Log::error('Erro na solicitação do facebook', $retorno);
                dd($retorno);
            }

            return $retorno;
        } catch (ModelNotFoundException $e) {
            \Log::error('Erro na solicitação do facebook', $retorno);
            dd('erro');
        }
    }
}
