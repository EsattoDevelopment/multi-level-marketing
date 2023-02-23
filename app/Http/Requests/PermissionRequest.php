<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Requests;

class PermissionRequest extends Request
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
                        'name'         => 'required:min:5|max:25|alpha_dash',
                        'display_name' => 'required|min:5|unique:permissions',
                    ];
                    break;

                case 'PUT':
                    return [
                        'name'         => 'required:min:5|max:25|alpha_dash|unique:permissions,name,'.$this->route('permission'),
                        'display_name' => 'required|min:5',
                    ];
                    break;

            }
    }
}
