<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Requests;

use App\Models\Sistema;

class AutenticacaoContratacaoRequest extends Request
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
        $sistema = $sistema = Sistema::findOrFail(1);
        $retorno = [];

        if ($sistema->habilita_autenticacao_contratacao) {
            $retorno['code'] = 'required|validate2fa';
        }

        return $retorno;
    }

    public function messages()
    {
        return [
            'code.validate2fa' => 'Código inválido, informe um novo código.',
        ];
    }
}
