<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableCarreira extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carreira', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('saldo_anterior')->default(0);
            $table->unsignedInteger('valor_manipulado');
            $table->unsignedInteger('saldo_atual')->default(0);
            $table->unsignedInteger('operacao_id');
            $table->unsignedInteger('pedido_id')->nullable();
            $table->unsignedInteger('mensalidade_id')->nullable();
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
        Schema::drop('carreira');
    }
}
