<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidoPacoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_pacote', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->integer('valor_milhas_dia_compra');
            $table->string('voucher')->unique();
            $table->string('codigo_reserva')->nullable();
            $table->string('codigo_voo')->nullable();
            $table->dateTime('data_ida')->default('0000-00-00 00:00:00');
            $table->dateTime('data_volta')->default('0000-00-00 00:00:00');
            $table->float('acomodacao_valor', 10, 0);
            $table->integer('cidade_id')->nullable();
            $table->integer('status_id')->unsigned()->nullable()->index('pedido_pacote_status_id_foreign');
            $table->integer('tipo_acomodacao_id')->unsigned()->nullable()->index('pedido_pacote_tipo_acomodacao_id_foreign');
            $table->integer('pacote_id')->unsigned()->nullable()->index('pedido_pacote_pacote_id_foreign');
            $table->integer('user_id')->unsigned()->nullable()->index('pedido_pacote_user_id_foreign');
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
        Schema::drop('pedido_pacote');
    }
}
