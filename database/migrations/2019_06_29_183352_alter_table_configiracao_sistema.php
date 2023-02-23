<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableConfigiracaoSistema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuracao_sistema', function (Blueprint $table) {
            $table->tinyInteger('sistema_viagens')->default(0);
            $table->tinyInteger('sistema_saude')->default(0);
            $table->tinyInteger('paga_bonus_diario_titulo')->default(0);
            $table->tinyInteger('paga_bonus_diario_item')->default(0);
            $table->tinyInteger('matriz_unilevel')->default(0);
            $table->tinyInteger('matriz_fechada')->default(0);
            $table->integer('matriz_fechada_tamanho')->default(0);
            $table->integer('profundidade_pagamento_matriz')->default(0);
            $table->tinyInteger('item_direcionado')->default(0);
            $table->tinyInteger('update_titulo')->default(0);
            $table->tinyInteger('update_titulo_automatico')->default(0);
            $table->char('moeda', 3)->default('R$');
            $table->tinyInteger('rede_binaria')->default(0);
            $table->double('valor_ponto_binario')->default(0.00);
            $table->tinyInteger('bonificacao_diaria')->default(0);
            $table->tinyInteger('bonificacao_diaria_recorrente')->default(0);
            $table->tinyInteger('tipo_teto_pagamento')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configuracao_sistema', function (Blueprint $table) {
            $table->dropColumn([
                'sistema_viagens',
                'sistema_saude',
                'paga_bonus_diario_titulo',
                'paga_bonus_diario_item',
                'matriz_unilevel',
                'matriz_fechada',
                'matriz_fechada_tamanho',
                'profundidade_pagamento_matriz',
                'item_direcionado',
                'update_titulo',
                'update_titulo_automatico',
                'moeda',
                'rede_binaria',
                'valor_ponto_binario',
                'bonificacao_diaria',
                'bonificacao_diaria_recorrente',
                'tipo_teto_pagamento',
            ]);
        });
    }
}
