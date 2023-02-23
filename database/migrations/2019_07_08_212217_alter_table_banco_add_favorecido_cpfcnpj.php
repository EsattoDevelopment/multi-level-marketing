<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableBancoAddFavorecidoCpfcnpj extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contas_empresa', function (Blueprint $table) {
            $table->string('favorecido', 255)->nullable();
            $table->string('cpfcnpj', 25)->nullable();
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
            $table->dropColumn(['favorecido', 'cpfcnpj']);
        });
    }
}
