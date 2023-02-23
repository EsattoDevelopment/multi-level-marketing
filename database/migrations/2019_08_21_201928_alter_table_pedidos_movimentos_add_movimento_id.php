<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePedidosMovimentosAddMovimentoId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedidos_movimentos', function (Blueprint $table) {
            $table->unsignedInteger('movimento_id')->nullable();
            $table->foreign('movimento_id', 'pedidos_movimentos_has_movimento')->references('id')->on('movimentos')->onUpdate('CASCADE')->onDelete('CASCADE');
        });

        /*Artisan::call('db:seed', [
            '--class' => Operacao31Seeder::class,
        ]);*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pedidos_movimentos', function (Blueprint $table) {
            $table->dropForeign('pedidos_movimentos_has_movimento');
            $table->dropIndex('pedidos_movimentos_has_movimento');
            $table->dropColumn(['movimento_id']);
        });

        DB::table('operacoes')->whereId('31')->delete();
    }
}
