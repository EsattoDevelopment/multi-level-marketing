<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableTitulosAddConfiguracaoBonus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('titulos', function (Blueprint $table) {
            $table->unsignedInteger('configuracao_bonus_adesao_id')->nullable();
            $table->unsignedInteger('configuracao_bonus_rentabilidade_id')->nullable();
            $table->foreign('configuracao_bonus_adesao_id', 'titulo_configuracao_bonus_adesao_has_configuracao_bonus')
                ->references('id')->on('configuracao_bonus')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('configuracao_bonus_rentabilidade_id', 'titulo_configuracao_bonus_rentabilidade_has_configuracao_bonus')
                ->references('id')->on('configuracao_bonus')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('titulos', function (Blueprint $table) {
            $table->dropForeign('titulo_configuracao_bonus_adesao_has_configuracao_bonus');
            $table->dropForeign('titulo_configuracao_bonus_rentabilidade_has_configuracao_bonus');
            $table->dropIndex('titulo_configuracao_bonus_adesao_has_configuracao_bonus');
            $table->dropIndex('titulo_configuracao_bonus_rentabilidade_has_configuracao_bonus');
            $table->dropColumn(['configuracao_bonus_adesao_id', 'configuracao_bonus_rentabilidade_id']);
        });
    }
}
