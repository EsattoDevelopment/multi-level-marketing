<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Requests;

/**
 * Class VideosRequest.
 */
class VideosRequest extends Request
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return mixed
     */
    public function rules()
    {
        $retorno['nome'] = 'required';
        $retorno['codigo'] = 'required';
        $retorno['categoria'] = 'required';

        return $retorno;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'nome' => 'Nome',
            'codigo' => 'Vídeo',
            'categoria' => 'Categoria',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'nome' => 'O campo Nome é obrigatório',
            'codigo' => 'O campo Vídeo é obrigatório',
            'categoria' => 'O campo Categoria é obrigatório',
        ];
    }
}
