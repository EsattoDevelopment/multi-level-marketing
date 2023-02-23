<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Requests;

class DownloadRequest extends Request
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
        switch ($this->getMethod()) {

            case 'POST':
                return [
                    'arquivo'   => 'required|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,ai',
                    'title'     => 'required|string|min:4',
                ];
                break;

            case 'PUT':
                return [
                    'arquivo'   => 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,ai',
                    'title'     => 'required|string|min:4',
                ];
                break;

        }
    }

    /**
     * Set custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'arquivo.mimes' => 'Os arquivos devem ser: JPG, PNG, AI, PDF, Word, Excel e PowerPoint',
        ];
    }
}
