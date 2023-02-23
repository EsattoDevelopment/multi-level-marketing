<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExtratoBinarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extrato_binario', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->integer('pontos')->nullable();
            $table->integer('saldo_anterior')->nullable();
            $table->integer('saldo')->nullable();
            $table->integer('referencia')->nullable();
            $table->integer('acumulado_direita')->nullable();
            $table->integer('acumulado_esquerda')->nullable();
            $table->integer('acumulado_total')->nullable();
            $table->integer('saldo_direita')->nullable();
            $table->integer('saldo_esquerda')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('operacao_id')->unsigned()->index('extrato_binario_operacao_id_foreign')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'operacao_id'], 'extrato_binario_user_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('extrato_binario');
    }
}
