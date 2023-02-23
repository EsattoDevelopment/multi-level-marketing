<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableConfiguracaoSistemaAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuracao_sistema', function (Blueprint $table) {
            $table->boolean('campo_cpf')->default(true);
            $table->boolean('campo_rg')->default(true);
            $table->boolean('campo_dtnasc')->default(true);
            $table->boolean('endereco')->default(true);
            $table->boolean('endereco_obrigatorio')->default(true);
            $table->boolean('dados_bancarios')->default(true);
            $table->boolean('dados_bancarios_obrigatorio')->default(true);
            $table->boolean('rendimento_titulo')->default(true);
            $table->boolean('rendimento_item')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configuracao_sistema', function (Blueprint $table) {
            $table->dropColumn([
                'campo_cpf',
                'campo_rg',
                'campo_dtnasc',
                'endereco',
                'endereco_obrigatorio',
                'dados_bancarios',
                'dados_bancarios_obrigatorio',
                'rendimento_titulo',
                'rendimento_item',
            ]);
        });
    }
}
