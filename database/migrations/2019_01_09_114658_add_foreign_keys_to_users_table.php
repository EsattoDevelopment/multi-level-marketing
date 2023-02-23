<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('empresa_id', 'empresa_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('parceiro_id', 'fk_user_parceiro1_idx')->references('id')->on('parceiro')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('indicador_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('titulo_id')->references('id')->on('titulos')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('empresa_id');
            $table->dropForeign('fk_user_parceiro1_idx');
            $table->dropForeign('users_indicador_id_foreign');
            $table->dropForeign('users_titulo_id_foreign');
        });
    }
}
