<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Http\Requests;

use App\Http\Requests\Request;

class DependentesRequest extends Request
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
                'name'       => 'required',
                'sexo'       => 'required',
                'dt_nasc'    => 'required',
                'status'     => 'required',
                'parentesco' => 'required',
                'titular_id' => 'required',
            ];
    }

    public function messages()
    {
        return [
                'parentesco' => '',
            ];
    }
}
