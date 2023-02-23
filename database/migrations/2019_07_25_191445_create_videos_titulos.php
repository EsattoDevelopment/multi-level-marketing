<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTitulos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos_titulos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('video_id')->unsigned()->nullable();
            $table->integer('titulo_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['id', 'video_id', 'titulo_id']);
            $table->index(['id', 'video_id']);
            $table->index(['id', 'titulo_id']);

            $table->foreign('video_id')
                ->references('id')
                ->on('videos')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->foreign('titulo_id')
                ->references('id')
                ->on('titulos')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('videos_titulos');
    }
}
