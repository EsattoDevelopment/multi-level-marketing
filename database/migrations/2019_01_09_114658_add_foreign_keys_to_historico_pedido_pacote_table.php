<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToHistoricoPedidoPacoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historico_pedido_pacote', function (Blueprint $table) {
            $table->foreign('pacote_id')->references('id')->on('pacotes')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('pedido_pacote_id')->references('id')->on('pedido_pacote')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('status_id')->references('id')->on('status_pedido_pacote')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('historico_pedido_pacote', function (Blueprint $table) {
            $table->dropForeign('historico_pedido_pacote_pacote_id_foreign');
            $table->dropForeign('historico_pedido_pacote_pedido_pacote_id_foreign');
            $table->dropForeign('historico_pedido_pacote_status_id_foreign');
            $table->dropForeign('historico_pedido_pacote_user_id_foreign');
        });
    }
}
