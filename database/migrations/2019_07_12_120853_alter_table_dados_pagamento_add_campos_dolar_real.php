<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDadosPagamentoAddCamposDolarReal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dados_pagamento', function (Blueprint $table) {
            $table->decimal('valor_efetivo', 10, 2)->nullable();
            $table->decimal('valor_real', 10, 2)->nullable();
            $table->decimal('valor_efetivo_real', 10, 2)->nullable();
            $table->decimal('cotacao_dolar_dia_compra', 7, 4)->nullable();
            $table->decimal('cotacao_dolar_dia_efetivo', 7, 4)->nullable();
            $table->dateTime('data_pagamento_efetivo')->default('0000-00-00 00:00:00');
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
            $table->dropColumn([
                'valor_efetivo',
                'valor_real',
                'valor_efetivo_real',
                'cotacao_dolar_dia_compra',
                'cotacao_dolar_dia_efetivo',
                'data_pagamento_efetivo',
            ]);
        });
    }
}
