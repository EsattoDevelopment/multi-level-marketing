<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Http\Requests;

use App\Http\Requests\Request;

class GuiasRequest extends Request
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
            'tipo'             => 'required|min:1',
            'tipo_atendimento' => 'required|min:1',
            'dt_atendimento'   => 'required',
            'medico_id'        => 'required_if:tipo_atendimento,2',
            'dependente_id'    => 'required_if:required,2',
            'user_id'          => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'tipo'             => 'Guia para Titular ou dependente',
            'tipo_atendimento' => 'Tipo de atendimento ',
            'dt_atendimento'   => 'Data do atendimento',
            'medico_id'        => 'Medico',
            'dependente_id'    => 'Dependentes',
            'user_id'          => 'Paciente/Titular',
        ];
    }
}
