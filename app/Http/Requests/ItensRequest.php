<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Requests;

use App\Models\Sistema;
use Illuminate\Validation\Factory as ValidatonFactory;

class ItensRequest extends Request
{
    /**
     * Create a new FormRequest instance.
     *
     * @param \Illuminate\Validation\Factory $factory
     * @return void
     */
    public function __construct(ValidatonFactory $factory)
    {
        $factory->extend(
            'validate_qtd_min_max',
            function ($attribute, $value, $parameters) {
                if ($this->request->get('qtd_min') > $this->request->get('qtd_max') || $this->request->get('qtd_max') < $this->request->get('qtd_min')) {
                    return false;
                }

                return true;
            },
            'Verifique a Qtd. Minima e Qtd. Maxima!'
        );
    }

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

        $retorno['name'] = 'required';
        $retorno['valor'] = 'required';

        if ($sistema->sistema_saude) {
            $retorno['temp_contrato'] = 'required';
        }

        if ($sistema->sistema_viagem) {
            $retorno['milhas'] = 'required|numeric';
            $retorno['libera_hotel'] = 'required';
            $retorno['validade_milhas'] = 'required|numeric';
            $retorno['bonus_milhas_indicador'] = 'required|numeric';
            $retorno['milhas_binaria'] = 'required|numeric';
            $retorno['milhas_binaria_validade'] = 'required|numeric';
            $retorno['milhas_binaria_max_altura'] = 'required|numeric';
        }

        if ($sistema->update_titulo) {
            $retorno['avanca_titulo'] = 'required';
        }

        if ($sistema->rede_binaria) {
            $retorno['pontos_binarios'] = 'required|numeric';
            $retorno['teto_binario_dia'] = 'required';
        }

        $retorno['tipo_pedido_id'] = 'required';
        $retorno['ativo'] = 'required';

        /*        if ($sistema->tipo_teto_pagamento == 2) {
                    $retorno['teto_ganho_geral_percentual'] = 'required';
                }*/

        //$retorno['teto_ganho_geral'] = 'required';

        if ($sistema->item_direcionado) {
            $retorno['user_id'] = 'required';
        }

/*        if ($sistema->tipo_bonus_indicador == 1) {
            $retorno['bonus_indicador'] = 'required';
        } else {
            $retorno['bonus_indicador_percentual'] = 'required';
        }*/

        if($sistema->pagar_bonus_equiparacao){
            if ($sistema->tipo_bonus_equiparacao == 1) {
                $retorno['bonus_equiparacao'] = 'required';
            } else {
                $retorno['bonus_equiparacao_percentual'] = 'required';
            }
        }

        $retorno['imagem'] = 'mimes:jpeg,jpg,png';

      //  $retorno['ativo_qtd'] = 'required|numeric';

/*        if ($this->request->get('ativo_qtd') == 1) {
            $retorno['qtd_min'] = 'required|numeric|min:1|validate_qtd_min_max';
            $retorno['qtd_max'] = 'required|numeric|min:1|validate_qtd_min_max';
        }*/

/*        $retorno['faixa_deposito_min'] = 'required';
        $retorno['faixa_deposito_max'] = 'required';
        $retorno['potencial_mensal_teto'] = 'required|numeric|min:0';
        $retorno['carencia_minima'] = 'required|numeric|min:0';
        $retorno['contrato'] = 'required|numeric|min:0';
        $retorno['resgate_minimo'] = 'required|numeric|min:1';
        $retorno['taxa_resgate'] = 'required|numeric|min:0';*/

        return $retorno;
    }

    public function attributes()
    {
        return [
            'name' => 'Nome',
            'valor' => 'Valor',
            'temp_contrato' => 'Tempo de contrato',
            'avanca_titulo' => 'Avança titulo',
            'pontos_binarios' => 'GMilhas',
            'milhas' => 'required|numeric',
            'libera_hotel' => 'required',
            'validade_milhas' => 'required|numeric',
            'tipo_pedido_id' => 'Tipo de pedido',
            'imagem'   => 'Imagem',
            'teto_binario_dia' => 'Teto pagamento binários dia',
            'teto_ganho_geral_percentual' => 'Teto de ganho (Percentual)',
            'teto_ganho_geral' => 'Teto de ganho',
            'bonus_indicador' => 'Bonus Indicador',
            'bonus_indicador_percentual' => 'Bonus Indicador (Percentual)',
            'bonus_equiparacao' => 'Bonus Equiparação',
            'bonus_equiparacao_percentual' => 'Bonus Equiparação (Percentual)',
            'ativo_qtd' => 'Ativar quantidade para compra',
            'qtd_min' => 'Qtd. mínima',
            'qtd_max' => 'Qtd. máxima',
        ];
    }
}
