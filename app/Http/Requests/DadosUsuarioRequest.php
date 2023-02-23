<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Sistema;
use Illuminate\Support\Facades\Auth;

class DadosUsuarioRequest extends Request
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
        // quer dizer que é só validação do endereço
        if (strpos(back()->getTargetUrl(), '/endereco') !== false) {
            return [];
        }

        $usuario = User::where('id', '=', $this->request->get('user_id'))->first(); //->status_cpf;
        $sistema = Sistema::findOrFail(1);

        if (! $usuario->validado) {
            if ($usuario->pedidos->count() === 0) {
                //$retorno['pessoal.estado_civil'] = 'required';
            }

            $retorno['pessoal.name'] = 'required';
            $retorno['pessoal.email'] = 'required|email|max:255|unique:users';

            if ($sistema->dtnasc && strlen(Auth::user()->cpf) == 14) {
                $retorno['pessoal.data_nasc'] = 'required';
            }

            $retorno['pessoal.celular'] = 'required';
        }

        if ($sistema->endereco_obrigatorio) {
            $retorno['endereco.cep'] = 'required';
            $retorno['endereco.logradouro'] = 'required';
            $retorno['endereco.numero'] = 'required';
            $retorno['endereco.bairro'] = 'required';
            $retorno['endereco.cidade'] = 'required';
            $retorno['endereco.estado'] = 'required';
        }

        if ($sistema->rede_binaria) {
            $retorno['equipe_preferencial'] = 'required';
        }

        if ($sistema->dados_bancarios_obrigatorio) {
            $retorno['d_bancarios.banco_id'] = 'required';
            $retorno['d_bancarios.agencia'] = 'required';
            $retorno['d_bancarios.agencia_digito'] = 'required';
            $retorno['d_bancarios.conta'] = 'required';
            $retorno['d_bancarios.conta_digito'] = 'required';
        }

        $retorno['password'] = 'confirmed|min:6';

        return $retorno;
    }

    public function attributes()
    {
        return [
            'pessoal.name' => 'Nome',
            'pessoal.email' => 'E-mail',
            'pessoal.cpf' => 'CPF',
            'image_cpf' => 'Imagem CPF',
            'image_comprovante_endereco' => 'Imagem do comprovante de endereço',
            'pessoal.celular' => 'Celular',
            'pessoal.data_nasc' => 'Data de Nascimento',
            'endereco.cep' => 'CEP',
            'endereco.logradouro' => 'Endereço',
            'endereco.numero' => 'Número',
            'endereco.bairro' => 'Bairro',
            'endereco.cidade' => 'Cidade',
            'endereco.estado' => 'Estado',
            'pessoal.estado_civil' => 'Estado Civil',
            'equipe_preferencial' => 'Lado Preferencial (Binário)',
            '_bancarios.banco_id' => 'Banco',
            'd_bancarios.agencia' => 'Agência',
            'd_bancarios.agencia_digito' => 'Dígito Agência',
            'd_bancarios.conta' => 'Dígito Conta',
            'd_bancarios.conta_digito' => 'Dígito Conta',
        ];
    }
}
