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
 * Class DadosBancarios.
 */
class DadosBancarios extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'dados_bancarios';

    /**
     * @var array
     */
    protected $fillable = [
        'banco',
        'agencia',
        'agencia_digito',
        'conta',
        'conta_digito',
        'user_id',
        'user_id_editor',
        'tipo_conta',
        'receber_bonus',
        'banco_id',
        'status_comprovante',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dadosEdit()
    {
        return $this->hasMany(DadosBancariosEdit::class, 'dados_bancarios_id');
    }

    /**
     * @return string
     */
    public function getTipoContaStringAttribute()
    {
        if ($this->attributes['tipo_conta'] == 1) {
            return 'Conta corrente';
        } else {
            return 'Poupança';
        }
    }

    public function getDadosAttribute()
    {
        $dados = "<b>Banco</b>: {$this->bancoReferencia->nome} - {$this->bancoReferencia->codigo} <br>";
        $dados .= "<b>Agencia</b>: {$this->attributes['agencia']}-{$this->attributes['agencia_digito']}<br>";
        $dados .= "<b>Conta</b>: {$this->attributes['conta']}-{$this->attributes['conta_digito']}<br>";
        $dados .= $this->attributes['tipo_conta'] == 1 ? 'Conta corrente' : 'Poupança';

        return $dados;
    }

    public function getDadosMinAttribute()
    {
        $dados = "<b>Banco</b>: {$this->bancoReferencia->nome} ";
        $dados .= "{$this->attributes['agencia']}-{$this->attributes['agencia_digito']}";
        $dados .= "/{$this->attributes['conta']}-{$this->attributes['conta_digito']}";

        return $dados;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return mixed
     */
    public function bancoReferencia()
    {
        return $this->belongsTo(Bancos::class, 'banco_id');
    }
}
