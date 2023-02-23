<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableItensAddRenovacaoAutomatica extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('itens', function (Blueprint $table) {
            $table->integer('dias_carencia_transferencia')->after('finaliza_contrato_automatico')->default(0);
            $table->boolean('habilita_recontratacao_automatica')->default(false);
            $table->tinyInteger('modo_recontratacao_automatica')->default(0);
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
                    'dias_carencia_transferencia',
                    'habilita_recontratacao_automatica',
                    'modo_recontratacao_automatica',
                ]
            );
        });
    }
}
