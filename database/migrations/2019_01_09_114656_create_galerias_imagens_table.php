<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGaleriasImagensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('galerias_imagens', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('caminho')->nullable();
            $table->integer('ordem')->nullable();
            $table->string('legenda')->nullable();
            $table->string('extensao')->nullable();
            $table->char('principal')->nullable();
            $table->integer('galeria_id')->unsigned()->nullable()->index('galerias_imagens_galeria_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('galerias_imagens');
    }
}
