<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToHospedesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospedes', function (Blueprint $table) {
            $table->foreign('direita')->references('id')->on('hotels')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('esquerda')->references('id')->on('hotels')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('hotel_id')->references('id')->on('hotels')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hospedes', function (Blueprint $table) {
            $table->dropForeign('hospedes_direita_foreign');
            $table->dropForeign('hospedes_esquerda_foreign');
            $table->dropForeign('hospedes_hotel_id_foreign');
        });
    }
}
