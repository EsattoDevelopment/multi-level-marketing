<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePacotesTipoAcomodacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pacotes_tipo_acomodacao', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->integer('pacotes_id')->unsigned()->nullable()->index('pacotes_tipo_acomodacao_pacotes_id_foreign');
            $table->integer('tipo_acomodacao_id')->unsigned()->nullable()->index('pacotes_tipo_acomodacao_tipo_acomodacao_id_foreign');
            $table->float('valor', 10, 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pacotes_tipo_acomodacao');
    }
}
