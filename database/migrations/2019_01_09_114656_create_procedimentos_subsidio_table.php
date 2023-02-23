<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcedimentosSubsidioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procedimentos_subsidio', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->integer('procedimentos_id')->unsigned()->index('fk_procedimentos_subsidio_procedimentos1_idx');
            $table->integer('user_id')->unsigned()->index('fk_procedimentos_subsidio_user1_idx');
            $table->dateTime('inicio_vigencia')->nullable();
            $table->dateTime('fim_vigencia')->nullable();
            $table->integer('status')->nullable();
            $table->softDeletes();
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
        Schema::drop('procedimentos_subsidio');
    }
}
