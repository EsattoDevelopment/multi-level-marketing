<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuiaHasExamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guia_has_exames', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->integer('guia_id')->unsigned()->index('guia_has_exames_guia_id_foreign')->nullable();
            $table->integer('exame_id')->unsigned()->index('guia_has_exames_exame_id_foreign')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('guia_has_exames');
    }
}
