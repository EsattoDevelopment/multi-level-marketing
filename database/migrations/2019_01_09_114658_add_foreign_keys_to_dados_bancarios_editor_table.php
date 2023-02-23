<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToDadosBancariosEditorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dados_bancarios_editor', function (Blueprint $table) {
            $table->foreign('banco_id')->references('id')->on('banco')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('dados_bancarios_id')->references('id')->on('dados_bancarios')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('user_id_editor')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dados_bancarios_editor', function (Blueprint $table) {
            $table->dropForeign('dados_bancarios_editor_banco_id_foreign');
            $table->dropForeign('dados_bancarios_editor_dados_bancarios_id_foreign');
            $table->dropForeign('dados_bancarios_editor_user_id_editor_foreign');
            $table->dropForeign('dados_bancarios_editor_user_id_foreign');
        });
    }
}
