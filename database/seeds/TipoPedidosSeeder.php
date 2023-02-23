<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Seeder;

class TipoPedidosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_pedidos')->insert([
           [
               'name' => 'Primeiro pedido',
               'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
               'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
           ], [
               'name' => 'Pedido padrão',
               'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
               'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
           ], [
                'name' => 'Pedido consultor',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
