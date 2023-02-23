<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Carbon\Carbon;
use App\Saude\Domains\Mensalidade;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    protected $table = 'contratos';

    protected $dates = ['dt_cancelamento'];

    protected $fillable = [
        'dt_inicio',
        'dt_fim',
        'dt_parcela',
        'item_id',
        'user_id',
        'status',
        'aguarda_mensalidade',
        'pedido_id',
        'qtd_mensalidades',
        'vl_mensalidades',
        'temp_contrato',
        'dt_cancelamento',
    ];

    public function getDtInicioAttribute()
    {
        return Carbon::parse($this->attributes['dt_inicio'])->format('d/m/Y');
    }

    public function getDtInicioImpressaoAttribute()
    {
        $datas = explode('-', explode(' ', $this->attributes['dt_inicio'])[0]);

        return $datas[2].' de '.config('constants.meses')[$datas[1]].' de '.$datas[0];
    }

    public function setDtInicioAttribute($value)
    {
        $this->attributes['dt_inicio'] = implode('-', array_reverse(explode('/', $value)));
    }

    public function getDtFimAttribute()
    {
        return Carbon::parse($this->attributes['dt_fim'])->format('d/m/Y');
    }

    public function setDtFimAttribute($value)
    {
        $this->attributes['dt_fim'] = implode('-', array_reverse(explode('/', $value)));
    }

    public function setDtParcelaAttribute($value)
    {
        $this->attributes['dt_parcela'] = implode('-', array_reverse(explode('/', $value)));
    }

    public function getDtParcelaAttribute()
    {
        return Carbon::parse($this->attributes['dt_parcela'])->format('d/m/Y');
    }

    public function mensalidades()
    {
        return $this->hasMany(Mensalidade::class, 'contrato_id')->orderBy('dt_pagamento');
    }

    public function mensalidade_aguardando()
    {
        return $this->belongsTo(Mensalidade::class, 'aguarda_mensalidade');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function item()
    {
        return $this->belongsTo(Itens::class, 'item_id');
    }

    public function pedido()
    {
        return $this->belongsTo(Pedidos::class, 'pedido_id');
    }
}
