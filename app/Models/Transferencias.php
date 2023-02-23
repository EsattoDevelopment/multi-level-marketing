<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Transferencias extends Model
{
    use Notifiable, Queueable;

    protected $table = 'transferencias';

    protected $fillable = [
        'valor',
        'responsavel_user_id',
        'user_id',
        'destinatario_user_id',
        'dado_bancario_id',
        'status',
        'descricao',
        'mensagem',
        'operacao_id',
        'dt_solicitacao',
        'dt_efetivacao',
        'valor_taxa',
    ];

    protected $dates = [
        'dt_efetivacao',
        'dt_solicitacao',
        'created_at',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function destinatario()
    {
        return $this->belongsTo(User::class, 'destinatario_user_id')->withTrashed();
    }

    public function conta()
    {
        return $this->belongsTo(DadosBancarios::class, 'dado_bancario_id')->withTrashed();
    }
}
