<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidosRecontratados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos_recontratados', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pedido_id_finalizado');
            $table->unsignedInteger('pedido_id_recontratado');
            $table->tinyInteger('modo_recontratacao_automatica');
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->foreign('pedido_id_finalizado', 'pedido_id_finalizado_pedidos_recontratados_has_pedidos')->references('id')->on('pedidos');
            $table->foreign('pedido_id_recontratado', 'pedido_id_recontratado_pedidos_recontratados_has_pedidos')->references('id')->on('pedidos');
            $table->foreign('item_id', 'item_id_pedidos_recontratados_has_pedidos')->references('id')->on('itens');
            $table->foreign('user_id', 'user_id_pedidos_recontratados_has_pedidos')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pedidos_recontratados');
    }
}
