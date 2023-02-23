<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Requests;

class SistemaRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                'sistema_viagens' => 'required',
                'bonus_milha_cadastro' => 'required',
                'bonus_ciclo_hotel' => 'required',
                'milhas_ciclo_hotel' => 'required',
                'validade_milhas_ciclo_hotel' => 'required',
                'sistema_saude' => 'required',
                'paga_bonus_diario_titulo' => 'required',
                'paga_bonus_diario_item' => 'required',
                'matriz_unilevel' => 'required',
                'matriz_fechada' => 'required',
                'matriz_fechada_tamanho' => 'required',
                'profundidade_pagamento_matriz' => 'required',
                'item_direcionado' => 'required',
                'update_titulo' => 'required',
                'update_titulo_automatico' => 'required',
                'moeda' => 'required',
                'rede_binaria' => 'required',
                'valor_ponto_binario' => 'required',
                'bonificacao_diaria' => 'required',
                'bonificacao_diaria_recorrente' => 'required',
                'tipo_teto_pagamento' => 'required',
            ];
    }
}
