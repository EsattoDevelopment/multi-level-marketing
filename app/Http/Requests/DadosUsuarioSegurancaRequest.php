<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Requests;

use App\Models\User;

/**
 * Class DadosUsuarioSegurancaRequest.
 */
class DadosUsuarioSegurancaRequest extends Request
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
        $retorno['password'] = 'confirmed|min:6';

        return $retorno;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [];
    }
}
