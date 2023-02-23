<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdutosProcedimentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos_procedimentos', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->integer('procedimentos_id')->unsigned()->index('fk_procedimentos_has_produtos_procedimentos1_idx');
            $table->integer('itens_id')->unsigned()->index('fk_procedimentos_has_itens_itens1_idx');
            $table->integer('quantidade');
            $table->integer('carencia');
            $table->integer('reencidencia');
            $table->integer('acumulativo');
            $table->softDeletes();
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
        Schema::drop('produtos_procedimentos');
    }
}
