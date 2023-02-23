<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Traits;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

/**
 * Trait Encryptable.
 */
trait Encryptable
{
    /**
     * @param $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        if (in_array($key, $this->encryptable) && $value !== '') {
            try {
                $value = Crypt::decrypt($value);
            } catch (DecryptException $e) {
            }
        }

        return $value;
    }

    /**
     * @param $key
     * @param $value
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptable)) {
            $value = Crypt::encrypt($value);
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * @return array
     */
    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();
        foreach ($this->encryptable as $key) {
            if (isset($attributes[$key])) {
                try {
                    $attributes[$key] = Crypt::decrypt($attributes[$key]);
                } catch (DecryptException $e) {
                }
            }
        }

        return $attributes;
    }
}
