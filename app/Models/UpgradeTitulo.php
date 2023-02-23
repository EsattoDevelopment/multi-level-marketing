<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpgradeTitulo extends Model
{
    protected $table = 'upgrade_titulo';

    protected $fillable = ['user_id', 'titulo_id'];

    public function titulo()
    {
        return $this->belongsTo(Titulos::class, 'titulo_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}
