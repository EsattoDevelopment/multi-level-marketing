<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('cpf')->nullable()->unique();
            $table->string('data_nasc');
            $table->integer('termo');
            $table->integer('status');
            $table->integer('indicador_id')->unsigned()->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('titulo_id')->unsigned()->nullable();
            $table->integer('qualificado');
            $table->integer('equipe_preferencial')->default(0);
            $table->integer('equipe_predefinida')->default(0);
            $table->string('image')->nullable();
            $table->string('codigo', 45)->nullable();
            $table->string('rg', 15)->nullable();
            $table->string('empresa')->nullable();
            $table->string('cnpj', 19)->nullable();
            $table->string('inscricao_estadual', 25)->nullable();
            $table->string('telefone', 15)->nullable();
            $table->string('celular', 15)->nullable();
            $table->string('whatsapp', 15)->nullable();
            $table->string('status_selfie', 100)->nullable();
            $table->string('image_selfie', 30)->nullable();
            $table->string('profissao')->nullable();
            $table->string('sexo', 15)->nullable();
            $table->integer('parceiro_id')->unsigned()->nullable()->index('fk_user_parceiro1_idx');
            $table->integer('empresa_id')->unsigned()->nullable()->index('empresa_id');
            $table->integer('sen-dependente')->nullable();
            $table->integer('tipo')->unsigned()->default(1);
            $table->integer('estado_civil')->default(0);
            $table->string('status_comprovante', 100)->nullable();
            $table->string('status_comprovante_endereco', 100)->nullable();
            $table->string('image_comprovante_endereco', 30)->nullable();
            $table->boolean('verificado')->default(0);
            $table->index(['titulo_id', 'email', 'codigo'], 'users_titulo_id_foreign');
            $table->index(['indicador_id', 'name', 'username'], 'users_indicador_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
