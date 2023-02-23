<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDadosPagamentoAddDadosBoleto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dados_pagamento', function (Blueprint $table) {
            $table->string('invoice_id');
            $table->json('dados_boleto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dados_pagamento', function (Blueprint $table) {
            $table->dropColumn(['invoice_id', 'dados_boleto']);
        });
    }
}
