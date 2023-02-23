<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterExtratoBinarioAddPedidoId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('extrato_binario', function (Blueprint $table) {
            $table->unsignedInteger('pedido_id')->nullable();
            $table->foreign('pedido_id', 'extrato_has_pedido')->references('id')->on('pedidos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('extrato_binario', function (Blueprint $table) {
            $table->dropForeign('extrato_has_pedido');
            $table->dropColumn(['pedido_id']);
        });
    }
}
