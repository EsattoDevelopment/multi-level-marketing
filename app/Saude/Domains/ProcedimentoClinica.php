<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Domains;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProcedimentoClinica.
 */
class ProcedimentoClinica extends Model
{
    public $timestamps = false;

    protected $table = 'procedimento_clinica';

    protected $fillable = [
        'procedimento_id',
        'valor',
    ];

    protected $guarded = [];

    public function procedimento()
    {
        return $this->belongsTo(Procedimento::class, 'procedimento_id');
    }
}
