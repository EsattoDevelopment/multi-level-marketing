<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItensPedidoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itens_pedido', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->decimal('quantidade', 10, 2);
            $table->string('name_item');
            $table->float('valor_unitario', 10, 0);
            $table->float('valor_total', 10, 0);
            $table->integer('item_id')->unsigned()->index('itens_pedido_item_id_foreign');
            $table->integer('pedido_id')->unsigned()->index('itens_pedido_pedido_id_foreign');
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
        Schema::drop('itens_pedido');
    }
}
