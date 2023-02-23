<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemessasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remessas', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->integer('id')->unsigned()->primary();
            $table->integer('numero')->unsigned();
            $table->string('arquivo')->nullable();
            $table->timestamps();
            $table->integer('efetivado')->default(0);
            $table->dateTime('dt_efetivado')->default('0000-00-00 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('remessas');
    }
}
