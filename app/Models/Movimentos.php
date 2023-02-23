<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Movimentos extends Model
{
    protected $table = 'movimentos';

    protected $fillable = [
        'valor_manipulado',
        'saldo_anterior',
        'saldo',
        'referencia',
        'documento',
        'descricao',
        'responsavel_user_id',
        'user_id',
        'pedido_id',
        'mensalidade_id',
        'operacao_id',

        'item_id',
        'titulo_id',
        'status',
        'valor_excedente',
        'transferencia_id',
    ];

    protected $casts = [
        'valor_manipulado' => 'double',
        'saldo_anterior' => 'double',
        'saldo' => 'double',
        'valor_excedente' => 'double',
    ];

    public function responsavel()
    {
        return $this->belongsTo(User::class, 'responsavel_user_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function operacao()
    {
        return $this->belongsTo(Operacoes::class, 'operacao_id');
    }

    public function pedido()
    {
        return $this->belongsTo(Pedidos::class, 'pedido_id');
    }

    public function item()
    {
        return $this->belongsTo(Itens::class, 'item_id')->withTrashed();
    }

    public function ganhosDoMes($id)
    {
        //TODO resgata ganhos mensais
        return $this->whereUserId($id)->whereIn('operacao_id', [1, 2, 9, 10, 15])->where(DB::raw('MONTH(created_at)'), '=', date('m'))->sum('valor_manipulado');
    }

    public function totalGanhos($id)
    {
        return $this->whereUserId($id)->whereIn('operacao_id', [1, 2, 3, 6, 7, 9, 10, 15, 17, 18, 19, 20, 22, 26, 27])->sum('valor_manipulado');
    }

    public function royaltiesPagos($id)
    {
        return $this->whereUserId($id)->whereIn('operacao_id', [31])->sum('valor_manipulado');
    }

    public function getDataAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d/m/Y');
    }

    public static function ultimoMovimentoUserId($userId)
    {
        $ultimoMovimento = self::whereUserId($userId)->orderBy('id', 'desc')->limit(1)->get();

        $ultimoMovimento = $ultimoMovimento->first() instanceof self ? $ultimoMovimento->first() : null;

        return $ultimoMovimento;
    }
}
