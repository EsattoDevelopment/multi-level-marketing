<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToPacotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pacotes', function (Blueprint $table) {
            $table->foreign('galeria_id')->references('id')->on('galerias')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('tipo_pacote_id')->references('id')->on('tipo_pacote')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pacotes', function (Blueprint $table) {
            $table->dropForeign('pacotes_galeria_id_foreign');
            $table->dropForeign('pacotes_tipo_pacote_id_foreign');
        });
    }
}
