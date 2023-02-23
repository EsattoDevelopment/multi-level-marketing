<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidosMovimentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos_movimentos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->decimal('valor_manipulado', 10, 2)->nullable();
            $table->decimal('saldo_anterior', 10, 2)->nullable();
            $table->decimal('saldo', 10, 2)->nullable();
            $table->text('descricao')->nullable();
            $table->tinyInteger('status')->nullable();

            $table->unsignedInteger('pedido_id')->nullable();
            $table->unsignedInteger('item_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('operacao_id')->nullable();
            $table->unsignedInteger('responsavel_user_id')->nullable();
            $table->unsignedInteger('mensalidade_id')->nullable();

            $table->foreign('pedido_id', 'movimento_has_pedido')
                ->references('id')
                ->on('pedidos')
                ->ondelete('cascade');
            $table->foreign('item_id', 'movimento_has_item')
                ->references('id')
                ->on('itens')
                ->ondelete('cascade');
            $table->foreign('user_id', 'movimento_has_usuario')
                ->references('id')
                ->on('users')
                ->ondelete('cascade');
            $table->foreign('operacao_id', 'movimento_has_operacao')
                ->references('id')
                ->on('operacoes')
                ->ondelete('cascade');
            $table->foreign('responsavel_user_id', 'movimento_has_responsavel')
                ->references('id')
                ->on('users')
                ->ondelete('cascade');
            $table->foreign('mensalidade_id', 'movimento_has_mensalidade')
                ->references('id')
                ->on('mensalidades')
                ->ondelete('cascade');

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
        Schema::drop('pedidos_movimentos');
    }
}
