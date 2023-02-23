<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToContratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratos', function (Blueprint $table) {
            $table->foreign('aguarda_mensalidade', 'fk_aguarda_mensalidade')->references('id')->on('mensalidades')->onUpdate('SET NULL')->onDelete('SET NULL');
            $table->foreign('pedido_id', 'fk_contrato_pedido')->references('id')->on('pedidos')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('item_id', 'fk_contratos_itens_id')->references('id')->on('itens')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('user_id', 'fk_contratos_usuarios1_idx')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratos', function (Blueprint $table) {
            $table->dropForeign('fk_aguarda_mensalidade');
            $table->dropForeign('fk_contrato_pedido');
            $table->dropForeign('fk_contratos_itens_id');
            $table->dropForeign('fk_contratos_usuarios1_idx');
        });
    }
}
