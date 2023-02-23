<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Requests;

class PacotesRequest extends Request
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
                'status'           => 'required',
                'promocao'         => 'required',
                'estado'           => 'required',
                'cidade_id'        => 'required',
                'chamada'          => 'required',
                'descricao'        => 'required',
                //'valor_milhas' => 'required|min:0',
                'quantidade_vagas' => 'required|min:-1',
                'acomodacao'      => 'required|tipo_acomodacoes',
            ];
    }

    public function messages()
    {
        return [
                'acomodacao.tipo_acomodacoes' => 'O valor das acomodações selecionadas é obrigatório!',
            ];
    }
}
