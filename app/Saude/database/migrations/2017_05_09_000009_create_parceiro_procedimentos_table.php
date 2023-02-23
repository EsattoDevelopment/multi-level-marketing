<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParceiroProcedimentosTable extends Migration
{
    /**
     * Run the migrations.
     * @table parceiro_procedimentos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parceiro_procedimentos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('parceiro_id')->nullable();
            $table->unsignedInteger('procedimentos_id')->nullable();
            $table->double('valor')->nullable();

            $table->index(['procedimentos_id'], 'fk_parceiro_has_procedimentos1_idx');

            $table->index(['parceiro_id'], 'fk_parceiro_has_procedimentos_parceiro1_idx');

            $table->foreign('parceiro_id', 'fk_parceiro_has_procedimentos_parceiro1_idx')
                ->references('id')->on('parceiro')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('procedimentos_id', 'fk_parceiro_has_procedimentos1_idx')
                ->references('id')->on('procedimentos')
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
        Schema::dropIfExists('parceiro_procedimentos');
    }
}
