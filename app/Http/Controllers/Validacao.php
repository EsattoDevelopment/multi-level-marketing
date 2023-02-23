<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;

class Validacao extends Controller
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'code' => 'required|digits:6|validate2fa',
        ], [
            'code.validate2fa' => '',
        ]);
    }

    public function validate2fa(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        return \Response::json([], 200);
    }
}
