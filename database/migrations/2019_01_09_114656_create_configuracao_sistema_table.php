<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfiguracaoSistemaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuracao_sistema', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->integer('profundidade_unilevel')->nullable();
            $table->float('bonus_milha_cadastro', 10, 0)->nullable();
            $table->float('bonus_ciclo_hotel', 10, 0)->nullable();
            $table->float('custo_hotel', 10, 0)->nullable();
            $table->float('milhas_ciclo_hotel', 10, 0)->nullable();
            $table->integer('validade_milhas_ciclo_hotel')->nullable();
            $table->integer('diretos_qualificacao')->nullable();
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
