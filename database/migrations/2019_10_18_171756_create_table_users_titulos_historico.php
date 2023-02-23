<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUsersTitulosHistorico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_titulos_historico', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('titulo_atual_id');
            $table->integer('titulo_antigo_id');
            $table->integer('responsavel_id');
            $table->text('historico');
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
        Schema::drop('users_titulos_historico');
    }
}
