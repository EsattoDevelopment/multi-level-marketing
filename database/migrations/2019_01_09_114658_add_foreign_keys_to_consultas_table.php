<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToConsultasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consultas', function (Blueprint $table) {
            $table->foreign('dependentes_id', 'fk_consultas_Dependentes1_idx')->references('id')->on('dependentes')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('especialidades_id', 'fk_consultas_especialidades1_idx')->references('id')->on('especialidades')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('medicos_id', 'fk_consultas_medicos1_idx')->references('id')->on('medicos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('parceiro_id', 'fk_consultas_parceiro1_idx')->references('id')->on('parceiro')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('procedimentos_id', 'fk_consultas_procedimentos1_idx')->references('id')->on('procedimentos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('tipo_pagamento_id', 'fk_consultas_tipo_pagamento1_idx')->references('id')->on('tipo_pagamento')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('user_autoriza', 'fk_consultas_user1_idx')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('user_pagamento', 'fk_consultas_user2_idx')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('titular_id', 'fk_consultas_usuarios1_idx')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consultas', function (Blueprint $table) {
            $table->dropForeign('fk_consultas_Dependentes1_idx');
            $table->dropForeign('fk_consultas_especialidades1_idx');
            $table->dropForeign('fk_consultas_medicos1_idx');
            $table->dropForeign('fk_consultas_parceiro1_idx');
            $table->dropForeign('fk_consultas_procedimentos1_idx');
            $table->dropForeign('fk_consultas_tipo_pagamento1_idx');
            $table->dropForeign('fk_consultas_user1_idx');
            $table->dropForeign('fk_consultas_user2_idx');
            $table->dropForeign('fk_consultas_usuarios1_idx');
        });
    }
}
