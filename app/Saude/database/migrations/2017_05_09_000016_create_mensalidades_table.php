<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMensalidadesTable extends Migration
{
    /**
     * Run the migrations.
     * @table mensalidades
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mensalidades', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->double('valor');
            $table->timestamp('referencia')->comment('mes de pagamento');
            $table->unsignedInteger('user_id');
            $table->timestamp('dt_pagamento')->nullable();
            $table->integer('status');

            $table->index(['user_id'], 'fk_mensalidades_user1_idx');

            $table->foreign('user_id', 'fk_mensalidades_user1_idx')
                ->references('id')->on('users')
                ->onDelete('no action')
                ->onUpdate('no action');

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
        Schema::dropIfExists('mensalidades');
    }
}
