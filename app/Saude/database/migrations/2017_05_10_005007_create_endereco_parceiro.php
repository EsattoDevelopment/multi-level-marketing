<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnderecoParceiro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('endereco_parceiro', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('cep', 10);
            $table->string('logradouro');
            $table->string('numero', 5);
            $table->string('bairro');
            $table->string('cidade');
            $table->string('estado');
            $table->string('telefone1');
            $table->string('telefone2');
            $table->string('celular');

            $table->unsignedInteger('parceiro_id');
            $table->foreign('parceiro_id')->references('id')->on('parceiro')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('endereco_parceiro');
    }
}
