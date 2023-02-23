<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToItensExamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('itens_exames', function (Blueprint $table) {
            $table->foreign('exame_id', 'fk_exames_has_itens')->references('id')->on('exames')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('item_id', 'fk_item_has_exame')->references('id')->on('itens')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('itens_exames', function (Blueprint $table) {
            $table->dropForeign('fk_exames_has_itens');
            $table->dropForeign('fk_item_has_exame');
        });
    }
}
