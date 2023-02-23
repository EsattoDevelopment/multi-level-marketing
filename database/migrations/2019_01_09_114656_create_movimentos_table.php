<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovimentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimentos', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->decimal('valor_manipulado');
            $table->decimal('saldo_anterior');
            $table->decimal('saldo');
            $table->integer('referencia');
            $table->string('documento');
            $table->string('descricao');
            $table->integer('responsavel_user_id')->unsigned()->index('movimentos_responsavel_user_id_foreign');
            $table->integer('user_id')->unsigned()->index('movimentos_user_id_foreign');
            $table->integer('operacao_id')->unsigned()->index('movimentos_operacao_id_foreign');
            $table->timestamps();
            $table->integer('pedido_id')->unsigned()->nullable()->index('movimentos_pedido_id_foreign');
            $table->integer('mensalidade_id')->unsigned()->nullable()->index('movimentos_mensalidade_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('movimentos');
    }
}
