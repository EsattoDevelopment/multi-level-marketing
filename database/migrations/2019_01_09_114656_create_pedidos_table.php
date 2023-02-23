<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->integer('status');
            $table->dateTime('data_compra')->default('0000-00-00 00:00:00');
            $table->float('valor_total', 10, 0);
            $table->integer('user_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();
            $table->integer('boleto_id')->unsigned()->nullable()->index('pedidos_boleto_id_foreign');
            $table->index(['user_id', 'data_compra'], 'pedidos_user_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pedidos');
    }
}
