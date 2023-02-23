<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDadosPagamentoAddIdTransation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dados_pagamento', function (Blueprint $table) {
            $table->text('transaction_id')->nullable();
            $table->text('transaction_confirm_pagto_id')->nullable();
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
            $table->dropColumn(['transaction_id', 'transaction_confirm_pagto_id']);
        });
    }
}
