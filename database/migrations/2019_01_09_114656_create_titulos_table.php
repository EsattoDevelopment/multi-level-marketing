<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTitulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('titulos', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->string('name');
            $table->integer('acumulo_pessoal_milhas');
            $table->integer('milhas_indicador');
            $table->float('dinheiro_indicador', 10, 0);
            $table->integer('binario_patrocinado');
            $table->integer('min_diretos_aprovados');
            $table->integer('percentual_binario');
            $table->integer('teto_pagamento_sobre_binario');
            $table->integer('teto_mensal_financeiro');
            $table->integer('min_pontuacao_perna_menor');
            $table->integer('bonus_hvip_diretos');
            $table->text('descricao', 65535);
            $table->integer('titulo_inicial');
            $table->string('cor', 6);
            $table->integer('titulo_superior')->unsigned()->nullable()->index('titulos_titulo_superior_foreign');
            $table->softDeletes();
            $table->timestamps();
            $table->integer('recebe_pontuacao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('titulos');
    }
}
