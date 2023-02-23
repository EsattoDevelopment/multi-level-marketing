<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Sistema;

class DadosUsuarioEnderecoRequest extends Request
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
        $usuario = User::where('id', '=', $this->request->get('user_id'))->first();

        $retorno = [];

        if ($usuario->editar_endereco) {
            if ($sistema->endereco_obrigatorio) {
                $retorno['endereco.cep'] = 'required';
                $retorno['endereco.logradouro'] = 'required';
                $retorno['endereco.numero'] = 'required';
                $retorno['endereco.bairro'] = 'required';
                $retorno['endereco.cidade'] = 'required';
                $retorno['endereco.estado'] = 'required';
            }
        }

        return $retorno;
    }

    public function attributes()
    {
        return [
            'endereco.cep' => 'CEP',
            'endereco.logradouro' => 'Endereço',
            'endereco.numero' => 'Número',
            'endereco.bairro' => 'Bairro',
            'endereco.cidade' => 'Cidade',
            'endereco.estado' => 'Estado',
        ];
    }
}
