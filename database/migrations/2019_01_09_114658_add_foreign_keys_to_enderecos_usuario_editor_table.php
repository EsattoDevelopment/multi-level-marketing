<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToEnderecosUsuarioEditorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enderecos_usuario_editor', function (Blueprint $table) {
            $table->foreign('enderecos_usuario_id')->references('id')->on('enderecos_usuario')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::table('enderecos_usuario_editor', function (Blueprint $table) {
            $table->dropForeign('enderecos_usuario_editor_enderecos_usuario_id_foreign');
            $table->dropForeign('enderecos_usuario_editor_user_id_editor_foreign');
            $table->dropForeign('enderecos_usuario_editor_user_id_foreign');
        });
    }
}
