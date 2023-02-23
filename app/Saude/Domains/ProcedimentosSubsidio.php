<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Domains;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProcedimentosSubsidio.
 */
class ProcedimentosSubsidio extends Model
{
    protected $table = 'procedimentos_subsidio';

    public $timestamps = false;

    protected $fillable = [
        'procedimentos_id',
        'user_id',
        'inicio_vigencia',
        'fim_vigencia',
        'status',
    ];

    protected $guarded = [];
}
