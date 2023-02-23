<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToPedidoPacoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedido_pacote', function (Blueprint $table) {
            $table->foreign('pacote_id')->references('id')->on('pacotes')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('status_id')->references('id')->on('status_pedido_pacote')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('tipo_acomodacao_id')->references('id')->on('tipo_acomodacao')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::table('pedido_pacote', function (Blueprint $table) {
            $table->dropForeign('pedido_pacote_pacote_id_foreign');
            $table->dropForeign('pedido_pacote_status_id_foreign');
            $table->dropForeign('pedido_pacote_tipo_acomodacao_id_foreign');
            $table->dropForeign('pedido_pacote_user_id_foreign');
        });
    }
}
