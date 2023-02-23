<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcedimentosSubsidioTable extends Migration
{
    /**
     * Run the migrations.
     * @table procedimentos_subsidio
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procedimentos_subsidio', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('procedimentos_id');
            $table->unsignedInteger('user_id');
            $table->timestamp('inicio_vigencia')->nullable();
            $table->timestamp('fim_vigencia')->nullable();
            $table->integer('status')->nullable();

            $table->index(['procedimentos_id'], 'fk_procedimentos_subsidio_procedimentos1_idx');

            $table->index(['user_id'], 'fk_procedimentos_subsidio_user1_idx');

            $table->foreign('procedimentos_id', 'fk_procedimentos_subsidio_procedimentos1_idx')
                ->references('id')->on('procedimentos')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('user_id', 'fk_procedimentos_subsidio_user1_idx')
                ->references('id')->on('users')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('procedimentos_subsidio');
    }
}
