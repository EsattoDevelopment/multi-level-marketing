<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToConsultasGratuitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consultas_gratuitas', function (Blueprint $table) {
            $table->foreign('especialidades_id', 'fk_consultas_gratuitas_especialidades1_idx')->references('id')->on('especialidades')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('pedidos_id', 'fk_consultas_gratuitas_pedidos1_idx')->references('id')->on('pedidos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('user_id', 'fk_consultas_gratuitas_usuarios1_idx')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consultas_gratuitas', function (Blueprint $table) {
            $table->dropForeign('fk_consultas_gratuitas_especialidades1_idx');
            $table->dropForeign('fk_consultas_gratuitas_pedidos1_idx');
            $table->dropForeign('fk_consultas_gratuitas_usuarios1_idx');
        });
    }
}
