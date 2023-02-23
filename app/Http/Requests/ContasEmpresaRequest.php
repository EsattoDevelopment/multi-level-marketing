<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Requests;

class ContasEmpresaRequest extends Request
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
            'banco_id' => 'required',
            'agencia' => 'required',
            'conta' => 'required',
            'favorecido' => 'required_if:recebe_ted,1',
            'cpfcnpj' => 'required_if:recebe_ted,1'.(strlen($this->request->get('cpfcnpj')) == 14 ? '|cpf' : '|cnpj'),

        ];
    }

    public function attributes()
    {
        return [
            'favorecido' => 'favorecido',
        ];
    }

    public function messages()
    {
        return [
            'favorecido.required_if' => 'O nome do favorecido deve ser informado para contas que recebem TED',
            'cpfcnpj.required_if' => 'O CPF/CNPJ deve ser informado para contas que recebem TED',
        ];
    }
}
