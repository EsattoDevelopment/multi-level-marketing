<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guias', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->integer('tipo')->nullable();
            $table->integer('tipo_atendimento')->nullable();
            $table->dateTime('dt_atendimento')->default('0000-00-00 00:00:00');
            $table->integer('medico_id')->unsigned()->nullable()->index('guias_medico_id_foreign');
            $table->float('valor_consulta', 10, 0)->nullable();
            $table->integer('dependente_id')->unsigned()->nullable()->index('guias_dependente_id_foreign');
            $table->integer('plano_id')->unsigned()->index('guias_plano_id_foreign')->nullable();
            $table->integer('user_id')->unsigned()->index('guias_user_id_foreign')->nullable();
            $table->text('observacao', 65535)->nullable();
            $table->integer('confirmado_por')->unsigned()->index('guias_confirmado_por_foreign')->nullable();
            $table->integer('clinica_id')->unsigned()->index('guias_clinica_id_foreign')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->char('autorizado', 1)->default(0);
            $table->dateTime('dt_autorizado')->nullable();
            $table->integer('autorizado_por')->unsigned()->nullable()->index('fk_guia_has_user_autoriza');
            $table->float('valor_fisioterapia', 10, 0)->default(0);
            $table->integer('guia_referencia')->unsigned()->nullable()->index('fk_guia_has_guia');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('guias');
    }
}
