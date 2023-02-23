<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableExtratoBinario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('extrato_binario', function (Blueprint $table) {
            $table->integer('user_responsavel')->nullable()->unsigned();
            $table->foreign('user_responsavel')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('extrato_binario', function (Blueprint $table) {
            $table->dropForeign('extrato_binario_user_responsavel_foreign');
            $table->dropIndex('extrato_binario_user_responsavel_foreign');
            $table->dropColumn(['user_responsavel']);
        });
    }
}
