<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresa', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->string('logo1')->nullable();
            $table->string('logo2')->nullable();
            $table->string('logo_flutuante')->nullable();
            $table->string('logo_email')->nullable();
            $table->string('razao_social')->nullable();
            $table->string('nome_fantasia')->nullable();
            $table->string('cnpj')->nullable();
            $table->string('inscricao_estadual')->nullable();
            $table->string('nome_contato')->nullable();
            $table->string('cpf_contato')->nullable();
            $table->string('rg_contato')->nullable();
            $table->string('telefone_contato')->nullable();
            $table->string('email_contato')->nullable();
            $table->string('logradouro')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('cep')->nullable();
            $table->string('uf')->nullable();
            $table->string('link_facebook')->nullable();
            $table->string('link_instagram')->nullable();
            $table->string('nome_termo_inicial')->nullable();
            $table->string('termo_inicial')->nullable();
            $table->timestamps();
            $table->string('background')->nullable();
            $table->string('background_manutencao')->nullable();
            $table->string('logo')->nullable();
            $table->string('cor')->nullable();
            $table->string('site')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('empresa');
    }
}
