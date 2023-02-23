<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcedimentoClinicaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procedimento_clinica', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->integer('procedimento_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('name')->nullable();
            $table->decimal('valor', 10, 2)->nullable();
            $table->primary(['procedimento_id', 'user_id']);
            $table->foreign('procedimento_id')->references('id')->on('procedimentos')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procedimento_clinica', function (Blueprint $table) {
            $table->dropForeign('procedimento_clinica_procedimento_id_foreign');
            $table->dropForeign('procedimento_clinica_user_id_foreign');
        });

        Schema::drop('procedimento_clinica');
    }
}
