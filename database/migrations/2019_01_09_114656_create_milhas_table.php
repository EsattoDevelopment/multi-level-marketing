<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMilhasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('milhas', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->integer('quantidade');
            $table->integer('referencia');
            $table->string('descricao');
            $table->dateTime('validade')->default('0000-00-00 00:00:00');
            $table->string('utilizada_onde');
            $table->integer('pedido_id')->unsigned()->nullable()->index('milhas_pedido_id_foreign');
            $table->timestamps();
            $table->integer('user_id')->unsigned()->index('milhas_user_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('milhas');
    }
}
