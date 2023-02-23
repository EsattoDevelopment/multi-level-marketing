<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Domains;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Boletos;
use App\Models\MetodoPagamento;
use Illuminate\Database\Eloquent\Model;

/**
     * Class Mensalidade.
     */
    class Mensalidade extends Model
    {
        protected $table = 'mensalidades';

        public $timestamps = false;

        protected $fillable = [
            'valor',
            'valor_pago',
            'referencia',
            'user_id',
            'dt_pagamento',
            'dt_baixa',
            'mes_referencia',
            'ano_referencia',
            'contrato_id',
            'boleto_id',
            'status',
            'codigo_de_barras',
            'proxima',
            'parcela',
            'nosso_numero',
            'numero_documento',
            'metodo_pagamento_id',
            'paga_bonus',
        ];

        protected $guarded = [];

        public function contrato()
        {
            return $this->belongsTo(\App\Models\Contrato::class, 'contrato_id');
        }

        public function nextMensalidade()
        {
            return $this->belongsTo(self::class, 'proxima');
        }

        //Contrato que aguarda o pagamento desta mensalidade
        public function contratoDependente()
        {
            return $this->hasOne(\App\Models\Contrato::class, 'aguarda_mensalidade');
        }

        public function getDtPagamentoAttribute()
        {
            return Carbon::parse($this->attributes['dt_pagamento'])->format('d/m/Y');
        }

        public function setDtPagamentoAttribute($value)
        {
            return $this->attributes['dt_pagamento'] = implode('-', array_reverse(explode('/', $value)));
        }

        public function getDtBaixaAttribute()
        {
            if ($this->attributes['dt_baixa']) {
                return Carbon::parse($this->attributes['dt_baixa'])->format('d/m/Y');
            }

            return 'Nada consta';
        }

        public function setDtBaixaAttribute($value)
        {
            $value = implode('-', array_reverse(explode('/', $value)));

            if (strlen(trim($value)) == 0) {
                $value = null;
            }

            return $this->attributes['dt_baixa'] = $value;
        }

        public function getStatusPivotAttribute()
        {
            return $this->attributes['status'];
        }

        public function getStatusAttribute()
        {
            $status = '';
            switch ($this->attributes['status']) {
                case 1:
                    $status = 'Aguardando';
                    break;
                case 2:
                    $status = 'Proxima';
                    break;
                case 3:
                    $status = 'Atrasada';
                    break;
                case 4:
                    $status = 'Paga';
                    break;
                case 5:
                    $status = 'Cancelada';
                    break;
            }

            return $status;
        }

        public function getStatusCorAttribute()
        {
            $cor = '';
            switch ($this->attributes['status']) {
                case 1:
                    $cor = 'default';
                    break;
                case 2:
                    $cor = 'warning';
                    break;
                case 5:
                case 3:
                    $cor = 'danger';
                    break;
                case 4:
                    $cor = 'success';
                    break;
            }

            return $cor;
        }

        public function usuario()
        {
            $this->belongsTo(User::class, 'user_id')->withTrashed();
        }

        public function user()
        {
            $this->belongsTo(User::class, 'user_id');
        }

        public function metodoPagamento()
        {
            return $this->belongsTo(MetodoPagamento::class, 'medoto_pagamento_id');
        }

        public function boleto()
        {
            return $this->belongsTo(Boletos::class, 'boleto_id');
        }
    }
