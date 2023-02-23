<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUsersFaixasCep extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_faixas_cep', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('inicio');
            $table->string('fim');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::table('users_faixas_cep', function (Blueprint $table) {
            $table->dropForeign('users_faixas_cep_user_id_foreign');
        });

        Schema::drop('users_faixas_cep');
    }
}
