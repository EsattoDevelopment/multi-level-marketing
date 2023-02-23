<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Requests;

use App\Models\Itens;

class PedidoRequest extends Request
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
        $item = Itens::findOrFail($this->get('item'));
        // dd($item);
        return [
                'item'           => 'required',
                //'sen-dependente' => 'required',
                'qtd_itens' => "required|numeric|min:{$item->qtd_min}|max:{$item->qtd_max}",
            ];
    }

    public function messages()
    {
        return [
                'item.required' => 'Escolha ao menos um item antes de realizar o pedido!',
                'sen-dependente.required' => 'Concordar seguir sem dependente é obrigatório.',
            ];
    }

    public function attributes()
    {
        return [
                'qtd_itens' => 'Quantidade de itens',
            ];
    }
}
