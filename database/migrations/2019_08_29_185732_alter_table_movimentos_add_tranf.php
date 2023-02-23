<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableMovimentosAddTranf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movimentos', function (Blueprint $table) {
            $table->unsignedInteger('transferencia_id')->nullable();
            $table->foreign('transferencia_id', 'movimento_has_tranferencia')
                ->references('id')
                ->on('transferencias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movimentos', function (Blueprint $table) {
            $table->dropForeign('movimento_has_tranferencia');
            $table->dropIndex('movimento_has_tranferencia');
            $table->dropColumn(['transferencia_id']);
        });
    }
}
