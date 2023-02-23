<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersTitulosHitorico extends Model
{
    protected $table = 'users_titulos_historico';

    protected $fillable = [
        'user_id',
        'titulo_atual_id',
        'titulo_antigo_id',
        'responsavel_id',
        'historico',
    ];
}
