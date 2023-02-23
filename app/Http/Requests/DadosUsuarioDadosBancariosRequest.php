<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Sistema;

/**
 * Class DadosUsuarioRequest.
 */
class DadosUsuarioDadosBancariosRequest extends Request
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

        if ($sistema->dados_bancarios_obrigatorio) {
            $retorno['d_bancarios.banco_id'] = 'required';
            $retorno['d_bancarios.tipo_conta'] = 'required';
            $retorno['d_bancarios.agencia'] = 'required';
            $retorno['d_bancarios.agencia_digito'] = 'required';
            $retorno['d_bancarios.conta'] = 'required';
            $retorno['d_bancarios.conta_digito'] = 'required';
        }

        return $retorno;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'd_bancarios.banco_id' => 'Banco',
            'd_bancarios.tipo_conta' => 'Tipo de conta',
            'd_bancarios.agencia' => 'Agência',
            'd_bancarios.agencia_digito' => 'Dígito Agência',
            'd_bancarios.conta' => 'Dígito Conta',
            'd_bancarios.conta_digito' => 'Dígito Conta',
        ];
    }
}
