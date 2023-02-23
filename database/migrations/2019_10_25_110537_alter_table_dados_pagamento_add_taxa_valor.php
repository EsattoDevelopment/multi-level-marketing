<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDadosPagamentoAddTaxaValor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dados_pagamento', function (Blueprint $table) {
            $table->decimal('taxa_valor', 8,2)->default(0);
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
            $table->dropColumn(['taxa_valor']);
        });
    }
}
