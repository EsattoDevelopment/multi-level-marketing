<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRentabilidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rentabilidades', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('item_id')->unsigned()->nullable();
            $table->integer('titulo_id')->unsigned()->nullable();
            $table->decimal('valor_fixo', 10, 2)->nullable();
            $table->decimal('percentual', 10, 2)->nullable();
            $table->boolean('pago')->nullable();
            $table->date('data')->default('0000-00-00 00:00:00');
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
        Schema::drop('rentabilidades');
    }
}
