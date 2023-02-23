<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableConfiguracaoSistemaAddCamposRecontratacaoAutenticacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuracao_sistema', function (Blueprint $table) {
            $table->boolean('habilita_autenticacao_contratacao')->default(false);
            $table->boolean('habilita_autenticacao_recontratacao')->default(false);
            $table->boolean('habilita_autenticacao_transferencias')->default(true);
            $table->string('alertas_recontratacao_range_dias')->default('');
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
                    'habilita_autenticacao_contratacao',
                    'habilita_autenticacao_recontratacao',
                    'habilita_autenticacao_transferencias',
                    'alertas_recontratacao_range_dias',
                ]
            );
        });
    }
}
