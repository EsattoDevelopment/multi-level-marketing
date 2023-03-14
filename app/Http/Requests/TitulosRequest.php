<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Requests;

use App\Models\Sistema;

class TitulosRequest extends Request
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
        $sistema = Sistema::findOrFail(1);

        $retorno['name'] = 'required|min:4';

        if ($sistema->sistema_viagem) {
            $retorno['acumulo_pessoal_milhas'] = 'required|numeric';
            $retorno['milhas_indicador'] = 'required|numeric';
            $retorno['bonus_hvip_diretos'] = 'numeric';
            $retorno['binario_patrocinado'] = 'required';
        }

        //$retorno['bonus_indicador'] = 'required|numeric';

        if ($sistema->matriz_fechada || $sistema->matriz_unilevel) {
            $retorno['min_diretos_aprovados_matriz'] = 'required|numeric';
        }

        if ($sistema->rede_binaria) {
            $retorno['min_pontuacao_perna_menor'] = 'required|numeric';
            $retorno['teto_pagamento_sobre_binario'] = 'required|numeric';
            $retorno['min_diretos_aprovados_binario_perna'] = 'required|numeric';
            $retorno['percentual_binario'] = 'required|numeric';
        }

        $retorno['teto_mensal_financeiro'] = 'required|numeric';

        $retorno['cor'] = 'required|min:6|max:7';
        $retorno['pontos_pessoais_update'] = 'required|numeric';
        $retorno['pontos_equipe_update'] = 'required|numeric';

        return $retorno;
    }

    public function attributes()
    {
        return [
            'bonus_indicador' => 'Bonus para patrocinador',
            'min_diretos_aprovados_binario_perna' => 'Minimo diretos ativos (Binario)',
            'cor' => 'Cor do titulo',
            'percentual_binario' => 'Percentual das GMilhas',
            'teto_mensal_financeiro' => 'Teto financeiro mensal',
            'min_pontuacao_perna_menor' => 'Minimo pontuação (Binário)',
            'teto_pagamento_sobre_binario' => 'Teto pagamento diário do binário',
            'pontos_pessoais_update' => 'Quantidade de GMilhas pessoais',
            'pontos_equipe_update' => 'Quantidade de GMilhas de equipe',
        ];
    }
}
