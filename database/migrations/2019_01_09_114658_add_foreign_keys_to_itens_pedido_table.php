<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToItensPedidoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('itens_pedido', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('itens')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('pedido_id')->references('id')->on('pedidos')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('itens_pedido', function (Blueprint $table) {
            $table->dropForeign('itens_pedido_item_id_foreign');
            $table->dropForeign('itens_pedido_pedido_id_foreign');
        });
    }
}
