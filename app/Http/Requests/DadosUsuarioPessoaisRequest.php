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
class DadosUsuarioPessoaisRequest extends Request
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
        $usuario = User::where('id', '=', $this->request->get('user_id'))->first(); //->status_cpf;
        $sistema = Sistema::findOrFail(1);

        $retorno['pessoal.celular'] = 'required';
        $retorno['pessoal.email'] = 'email|max:255|unique:users,email,'.$this->user()->id;

        if (! $usuario->validado) {
            //$retorno['pessoal.estado_civil'] = 'required';
            $retorno['pessoal.name'] = 'required|palavras:2';
            $retorno['pessoal.email'] = 'required|email|max:255|unique:users,email,'.$this->user()->id;

            if ($sistema->dtnasc) {
                $retorno['pessoal.data_nasc'] = 'required';
            }

            if ($sistema->rede_binaria) {
                $retorno['equipe_preferencial'] = 'required';
            }

            if ($usuario->isEmpresa) {
                $retorno['pessoal.empresa'] = 'required';
            }

            if ($usuario->nascimento->diffInYears(\Carbon\Carbon::now()) < 18) {
                $retorno['responsavel.nome'] = 'required|palavras:2';
                $retorno['responsavel.email'] = 'required';
                $retorno['responsavel.cpf'] = 'required|cpf';
                $retorno['responsavel.rg'] = 'required';
                $retorno['responsavel.data_nasc'] = 'required|idade:18';
                $retorno['responsavel.user_id'] = 'required';
                $retorno['responsavel.telefone'] = 'required';
            }
        }

        return $retorno;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'pessoal.name' => 'Nome',
            'pessoal.empresa' => 'Nome da Empresa',
            'pessoal.email' => 'E-mail',
            'pessoal.cpf' => 'CPF',
            'pessoal.celular' => 'Celular',
            'pessoal.data_nasc' => 'Data de Nascimento',
            'pessoal.estado_civil' => 'Estado Civil',
            'equipe_preferencial' => 'Lado Preferencial (Binário)',
            '_bancarios.banco_id' => 'Banco',
            'd_bancarios.agencia' => 'Agência',
            'd_bancarios.agencia_digito' => 'Dígito Agência',
            'd_bancarios.conta' => 'Dígito Conta',
            'd_bancarios.conta_digito' => 'Dígito Conta',
            'responsavel.nome' => 'Nome do responsável',
            'responsavel.data_nasc' => 'Idade do responsável',
            'responsavel.cpf' => 'CPF do responsável',
        ];
    }

    public function messages()
    {
        return [
            'responsavel.data_nasc.idade' => 'O responsável não pode ser menor de :min anos',
        ];
    }
}
