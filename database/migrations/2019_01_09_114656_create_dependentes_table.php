<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDependentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dependentes', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->string('name', 45)->nullable();
            $table->string('sexo', 45)->nullable();
            $table->integer('status')->nullable();
            $table->dateTime('dt_nasc')->nullable();
            $table->string('parentesco', 45)->nullable();
            $table->integer('titular_id')->unsigned()->index('fk_Dependentes_usuarios_idx');
            $table->softDeletes();
            $table->timestamps();
            $table->string('rg', 50)->nullable();
            $table->string('cpf', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dependentes');
    }
}
