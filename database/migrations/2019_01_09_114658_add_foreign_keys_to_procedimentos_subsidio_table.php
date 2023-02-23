<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToProcedimentosSubsidioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procedimentos_subsidio', function (Blueprint $table) {
            $table->foreign('procedimentos_id', 'fk_procedimentos_subsidio_procedimentos1_idx')->references('id')->on('procedimentos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('user_id', 'fk_procedimentos_subsidio_user1_idx')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procedimentos_subsidio', function (Blueprint $table) {
            $table->dropForeign('fk_procedimentos_subsidio_procedimentos1_idx');
            $table->dropForeign('fk_procedimentos_subsidio_user1_idx');
        });
    }
}
