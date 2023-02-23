<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Requests;

use App\Models\Sistema;

class TransferenciaRequest extends Request
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
        $dados = $this->request->all();
        $campos = [];

        if(isset($dados['leiaute'])){
            $campos['valor'] = 'required|saldocomtaxainterno|transferenciainterna';
        }else{
            $campos['valor'] = 'required|saldocomtaxaexterno|transferenciaexterna';
        }

        $campos['conta_id'] = 'required_without:user|min:1';
        $campos['user'] = 'required_without:conta_id';

        if ($sistema->habilita_autenticacao_transferencias) {
            $campos['code'] = 'required|validate2fa';
        }

        return $campos;
    }

    public function messages()
    {
        return [
            'valor.saldocomtaxainterno' => 'Saldo insuficiente para a operação',
            'valor.saldocomtaxaexterno' => 'Saldo insuficiente para a operação',
            'valor.transferenciainterna' => 'O valor não atende o valor minimo de transferência.',
            'valor.transferenciaexterna' => 'O valor não atende o valor minimo de transferência.',
            'valor.required' => 'Por favor digite um valor!',
            'conta_id.required_without' => 'Por favor escolha uma conta',
            'user.required_without' => 'Não há usuário selecionado, volte a tela anterior',
            'code.validate2fa' => 'Código inválido, informe um novo código.',
        ];
    }
}
