<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicosClinicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicos_clinicas', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->integer('medico_id')->unsigned()->index('fk_medico_has_clinicas');
            $table->integer('user_id')->unsigned()->index('fk_clinica_has_medicos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('medicos_clinicas');
    }
}
