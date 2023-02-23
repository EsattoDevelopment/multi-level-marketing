<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Modal.
 */
class Modal extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'modal';

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'nomeArquivo',
        'descricao',
        'data_inicio',
        'data_fim',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
