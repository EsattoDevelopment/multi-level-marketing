<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParceiroProcedimentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parceiro_procedimentos', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->integer('parceiro_id')->unsigned()->nullable()->index('fk_parceiro_has_procedimentos_parceiro1_idx');
            $table->integer('procedimentos_id')->unsigned()->nullable()->index('fk_parceiro_has_procedimentos1_idx');
            $table->decimal('valor', 10, 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('parceiro_procedimentos');
    }
}
