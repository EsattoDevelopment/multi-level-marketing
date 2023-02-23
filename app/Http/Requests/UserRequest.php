<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Requests;

use App\Models\Sistema;

class UserRequest extends Request
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

        $campos['name'] = 'required|max:255';
        $campos['email'] = 'required|email|max:255|unique:users';
        $campos['indicador_id'] = 'required';
        $campos['email'] = 'required|email';
        $campos['roles'] = 'required|min:1';

        if (strlen($this->get('cpf')) < 18) {
            if ($sistema->campo_rg) {
                $campos['rg'] = 'required';
            }
        }

        if (strlen($this->get('cpf')) < 18) {
            if ($sistema->campo_dtnasc || $sistema->campo_cpf) {
                $campos['data_nasc'] = 'date_format:"d/m/Y"|required';
            }
        }

        //$campos['indicador'] = 'required';
        //$campos['termo'] = 'required';

        if ($sistema->campo_cpf) {
            if (strlen($this->get('cpf')) == 18) {
                $campos['cpf'] = 'required|cnpj';
                $campos['empresa'] = 'required';
            } else {
                $campos['cpf'] = 'required|cpf';
            }
        }

        switch ($this->getMethod()) {

                case 'PUT':
                    $campos['password'] = 'confirmed|min:6';
                    break;

                case 'POST':
                    $campos['password'] = 'required|confirmed|min:6';
                    break;
            }

        return $campos;
    }

    public function attributes()
    {
        return [
                'cpf' => 'CPF/CNPJ',
                'indicador_id' => 'Indicador',
            ];
    }

    public function messages()
    {
        return [
                'email.unique'    => 'E-mail já cadastrado!',
                'cpf.unique'      => 'CPF/CNPJ já cadastrado!',
                'username.unique' => 'Nome de usuário já cadastrado!',
            ];
    }
}
