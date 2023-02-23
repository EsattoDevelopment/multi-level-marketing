<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers\api;

use App\Models\Itens;
use App\Models\MetodoPagamento;
use App\Models\Pedidos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TaxasPagamentoController extends Controller
{
    public function astroPayCard($user_id, $pedido_id)
    {
        try {
            $pedido = Pedidos::with('dadosPagamento')
                ->whereId($pedido_id)
                ->whereUserId($user_id)
                ->first();

            //verifico se tem valor da taxa
            $metodoPagamento = MetodoPagamento::where('id', 11)->first();

            if ($pedido == null || $metodoPagamento == null) {
                return response()->json(['status' => 'error', 'message' => 'Ocorreu um erro ao verificar as informações do depósito e método de pagamento selecionado.'], 500);
            }

            $valorTotal = $pedido->dadosPagamento->valor;
            $valorTaxa = 0;

            if($metodoPagamento->taxa_valor > 0 || $metodoPagamento->taxa_porcentagem > 0) {
                $valorTaxa = $metodoPagamento->taxa_valor;
                if($metodoPagamento->taxa_porcentagem > 0)
                    $valorTaxa += ($pedido->dadosPagamento->valor * $metodoPagamento->taxa_porcentagem) / 100;

                $valorTotal = $valorTotal + convertDoubleGeral($valorTaxa);
            }

            return response()->json(
                [
                    'status' => 'success',
                    'valorTotal' => mascaraMoeda("R$", $valorTotal, 2, false),
                    'valorTaxa' => mascaraMoeda("R$", $valorTaxa, 2, false),
                    'descrtaxa' => $metodoPagamento->taxa_descricao
                ],
                200);

        }catch (\Exception $e){
            return response()->json(['status' => 'error', 'message' => 'Ocorreu um erro ao verificar as informações do método de pagamento selecionado.'], 500);
        }
    }
}
