<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToMedicosClinicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medicos_clinicas', function (Blueprint $table) {
            $table->foreign('user_id', 'fk_clinica_has_medicos')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('medico_id', 'fk_medico_has_clinicas')->references('id')->on('medicos')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medicos_clinicas', function (Blueprint $table) {
            $table->dropForeign('fk_clinica_has_medicos');
            $table->dropForeign('fk_medico_has_clinicas');
        });
    }
}
