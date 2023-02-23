<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PedidoPacote extends Model
{
    protected $table = 'pedido_pacote';

    protected $fillable = [
        'valor_milhas_dia_compra',
        'voucher',
        'codigo_reserva',
        'codigo_voo',
        'data_ida',
        'data_volta',
        'acomodacao_valor',
        'cidade_id',
        'status_id',
        'tipo_acomodacao_id',
        'pacote_id',
        'user_id',
    ];

    public function setDataIdaAttribute($value)
    {
        $this->attributes['data_ida'] = implode('-', array_reverse(explode('/', $value)));
    }

    public function getDataIdaAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function setDataVoltaAttribute($value)
    {
        $this->attributes['data_volta'] = implode('-', array_reverse(explode('/', $value)));
    }

    public function getDataVoltaAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function acomodacao()
    {
        return $this->belongsTo(TipoAcomodacao::class, 'tipo_acomodacao_id');
    }

    public function pacote()
    {
        return $this->belongsTo(Pacotes::class, 'pacote_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function statusPedidoPacote()
    {
        return $this->belongsTo(StatusPedidoPacote::class, 'status_id');
    }

    public function getPodeCancelarAttribute()
    {
        $create = Carbon::parse($this->attributes['created_at']);
        $now = Carbon::now();

        if ($now->diffInDays($create) > 7) {
            return false;
        }

        return true;
    }
}
