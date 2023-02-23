<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHospedesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospedes', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->integer('hotel_id')->unsigned()->nullable()->index('hospedes_hotel_id_foreign');
            $table->integer('esquerda')->unsigned()->nullable()->index('hospedes_esquerda_foreign');
            $table->integer('direita')->unsigned()->nullable()->index('hospedes_direita_foreign');
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
        Schema::drop('hospedes');
    }
}
