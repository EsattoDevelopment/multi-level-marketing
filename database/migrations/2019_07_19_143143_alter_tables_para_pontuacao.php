<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablesParaPontuacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pontos_pessoais', function (Blueprint $table) {
            $table->unsignedInteger('mensalidade_id')->nullable();
            $table->foreign('mensalidade_id', 'pontosp_has_mensalidade')->references('id')->on('mensalidades')->ondelete('cascade');
        });

        Schema::table('pontos_equipe_equiparacao', function (Blueprint $table) {
            $table->unsignedInteger('mensalidade_id')->nullable();
            $table->foreign('mensalidade_id', 'pontosee_has_mensalidade')->references('id')->on('mensalidades')->ondelete('cascade');
        });

        Schema::table('pontos_equipe_unilevel', function (Blueprint $table) {
            $table->unsignedInteger('mensalidade_id')->nullable();
            $table->foreign('mensalidade_id', 'pontoseu_has_mensalidade')->references('id')->on('mensalidades')->ondelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pontos_pessoais', function (Blueprint $table) {
            $table->dropForeign('pontosp_has_mensalidade');
            $table->dropIndex('pontosp_has_mensalidade');
            $table->dropColumn(['mensalidade_id']);
        });

        Schema::table('pontos_equipe_equiparacao', function (Blueprint $table) {
            $table->dropForeign('pontosee_has_mensalidade');
            $table->dropIndex('pontosee_has_mensalidade');
            $table->dropColumn(['mensalidade_id']);
        });

        Schema::table('pontos_equipe_unilevel', function (Blueprint $table) {
            $table->dropForeign('pontoseu_has_mensalidade');
            $table->dropIndex('pontoseu_has_mensalidade');
            $table->dropColumn(['mensalidade_id']);
        });
    }
}
