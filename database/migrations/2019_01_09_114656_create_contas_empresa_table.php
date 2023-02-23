<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContasEmpresaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contas_empresa', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->integer('dataVencimento')->nullable();
            $table->integer('usar_boleto')->nullable();
            $table->integer('multa')->nullable();
            $table->integer('juros')->nullable();
            $table->integer('juros_apos')->nullable();
            $table->integer('diasProtesto')->nullable();
            $table->string('logo_empresa')->nullable();
            $table->integer('agencia')->nullable();
            $table->string('agenciaDv', 2);
            $table->string('conta')->nullable();
            $table->string('contaDv', 2)->nullable();
            $table->string('carteira')->nullable();
            $table->string('convenio')->nullable();
            $table->string('variacaoCarteira')->nullable();
            $table->string('range')->nullable();
            $table->string('codigoCliente')->nullable();
            $table->string('ios')->nullable();
            $table->string('msg1')->nullable();
            $table->string('msg2')->nullable();
            $table->string('msg3')->nullable();
            $table->string('msg4')->nullable();
            $table->string('msg5')->nullable();
            $table->string('inst1')->nullable();
            $table->string('inst2')->nullable();
            $table->string('inst3')->nullable();
            $table->string('inst4')->nullable();
            $table->string('inst5')->nullable();
            $table->string('aceite')->nullable();
            $table->string('especieDoc')->nullable();
            $table->timestamps();
            $table->integer('banco_id')->unsigned()->nullable()->index('contas_empresa_banco_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contas_empresa');
    }
}
