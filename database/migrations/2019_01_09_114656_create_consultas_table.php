<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsultasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultas', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->dateTime('data')->default('0000-00-00 00:00:00');
            $table->float('valor', 10, 0)->nullable();
            $table->integer('parceiro_id')->unsigned()->index('fk_consultas_parceiro1_idx');
            $table->integer('status')->nullable();
            $table->integer('medicos_id')->unsigned()->index('fk_consultas_medicos1_idx')->nullable();
            $table->integer('procedimentos_id')->unsigned()->index('fk_consultas_procedimentos1_idx')->nullable();
            $table->integer('especialidades_id')->unsigned()->index('fk_consultas_especialidades1_idx')->nullable();
            $table->integer('titular_id')->unsigned()->index('fk_consultas_usuarios1_idx')->nullable();
            $table->integer('dependentes_id')->unsigned()->nullable()->index('fk_consultas_Dependentes1_idx');
            $table->text('observacao')->nullable();
            $table->string('user_consultorio', 45)->nullable();
            $table->integer('tipo_pagamento_id')->unsigned()->index('fk_consultas_tipo_pagamento1_idx')->nullable();
            $table->integer('user_autoriza')->unsigned()->index('fk_consultas_user1_idx')->nullable();
            $table->integer('user_pagamento')->unsigned()->index('fk_consultas_user2_idx')->comment('usuario da clinica. A pessoa que finaliza a consulta')->nullable();
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
        Schema::drop('consultas');
    }
}
