<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsultasGratuitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultas_gratuitas', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->integer('especialidades_id')->unsigned()->index('fk_consultas_gratuitas_especialidades1_idx')->nullable();
            $table->integer('user_id')->unsigned()->index('fk_consultas_gratuitas_usuarios1_idx')->nullable();
            $table->string('quantidade', 45)->nullable();
            $table->date('inicio_validade')->nullable();
            $table->date('fim_validade')->nullable();
            $table->integer('pedidos_id')->unsigned()->index('fk_consultas_gratuitas_pedidos1_idx')->nullable();
            $table->softDeletes();
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
        Schema::drop('consultas_gratuitas');
    }
}
