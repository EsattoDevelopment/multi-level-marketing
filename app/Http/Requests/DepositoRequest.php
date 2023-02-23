<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Requests;

use App\Models\Sistema;
use Illuminate\Validation\Factory;

/**
 * Class DepositoRequest.
 */
class DepositoRequest extends Request
{
    /**
     * @var
     */
    protected $sistema;

    /**
     * DepositoRequest constructor.
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->sistema = Sistema::find(1);

        $factory->extend(
            'lmin',
            function ($attribute, $value, $parameters, $validator) {
                $value = str_replace([$this->sistema->moeda, ' '], '', $value);
                $value = str_replace('.', '', $value);
                $value = str_replace(',', '.', $value);
                $value = (float) $value;

                return $value >= $parameters[0];
            },
            'O :attribute deve ser maior que <b> '.mascaraMoeda($this->sistema->moeda, $this->sistema->min_deposito, 2, true).'</b>.'
        );
    }

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
            'valor' => 'required|lmin:'.$this->sistema->min_deposito,
        ];
    }
}
