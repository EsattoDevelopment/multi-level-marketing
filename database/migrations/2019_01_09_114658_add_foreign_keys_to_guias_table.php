<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToGuiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guias', function (Blueprint $table) {
            $table->foreign('guia_referencia', 'fk_guia_has_guia')->references('id')->on('guias')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('autorizado_por', 'fk_guia_has_user_autoriza')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('clinica_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('confirmado_por')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('dependente_id')->references('id')->on('dependentes')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('medico_id')->references('id')->on('medicos')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('plano_id')->references('id')->on('itens')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guias', function (Blueprint $table) {
            $table->dropForeign('fk_guia_has_guia');
            $table->dropForeign('fk_guia_has_user_autoriza');
            $table->dropForeign('guias_clinica_id_foreign');
            $table->dropForeign('guias_confirmado_por_foreign');
            $table->dropForeign('guias_dependente_id_foreign');
            $table->dropForeign('guias_medico_id_foreign');
            $table->dropForeign('guias_plano_id_foreign');
            $table->dropForeign('guias_user_id_foreign');
        });
    }
}
