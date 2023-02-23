<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEspecialidadesMedicosTable extends Migration
{
    /**
     * Run the migrations.
     * @table especialidades_medicos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('especialidades_medicos', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->unsignedInteger('especialidades_id');
            $table->unsignedInteger('medicos_id');

            $table->index(['especialidades_id'], 'fk_especialidades_has_medicos_especialidades1_idx');

            $table->index(['medicos_id'], 'fk_especialidades_has_medicos_medicos1_idx');

            $table->foreign('especialidades_id', 'fk_especialidades_has_medicos_especialidades1_idx')
                ->references('id')->on('especialidades')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('medicos_id', 'fk_especialidades_has_medicos_medicos1_idx')
                ->references('id')->on('medicos')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('especialidades_medicos');
    }
}
