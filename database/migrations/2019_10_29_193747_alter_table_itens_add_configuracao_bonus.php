<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableItensAddConfiguracaoBonus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('itens', function (Blueprint $table) {
            $table->unsignedInteger('configuracao_bonus_adesao_id')->nullable();
            $table->unsignedInteger('configuracao_bonus_rentabilidade_id')->nullable();
            $table->foreign('configuracao_bonus_adesao_id', 'itens_configuracao_bonus_adesao_has_configuracao_bonus')
                ->references('id')->on('configuracao_bonus')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('configuracao_bonus_rentabilidade_id', 'itens_configuracao_bonus_rentabilidade_has_configuracao_bonus')
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
        Schema::table('itens', function (Blueprint $table) {
            $table->dropForeign('itens_configuracao_bonus_adesao_has_configuracao_bonus');
            $table->dropForeign('itens_configuracao_bonus_rentabilidade_has_configuracao_bonus');
            $table->dropIndex('itens_configuracao_bonus_adesao_has_configuracao_bonus');
            $table->dropIndex('itens_configuracao_bonus_rentabilidade_has_configuracao_bonus');
            $table->dropColumn(['configuracao_bonus_adesao_id', 'configuracao_bonus_rentabilidade_id']);
        });
    }
}
