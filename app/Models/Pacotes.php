<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pacotes extends Model
{
    protected $table = 'pacotes';

    protected $fillable = [
        'chamada',
        'video',
        'descricao',
        'promocao',
        'valor_milhas',
        'status',
        'quantidade_vagas',
        'cidade_id',
        'tipo_pacote_id',
        'galeria_id',
        'internacional',
        'local_selecionavel',
        'dias',
    ];

    public function cidade()
    {
        return $this->belongsTo(Cidade::class);
    }

    public function acomodacao()
    {
        return $this->belongsToMany(TipoAcomodacao::class)->withPivot('valor');
    }

    public function galeria()
    {
        return $this->belongsTo(Galeria::class);
    }

    public function tipoPacote()
    {
        return $this->belongsTo(TipoPacote::class, 'tipo_pacote_id');
    }
}
