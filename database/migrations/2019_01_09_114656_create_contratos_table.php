<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->dateTime('dt_inicio')->nullable();
            $table->dateTime('dt_fim')->nullable();
            $table->dateTime('dt_parcela')->nullable();
            $table->integer('item_id')->unsigned()->index('fk_contratos_itens_id');
            $table->integer('user_id')->unsigned()->nullable()->index('fk_contratos_usuarios1_idx');
            $table->softDeletes();
            $table->timestamps();
            $table->integer('aguarda_mensalidade')->unsigned()->nullable()->index('fk_aguarda_mensalidade');
            $table->integer('status')->nullable();
            $table->integer('pedido_id')->unsigned()->nullable()->index('fk_contrato_pedido');
            $table->integer('qtd_mensalidades')->nullable();
            $table->float('vl_mensalidades', 10, 0)->nullable();
            $table->integer('temp_contrato')->nullable();
            $table->dateTime('dt_cancelamento')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contratos');
    }
}
