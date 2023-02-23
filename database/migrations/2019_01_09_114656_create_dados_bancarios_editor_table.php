<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDadosBancariosEditorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dados_bancarios_editor', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->string('banco')->nullable();
            $table->string('agencia')->nullable();
            $table->string('agencia_digito')->nullable();
            $table->string('conta')->nullable();
            $table->string('conta_digito')->nullable();
            $table->integer('user_id')->unsigned()->index('dados_bancarios_editor_user_id_foreign');
            $table->integer('user_id_editor')->unsigned()->index('dados_bancarios_editor_user_id_editor_foreign');
            $table->integer('dados_bancarios_id')->unsigned()->index('dados_bancarios_editor_dados_bancarios_id_foreign');
            $table->timestamps();
            $table->integer('banco_id')->unsigned()->nullable()->index('dados_bancarios_editor_banco_id_foreign');
            $table->integer('tipo_conta')->default(1);
            $table->integer('receber_bonus')->default(2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dados_bancarios_editor');
    }
}
