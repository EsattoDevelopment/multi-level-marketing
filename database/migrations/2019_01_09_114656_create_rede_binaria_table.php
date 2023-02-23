<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedeBinariaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rede_binaria', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable()->index('rede_binaria_user_id_foreign');
            $table->integer('esquerda')->unsigned()->nullable()->index('rede_binaria_esquerda_foreign');
            $table->integer('direita')->unsigned()->nullable()->index('rede_binaria_direita_foreign');
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
        Schema::drop('rede_binaria');
    }
}
