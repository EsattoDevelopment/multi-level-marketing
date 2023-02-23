<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToRentabilidadeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rentabilidades', function (Blueprint $table) {
            $table->foreign('titulo_id', 'rentabilidade_has_titulo')->references('id')->on('titulos')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('item_id', 'rentabilidade_has_itens')->references('id')->on('itens')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rentabilidades', function (Blueprint $table) {
            $table->dropForeign('rentabilidade_has_titulo');
            $table->dropIndex('rentabilidade_has_titulo');

            $table->dropForeign('rentabilidade_has_itens');
            $table->dropIndex('rentabilidade_has_itens');
        });
    }
}
