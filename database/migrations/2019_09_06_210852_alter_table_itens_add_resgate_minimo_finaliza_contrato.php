<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableItensAddResgateMinimoFinalizaContrato extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('itens', function (Blueprint $table) {
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
        Schema::table('itens', function (Blueprint $table) {
            $table->dropColumn([
                'resgate_minimo_automatico',
                'finaliza_contrato_automatico',
                'dias_carencia_saque',
            ]);
        });
    }
}
