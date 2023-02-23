<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableBancoAddStatusERecebeTed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contas_empresa', function (Blueprint $table) {
            $table->tinyInteger('status')->default('0');
            $table->tinyInteger('recebe_ted')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contas_empresa', function (Blueprint $table) {
            $table->dropColumn(['status', 'recebe_ted']);
        });
    }
}
