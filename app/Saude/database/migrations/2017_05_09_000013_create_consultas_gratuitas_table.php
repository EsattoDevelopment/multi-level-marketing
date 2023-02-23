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
     * @table consultas_gratuitas
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultas_gratuitas', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('especialidades_id');
            $table->unsignedInteger('user_id');
            $table->string('quantidade', 45)->nullable();
            $table->date('inicio_validade')->nullable();
            $table->date('fim_validade')->nullable();
            $table->unsignedInteger('pedidos_id');

            $table->index(['pedidos_id'], 'fk_consultas_gratuitas_pedidos1_idx');

            $table->index(['especialidades_id'], 'fk_consultas_gratuitas_especialidades1_idx');

            $table->index(['user_id'], 'fk_consultas_gratuitas_usuarios1_idx');

            $table->foreign('especialidades_id', 'fk_consultas_gratuitas_especialidades1_idx')
                ->references('id')->on('especialidades')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('user_id', 'fk_consultas_gratuitas_usuarios1_idx')
                ->references('id')->on('users')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('pedidos_id', 'fk_consultas_gratuitas_pedidos1_idx')
                ->references('id')->on('pedidos')
                ->onDelete('no action')
                ->onUpdate('no action');

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
        Schema::dropIfExists('consultas_gratuitas');
    }
}
