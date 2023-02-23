<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToParceiroProcedimentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parceiro_procedimentos', function (Blueprint $table) {
            $table->foreign('procedimentos_id', 'fk_parceiro_has_procedimentos1_idx')->references('id')->on('procedimentos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('parceiro_id', 'fk_parceiro_has_procedimentos_parceiro1_idx')->references('id')->on('parceiro')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parceiro_procedimentos', function (Blueprint $table) {
            $table->dropForeign('fk_parceiro_has_procedimentos1_idx');
            $table->dropForeign('fk_parceiro_has_procedimentos_parceiro1_idx');
        });
    }
}
