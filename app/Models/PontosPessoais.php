<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use App\Saude\Domains\Mensalidade;
use Illuminate\Database\Eloquent\Model;

class PontosPessoais extends Model
{
    protected $table = 'pontos_pessoais';

    protected $fillable = [
        'pontos',
        'saldo_anterior',
        'saldo',
        'pedido_id',
        'mensalidade_id',
        'user_id',
        'operacao_id',
    ];

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

    public function mensalidade()
    {
        return $this->belongsTo(Mensalidade::class, 'mensalidade_id');
    }

    public static function ultimoPontosPessoaisUserId($userId)
    {
        $ultimoPontosPessoais = self::whereUserId($userId)->orderBy('id', 'desc')->limit(1)->get();

        $ultimoPontosPessoais = $ultimoPontosPessoais->first() instanceof self ? $ultimoPontosPessoais->first() : null;

        return $ultimoPontosPessoais;
    }
}
