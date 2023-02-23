<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableItensAddCamposPlcm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('itens', function (Blueprint $table) {
            $table->boolean('ativo_qtd')->default(false);
            $table->integer('qtd_min')->default(1);
            $table->integer('qtd_max')->default(1);
            $table->decimal('faixa_deposito_min', 10, 2)->nullable();
            $table->decimal('faixa_deposito_max', 10, 2)->nullable();
            $table->decimal('potencial_mensal_teto', 10, 2)->nullable();
            $table->integer('carencia_minima')->nullable();
            $table->integer('contrato')->nullable();
            $table->decimal('resgate_minimo', 10, 2)->nullable();
            $table->decimal('taxa_resgate', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('itens', function ($table) {
            $table->dropColumn([
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
            ]);
        });
    }
}
