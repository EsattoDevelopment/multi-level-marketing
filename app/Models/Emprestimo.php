<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emprestimo extends Model
{
    protected $table = 'emprestimo';

    protected $fillable = [
        'user_id',
        'valor',
        'chave_pix',
        'status',
    ];
}
