<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToGuiaHasExamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guia_has_exames', function (Blueprint $table) {
            $table->foreign('exame_id')->references('id')->on('exames')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::table('guia_has_exames', function (Blueprint $table) {
            $table->dropForeign('guia_has_exames_exame_id_foreign');
            $table->dropForeign('guia_has_exames_guia_id_foreign');
        });
    }
}
