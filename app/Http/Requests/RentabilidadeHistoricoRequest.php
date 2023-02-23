<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Requests;

class RentabilidadeHistoricoRequest extends Request
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
            'titulo' => 'required',
            'data' => 'required',
            'arquivo' => 'mimes:jpeg,jpg,png',
            'documento' => 'mimes:pdf',
            'plataforma_id' => 'required',
            'plataforma_conta_id' => 'required',
            'valor' => 'required',
            'percentual' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'arquivo.mimes' => 'O arquivo deve ser do tipo: jpeg, jpg ou png',
            'documento.mimes' => 'O documento deve ser do tipo: pdf',
            'plataforma_id.required' => 'A plataforma deve ser informada',
            'plataforma_conta_id.required' => 'A conta deve ser informada',
        ];
    }
}
