<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMensalidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mensalidades', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->float('valor', 10, 0);
            $table->dateTime('referencia')->default('0000-00-00 00:00:00')->comment('mes de pagamento');
            $table->integer('user_id')->unsigned();
            $table->integer('contrato_id')->unsigned();
            $table->dateTime('dt_pagamento')->nullable();
            $table->integer('mes_referencia')->nullable();
            $table->string('valor_pago', 50)->nullable();
            $table->integer('ano_referencia')->nullable();
            $table->dateTime('dt_baixa')->nullable();
            $table->integer('status');
            $table->string('codigo_de_barras')->nullable();
            $table->string('nosso_numero')->nullable();
            $table->string('numero_documento')->nullable();
            $table->integer('proxima')->unsigned()->nullable()->index('FK_proxima_mensalidade');
            $table->string('parcela', 50)->nullable();
            $table->integer('boleto_id')->unsigned()->nullable()->index('fk_boletos_boleto_id');
            $table->integer('metodo_pagamento_id')->unsigned()->nullable()->default(7)->index('fk_metodo_pagamento_id');
            $table->integer('paga_bonus')->nullable()->default(1);
            $table->index(['contrato_id', 'mes_referencia', 'ano_referencia'], 'fk_contrato_contrato_id');
            $table->index(['user_id', 'contrato_id'], 'fk_mensalidades_user1_contrato_id_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mensalidades');
    }
}
