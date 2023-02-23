<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Migrations\Migration;

class InsertOperacaoDepositoETipoPedido extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('operacoes')->insert([
            [
                'id' => 32,
                'name' => 'Depósito',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-green',
            ],
        ]);

        /* tipo_pedido */
        DB::table('tipo_pedidos')->insert([
            [
                'id' => 4,
                'name' => 'Depósito',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\Models\Operacoes::destroy(32);
        \App\Models\TipoPedidos::destroy(4);
    }
}
