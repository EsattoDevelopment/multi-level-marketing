<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToEspecialidadesMedicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('especialidades_medicos', function (Blueprint $table) {
            $table->foreign('especialidades_id', 'fk_especialidades_has_medicos_especialidades1_idx')->references('id')->on('especialidades')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('medicos_id', 'fk_especialidades_has_medicos_medicos1_idx')->references('id')->on('medicos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('especialidades_medicos', function (Blueprint $table) {
            $table->dropForeign('fk_especialidades_has_medicos_especialidades1_idx');
            $table->dropForeign('fk_especialidades_has_medicos_medicos1_idx');
        });
    }
}
