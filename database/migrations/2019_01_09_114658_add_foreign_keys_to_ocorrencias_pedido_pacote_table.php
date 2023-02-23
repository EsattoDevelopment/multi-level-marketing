<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToOcorrenciasPedidoPacoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ocorrencias_pedido_pacote', function (Blueprint $table) {
            $table->foreign('pedido_pacote_id')->references('id')->on('pedido_pacote')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ocorrencias_pedido_pacote', function (Blueprint $table) {
            $table->dropForeign('ocorrencias_pedido_pacote_pedido_pacote_id_foreign');
        });
    }
}
