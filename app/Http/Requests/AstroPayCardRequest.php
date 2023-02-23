<?php

namespace App\Http\Requests;

class AstroPayCardRequest extends Request
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
            'numero_cartao' => 'required|min:16',
            'cvv' => 'required|min:4',
            'data_expiracao' => 'required|min:6|date_format:"m/Y"|data_expiracao',
        ];
    }

    public function messages()
    {
        return [
            'numero_cartao.required' => 'O número do cartão é requerido',
            'numero_cartao.min' => 'O número do cartão deve possuir 16 digitos',
            'cvv.required' => 'O CVV é requerido',
            'cvv.min' => 'O CVV deve possuir 4 digitos',
            'data_expiracao.required' => 'A data de expiração é requerida',
            'data_expiracao.min' => 'A data de expiração deve possuir o formato MM/AAAA',
            'data_expiracao.data_format' => 'A data de expiração deve possuir o formato MM/AAAA',
            'data_expiracao.data_expiracao' => 'A data de expiração informada é invalida',
        ];
    }
}
