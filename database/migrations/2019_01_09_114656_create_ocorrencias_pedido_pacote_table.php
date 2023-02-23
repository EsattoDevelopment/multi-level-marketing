<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOcorrenciasPedidoPacoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ocorrencias_pedido_pacote', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->text('descricao', 65535);
            $table->integer('pedido_pacote_id')->unsigned()->nullable()->index('ocorrencias_pedido_pacote_pedido_pacote_id_foreign');
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
        Schema::drop('ocorrencias_pedido_pacote');
    }
}
