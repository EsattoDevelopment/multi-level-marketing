<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCarreiraForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carreira', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('operacao_id')->references('id')->on('operacoes');
            $table->foreign('pedido_id')->references('id')->on('pedidos');
            $table->foreign('mensalidade_id')->references('id')->on('mensalidades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carreira', function (Blueprint $table) {
            $table->dropForeign('carreira_user_id_foreign');
            $table->dropForeign('carreira_operacao_id_foreign');
            $table->dropForeign('carreira_pedido_id_foreign');
            $table->dropForeign('carreira_mensalidade_id_foreign');
        });
    }
}
