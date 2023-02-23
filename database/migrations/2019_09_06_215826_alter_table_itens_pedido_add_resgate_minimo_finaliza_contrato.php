<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableItensPedidoAddResgateMinimoFinalizaContrato extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('itens_pedido', function (Blueprint $table) {
            $table->boolean('quitar_com_bonus')->default(false);
            $table->decimal('potencial_mensal_teto', 10, 2)->default(-1);
            $table->decimal('resgate_minimo', 10, 2)->default(-1);
            $table->integer('total_dias_contrato')->default(-1);
            $table->integer('total_meses_contrato')->default(-1);

            $table->boolean('resgate_minimo_automatico')->default(true);
            $table->boolean('finaliza_contrato_automatico')->default(true);
            $table->integer('dias_carencia_saque')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('itens_pedido', function (Blueprint $table) {
            $table->dropColumn([
                'quitar_com_bonus',
                'potencial_mensal_teto',
                'resgate_minimo',
                'total_dias_contrato',
                'total_meses_contrato',

                'resgate_minimo_automatico',
                'finaliza_contrato_automatico',
                'dias_carencia_saque',
            ]);
        });
    }
}
