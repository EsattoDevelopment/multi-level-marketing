<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuiaHasProcedimentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guia_has_procedimentos', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->integer('guia_id')->unsigned()->index('guia_has_procedimentos_guia_id_foreign');
            $table->integer('procedimento_id')->unsigned()->index('guia_has_procedimentos_procedimento_id_foreign');
            $table->decimal('valor', 10, 0);
        });

        Schema::table('guia_has_procedimentos', function (Blueprint $table) {
            $table->foreign('procedimento_id')->references('id')->on('procedimentos')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('guia_id')->references('id')->on('guias')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guia_has_procedimentos', function (Blueprint $table) {
            $table->dropForeign('guia_has_procedimentos_guia_id_foreign');
            $table->dropForeign('guia_has_procedimentos_procedimento_id_foreign');
        });

        Schema::drop('guia_has_procedimentos');
    }
}
