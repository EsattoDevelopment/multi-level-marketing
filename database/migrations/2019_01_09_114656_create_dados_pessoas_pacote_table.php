<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDadosPessoasPacoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dados_pessoas_pacote', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->string('nome')->nullable();
            $table->dateTime('data_nascimento')->default('0000-00-00 00:00:00');
            $table->string('rg')->nullable();
            $table->string('cpf')->nullable();
            $table->string('passaporte')->nullable();
            $table->integer('pedido_pacote_id')->unsigned()->nullable()->index('dados_pessoas_pacote_pedido_pacote_id_foreign');
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
        Schema::drop('dados_pessoas_pacote');
    }
}
