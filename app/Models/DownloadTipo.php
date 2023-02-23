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
 * Class DownloadTipo.
 */
class DownloadTipo extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'download_tipo';

    /**
     * @var array
     */
    protected $fillable = ['titulo', 'descricao', 'habilita_rede'];

    public function downloads()
    {
        $this->hasMany(Download::class, 'download_tipo_id');
    }
}
