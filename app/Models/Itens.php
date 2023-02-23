<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use App\Saude\Domains\Exame;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Itens extends Model
{
    use SoftDeletes;

    protected $table = 'itens';

    protected $fillable = [
        'name',
        'chamada',
        'descricao',
        'descricao2',
        'valor',
        'pontos_binarios',
        'imagem',
        'tipo_pedido_id',
        'item_id',
        'avanca_titulo',
        'ativo',
        'user_id',
        'ordem_exibicao', //TODO fazer uso desse campo posteriormente

        'teto_binario_dia',
        //'teto_ganho_geral_percentual',
        'teto_ganho_geral',

        'bonus_indicador_percentual',
        'bonus_indicador',

        'bonus_equiparacao_percentual',
        'bonus_equiparacao',

        'cor_item', // cor mostrada na compra do pacote

        //Galaxy
        'milhas',
        'libera_hotel',
        'validade_milhas',
        'bonus_milhas_indicador',
        'milhas_binaria',
        'milhas_binaria_validade',
        'milhas_binaria_max_altura',

        //Saude
        'qtd_parcelas',
        'vl_parcelas',
        'temp_contrato',
        'tipo_pacote',
        'valor_consulta',
        'valor_fisioterapia',
        'descricao_impressao',

        //
        'ativo_qtd',
        'qtd_min',
        'qtd_max',
        'faixa_deposito_min',
        'faixa_deposito_max',
        'potencial_mensal_teto',
        'carencia_minima',
        'contrato',
        'resgate_minimo',
        'taxa_resgate',

        'quitar_com_bonus',
        'meses',
        'pontos_pessoais',
        'pontos_equipe',

        'resgate_minimo_automatico',
        'finaliza_contrato_automatico',
        'dias_carencia_transferencia',
        'dias_carencia_saque',
        'habilita_recontratacao_automatica',
        'modo_recontratacao_automatica',
        'configuracao_bonus_adesao_id',
        'configuracao_bonus_rentabilidade_id',
        'pagar_bonus',
        'pagar_bonus_titulo',
    ];

    public function tipoPedidos()
    {
        return $this->belongsTo(TipoPedidos::class, 'tipo_pedido_id');
    }

    public function itensPedidos()
    {
        return $this->hasMany(ItensPedido::class, 'item_id');
    }

    public function titulo()
    {
        return $this->belongsTo(Titulos::class, 'avanca_titulo');
    }

    public function configuracaoBonusAdesao()
    {
        return $this->belongsTo(ConfiguracaoBonus::class, 'configuracao_bonus_adesao_id');
    }

    public function configuracaoBonusRentabilidade()
    {
        return $this->belongsTo(ConfiguracaoBonus::class, 'onfiguracao_bonus_rentabilidade_id');
    }

    public function empresa()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function exames()
    {
        return $this->belongsToMany(Exame::class, 'itens_exames', 'item_id');
    }

    public function movimentos()
    {
        return $this->hasMany(Movimentos::class);
    }

    public function pedidos()
    {
        return $this->belongsToMany(Pedidos::class, 'itens_pedido', 'item_id', 'pedido_id');
    }
}
