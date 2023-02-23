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
     * @table consultas
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultas', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamp('data');
            $table->double('valor');
            $table->unsignedInteger('parceiro_id');
            $table->integer('status');
            $table->unsignedInteger('medicos_id');
            $table->unsignedInteger('procedimentos_id');
            $table->unsignedInteger('especialidades_id');
            $table->unsignedInteger('titular_id');
            $table->unsignedInteger('dependentes_id')->nullable();
            $table->longText('observacao')->nullable();
            $table->string('user_consultorio', 45)->nullable();
            $table->unsignedInteger('tipo_pagamento_id');
            $table->unsignedInteger('user_autoriza');
            $table->unsignedInteger('user_pagamento')->comment('usuario da clinica. A pessoa que finaliza a consulta');

            $table->index(['user_pagamento'], 'fk_consultas_user2_idx');

            $table->index(['dependentes_id'], 'fk_consultas_Dependentes1_idx');

            $table->index(['tipo_pagamento_id'], 'fk_consultas_tipo_pagamento1_idx');

            $table->index(['titular_id'], 'fk_consultas_usuarios1_idx');

            $table->index(['user_autoriza'], 'fk_consultas_user1_idx');

            $table->index(['especialidades_id'], 'fk_consultas_especialidades1_idx');

            $table->index(['medicos_id'], 'fk_consultas_medicos1_idx');

            $table->index(['procedimentos_id'], 'fk_consultas_procedimentos1_idx');

            $table->index(['parceiro_id'], 'fk_consultas_parceiro1_idx');

            $table->foreign('parceiro_id', 'fk_consultas_parceiro1_idx')
                ->references('id')->on('parceiro')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('medicos_id', 'fk_consultas_medicos1_idx')
                ->references('id')->on('medicos')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('procedimentos_id', 'fk_consultas_procedimentos1_idx')
                ->references('id')->on('procedimentos')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('especialidades_id', 'fk_consultas_especialidades1_idx')
                ->references('id')->on('especialidades')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('titular_id', 'fk_consultas_usuarios1_idx')
                ->references('id')->on('users')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('dependentes_id', 'fk_consultas_Dependentes1_idx')
                ->references('id')->on('dependentes')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('tipo_pagamento_id', 'fk_consultas_tipo_pagamento1_idx')
                ->references('id')->on('tipo_pagamento')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('user_autoriza', 'fk_consultas_user1_idx')
                ->references('id')->on('users')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('user_pagamento', 'fk_consultas_user2_idx')
                ->references('id')->on('users')
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
        Schema::dropIfExists('consultas');
    }
}
