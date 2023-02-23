<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableRentabilidadeHistoricoAddPlataformaContaId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rentabilidade_historico', function (Blueprint $table) {
            $table->unsignedInteger('plataforma_conta_id')->nullable();
            $table->foreign('plataforma_conta_id', 'fk_rent_plataforma_conta_id')
                ->references('id')
                ->on('plataforma_conta')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rentabilidade_historico', function (Blueprint $table) {
            $table->dropForeign('fk_rent_plataforma_conta_id');
            $table->dropIndex('fk_rent_plataforma_conta_id');
            $table->dropColumn(['plataforma_conta_id']);
        });
    }
}
