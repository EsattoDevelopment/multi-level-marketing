<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Domains;

use App\Models\Itens;
use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Exame.
 */
class Exame extends Model
{
//    use Encryptable;
//
//    /**
//     * @var array
//     */
//    protected $encryptable = [
//        'nome'
//    ];

    /**
     * @var array
     */
    protected $fillable = [
        'nome',
        'codigo',
        'descricao',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function itens()
    {
        return $this->belongsToMany(Itens::class, 'itens_exames', 'exame_id');
    }
}
