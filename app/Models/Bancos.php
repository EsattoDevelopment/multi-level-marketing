<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bancos extends Model
{
    protected $table = 'banco';

    protected $fillable = ['nome', 'codigo'];

    public function contas()
    {
        return $this->hasMany(ContasEmpresa::class);
    }
}
