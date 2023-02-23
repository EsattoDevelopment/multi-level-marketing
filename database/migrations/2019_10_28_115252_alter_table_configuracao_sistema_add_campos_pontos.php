<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableConfiguracaoSistemaAddCamposPontos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuracao_sistema', function (Blueprint $table) {
            $table->tinyInteger('pontos_pessoais_calculo_exibicao')->default(0);
            $table->tinyInteger('pontos_equipe_calculo_exibicao')->default(0);
            $table->tinyInteger('extrato_capitalizacao_exibicao')->default(0);
            $table->tinyInteger('extrato_bonus_equipe_exibicao')->default(0);
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
                'pontos_pessoais_calculo_exibicao',
                'pontos_equipe_calculo_exibicao',
                'extrato_capitalizacao_exibicao',
                'extrato_bonus_equipe_exibicao',
            ]);
        });
    }
}
