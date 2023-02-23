<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParceiroMedicosTable extends Migration
{
    /**
     * Run the migrations.
     * @table parceiro_medicos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parceiro_medicos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('parceiro_id');
            $table->unsignedInteger('medicos_id');

            $table->index(['medicos_id'], 'fk_parceiro_has_medicos_medicos1_idx');

            $table->index(['parceiro_id'], 'fk_parceiro_has_medicos_parceiro1_idx');

            $table->foreign('parceiro_id', 'fk_parceiro_has_medicos_parceiro1_idx')
                ->references('id')->on('parceiro')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('medicos_id', 'fk_parceiro_has_medicos_medicos1_idx')
                ->references('id')->on('medicos')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->timestamps();
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
        Schema::dropIfExists('parceiro_medicos');
    }
}
