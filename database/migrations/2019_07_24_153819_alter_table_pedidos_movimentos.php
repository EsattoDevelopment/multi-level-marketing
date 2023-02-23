<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePedidosMovimentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedidos_movimentos', function (Blueprint $table) {
            $table->unsignedInteger('rentabilidade_id')->nullable();
            $table->foreign('rentabilidade_id', 'movimento_has_rentabilidade')->on('rentabilidades')->references('id')->ondelete('cascade');
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
            $table->dropForeign('movimento_has_rentabilidade');
            $table->dropIndex('movimento_has_rentabilidade');
            $table->dropColumn(['rentabilidade_id']);
        });
    }
}
