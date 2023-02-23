<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoricoPedidoPacoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historico_pedido_pacote', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->integer('valor_milhas_dia_compra')->nullable();
            $table->string('voucher')->unique()->nullable();
            $table->string('codigo_reserva')->nullable();
            $table->string('codigo_voo')->nullable();
            $table->dateTime('data_viagem')->default('0000-00-00 00:00:00');
            $table->integer('status_id')->unsigned()->nullable()->index('historico_pedido_pacote_status_id_foreign');
            $table->integer('pacote_id')->unsigned()->nullable()->index('historico_pedido_pacote_pacote_id_foreign');
            $table->integer('user_id')->unsigned()->nullable()->index('historico_pedido_pacote_user_id_foreign');
            $table->integer('pedido_pacote_id')->unsigned()->nullable()->index('historico_pedido_pacote_pedido_pacote_id_foreign');
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
        Schema::drop('historico_pedido_pacote');
    }
}
