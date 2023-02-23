<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Requests;

class UsarMilhasRequest extends Request
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
            'acomodacao' => 'required',
            'pacote' => 'required',
            'from' => 'required',
            'to' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'acomodacao' => 'Acomodação',
            'pacote' => 'Pacote',
            'from' => 'Data de ida',
            'to' => 'Data de volta',
        ];
    }
}
