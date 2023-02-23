<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfiguracaoSistemasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuracao_sistema', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('profundidade_unilevel');
            $table->double('bonus_milha_cadastro');
            $table->double('bonus_ciclo_hotel');
            $table->double('custo_hotel');
            $table->double('milhas_ciclo_hotel');
            $table->integer('validade_milhas_ciclo_hotel');
            $table->integer('diretos_qualificacao');
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
        Schema::drop('configuracao_sistema');
    }
}
