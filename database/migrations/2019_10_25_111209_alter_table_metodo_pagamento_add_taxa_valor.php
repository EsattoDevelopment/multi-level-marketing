<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableMetodoPagamentoAddTaxaValor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('metodo_pagamento', function (Blueprint $table) {
            $table->string('taxa_descricao');
            $table->decimal('taxa_valor', 8,2)->default(0);
            $table->decimal('taxa_porcentagem', 5,2)->default(0);
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
            $table->dropColumn(['taxa_descricao', 'taxa_valor', 'taxa_porcentagem']);
        });
    }
}
