<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Remessa extends Model
{
    protected $fillable = ['id', 'numero', 'arquivo', 'efetivado', 'dt_efetivado'];

    protected $dates = ['created_at', 'dt_efetivado'];

    public $incrementing = false;

    public function boletos()
    {
        return $this->hasMany(Boletos::class);
    }
}
