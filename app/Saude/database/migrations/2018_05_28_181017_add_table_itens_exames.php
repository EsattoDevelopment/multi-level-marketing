<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableItensExames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itens_exames', function (Blueprint $table) {
            $table->engine = 'innoDB';

            $table->unsignedInteger('item_id');
            $table->unsignedInteger('exame_id');

            $table->foreign('item_id', 'fk_item_has_exame')
                ->references('id')
                ->on('itens')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('exame_id', 'fk_exames_has_itens')
                ->references('id')
                ->on('exames')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('itens_exames');
    }
}
