<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePedidosMovimentosAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedidos_movimentos', function (Blueprint $table) {
            $table->unsignedInteger('pedido_referencia_id')->nullable();
            $table->foreign('pedido_referencia_id', 'movimento_has_pedido_referencia')->references('id')->on('pedidos')->ondelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pedidos_movimentos', function (Blueprint $table) {
            $table->dropForeign('movimento_has_pedido_referencia');
            $table->dropIndex('movimento_has_pedido_referencia');
            $table->dropColumn(['pedido_referencia_id']);
        });
    }
}
