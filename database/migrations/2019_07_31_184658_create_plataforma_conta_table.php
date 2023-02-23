<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlataformaContaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plataforma_conta', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome')->nullable();
            $table->string('descricao')->nullable();
            $table->integer('status')->nullable();
            $table->unsignedInteger('plataforma_id')->nullable();
            $table->timestamps();

            $table->foreign('plataforma_id')
                ->references('id')
                ->on('plataforma');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('plataforma_conta');
    }
}
