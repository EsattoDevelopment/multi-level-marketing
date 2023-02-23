<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDocumentosRecusados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_recusados', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->string('documento');
            $table->string('motivo_recusa');
            $table->string('path_documento');
            $table->integer('user_id')->unsigned()->nullable()->index('documentos_recusados_user_id_foreign');
            $table->integer('responsavel_id')->unsigned()->nullable()->index('documentos_recusados_responsavel_id_foreign');
            $table->integer('banco_id')->unsigned()->nullable()->index('documentos_recusados_banco_id_foreign');
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
        Schema::drop('documentos_recusados');
    }
}
