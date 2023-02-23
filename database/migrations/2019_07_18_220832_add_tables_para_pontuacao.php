<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTablesParaPontuacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pontos_pessoais', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('pontos')->nullable();
            $table->unsignedInteger('saldo_anterior')->nullable();
            $table->unsignedInteger('saldo')->nullable();
            $table->unsignedInteger('pedido_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('operacao_id')->nullable();
            $table->timestamps();

            $table->foreign('pedido_id', 'pontosp_has_pedido')->references('id')->on('pedidos')->ondelete('cascade');
            $table->foreign('user_id', 'pontosp_has_user')->references('id')->on('users')->ondelete('cascade');
            $table->foreign('operacao_id', 'pontosp_has_operacao')->references('id')->on('operacoes')->ondelete('cascade');
        });

        Schema::create('pontos_equipe_equiparacao', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('pontos')->nullable();
            $table->unsignedInteger('saldo_anterior')->nullable();
            $table->unsignedInteger('saldo')->nullable();
            $table->unsignedInteger('pedido_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('operacao_id')->nullable();
            $table->timestamps();

            $table->foreign('pedido_id', 'pontosee_has_pedido')->references('id')->on('pedidos')->ondelete('cascade');
            $table->foreign('user_id', 'pontosee_has_user')->references('id')->on('users')->ondelete('cascade');
            $table->foreign('operacao_id', 'pontosee_has_operacao')->references('id')->on('operacoes')->ondelete('cascade');
        });

        Schema::create('pontos_equipe_unilevel', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('pontos')->nullable();
            $table->unsignedInteger('saldo_anterior')->nullable();
            $table->unsignedInteger('saldo')->nullable();
            $table->unsignedInteger('pedido_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('operacao_id')->nullable();
            $table->timestamps();

            $table->foreign('pedido_id', 'pontoseu_has_pedido')->references('id')->on('pedidos')->ondelete('cascade');
            $table->foreign('user_id', 'pontoseu_has_user')->references('id')->on('users')->ondelete('cascade');
            $table->foreign('operacao_id', 'pontoseu_has_operacao')->references('id')->on('operacoes')->ondelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pontos_pessoais');
        Schema::drop('pontos_equipe_equiparacao');
        Schema::drop('pontos_equipe_unilevel');
    }
}
