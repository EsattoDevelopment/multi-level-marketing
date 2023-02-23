<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableMovimentoAddItemTitulo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movimentos', function (Blueprint $table) {
            $table->unsignedInteger('item_id')->nullable();
            $table->unsignedInteger('titulo_id')->nullable();

            $table->foreign('item_id', 'item_id_foreign')->references('id')->on('itens')->onDelete('cascade');
            $table->foreign('titulo_id', 'titulo_id_foreign')->references('id')->on('titulos')->onDelete('cascade');
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
            $table->dropForeign('item_id_foreign');
            $table->dropIndex('item_id_foreign');

            $table->dropForeign('titulo_id_foreign');
            $table->dropIndex('titulo_id_foreign');

            $table->dropColumn(['titulo_id', 'item_id']);
        });
    }
}
