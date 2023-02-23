<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnderecosUsuarioEditorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enderecos_usuario_editor', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->string('cep', 10)->nullable();
            $table->string('logradouro')->nullable();
            $table->string('numero', 5)->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado')->nullable();
            $table->string('telefone1')->nullable();
            $table->string('telefone2')->nullable();
            $table->string('celular')->nullable();
            $table->integer('user_id')->unsigned()->index('enderecos_usuario_editor_user_id_foreign')->nullable();
            $table->integer('user_id_editor')->unsigned()->index('enderecos_usuario_editor_user_id_editor_foreign')->nullable();
            $table->integer('enderecos_usuario_id')->unsigned()->index('enderecos_usuario_editor_enderecos_usuario_id_foreign')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('enderecos_usuario_editor');
    }
}
