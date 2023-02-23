<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Download extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'nomeArquivo', 'extensao', 'descricao', 'download_tipo_id'];

    protected $dates = ['created_at'];

    public function tipo()
    {
        return $this->belongsTo(DownloadTipo::class, 'download_tipo_id');
    }
}
