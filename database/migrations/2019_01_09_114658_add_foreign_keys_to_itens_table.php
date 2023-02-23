<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToItensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('itens', function (Blueprint $table) {
            $table->foreign('avanca_titulo')->references('id')->on('titulos')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('tipo_pedido_id')->references('id')->on('tipo_pedidos')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('user_id', 'user_id_foreing')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('itens', function (Blueprint $table) {
            $table->dropForeign('itens_avanca_titulo_foreign');
            $table->dropForeign('itens_tipo_pedido_id_foreign');
            $table->dropForeign('user_id_foreing');
        });
    }
}
