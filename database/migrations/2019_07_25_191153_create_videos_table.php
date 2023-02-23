<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 100)->nullable();
            $table->string('descricao', 800)->nullable();
            $table->string('codigo', 100)->nullable();
            $table->string('capa', 500)->nullable();
            $table->integer('tipo')->nullable();
            $table->integer('categoria')->nullable();
            $table->integer('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['id', 'tipo']);
            $table->index(['id', 'status']);
            $table->index(['id', 'nome', 'tipo', 'categoria']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('videos');
    }
}
