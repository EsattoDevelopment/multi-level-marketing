<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToProdutosProcedimentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('produtos_procedimentos', function (Blueprint $table) {
            $table->foreign('itens_id', 'fk_procedimentos_has_itens_itens1_idx')->references('id')->on('itens')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('procedimentos_id', 'fk_procedimentos_has_produtos_procedimentos1_idx')->references('id')->on('procedimentos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('produtos_procedimentos', function (Blueprint $table) {
            $table->dropForeign('fk_procedimentos_has_itens_itens1_idx');
            $table->dropForeign('fk_procedimentos_has_produtos_procedimentos1_idx');
        });
    }
}
