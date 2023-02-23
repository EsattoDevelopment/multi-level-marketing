<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentosRecusados extends Model
{
    protected $table = 'documentos_recusados';

    protected $fillable = [
        'documento',
        'motivo_recusa',
        'path_documento',
        'user_id',
        'responsavel_id',
        'banco_id',
    ];
}
