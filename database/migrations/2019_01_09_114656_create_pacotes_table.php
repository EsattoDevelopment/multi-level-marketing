<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePacotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pacotes', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->string('chamada');
            $table->string('video');
            $table->text('descricao', 65535);
            $table->integer('promocao')->default(0);
            $table->integer('internacional')->default(0);
            $table->integer('valor_milhas');
            $table->integer('status')->default(1);
            $table->integer('quantidade_vagas')->default(-1);
            $table->integer('cidade_id')->nullable();
            $table->integer('tipo_pacote_id')->unsigned()->nullable()->index('pacotes_tipo_pacote_id_foreign');
            $table->integer('galeria_id')->unsigned()->nullable()->index('pacotes_galeria_id_foreign');
            $table->timestamps();
            $table->integer('dias')->default(-1);
            $table->smallInteger('local_selecionavel')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pacotes');
    }
}
