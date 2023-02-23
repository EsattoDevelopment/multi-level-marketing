<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableMetodoPagamentoAddConfiguracao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('metodo_pagamento', function (Blueprint $table) {
            $table->json('configuracao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('metodo_pagamento', function (Blueprint $table) {
            $table->dropColumn(['configuracao']);
        });
    }
}
