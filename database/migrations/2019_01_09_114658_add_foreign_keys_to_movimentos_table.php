<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToMovimentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movimentos', function (Blueprint $table) {
            $table->foreign('mensalidade_id')->references('id')->on('mensalidades')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('operacao_id')->references('id')->on('operacoes')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('pedido_id')->references('id')->on('pedidos')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('responsavel_user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::table('movimentos', function (Blueprint $table) {
            $table->dropForeign('movimentos_mensalidade_id_foreign');
            $table->dropForeign('movimentos_operacao_id_foreign');
            $table->dropForeign('movimentos_pedido_id_foreign');
            $table->dropForeign('movimentos_responsavel_user_id_foreign');
            $table->dropForeign('movimentos_user_id_foreign');
        });
    }
}
