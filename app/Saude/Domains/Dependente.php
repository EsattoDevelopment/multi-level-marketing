<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Domains;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Dependente.
 */
class Dependente extends Model
{
    use SoftDeletes;

    protected $table = 'dependentes';

    protected $fillable = [
        'name',
        'sexo',
        'dt_nasc',
        'status',
        'parentesco',
        'rg',
        'cpf',
        'titular_id',
    ];

    protected $guarded = [];

    public function setDtNascAttribute($value)
    {
        $data = explode('/', $value);
        $this->attributes['dt_nasc'] = Carbon::create($data[2], $data[1], $data[0], 0, 0, 0);
    }

    public function getDtNascAttribute($value)
    {
        return Carbon::parse($this->attributes['dt_nasc'])->format('d/m/Y');
    }

    public function titular()
    {
        return $this->belongsTo(User::class, 'titular_id');
    }

    public function getParentescoAttribute($value)
    {
        switch ($value) {
            case 1:
                $parentesco = 'Conjugê';
                    break;
            case 2:
                $parentesco = 'Filhos';
                break;
        }

        return $parentesco;
    }
}
