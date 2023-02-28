<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Seeder;

class OperacoesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('operacoes')->insert([
            [
                'id' => 1,
                'name' => 'Adesão Contrato',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-green',
            ], [
                'id' => 2,
                'name' => 'Bonus ciclo',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-green',
            ], [
                'id' => 3,
                'name' => 'Bonus empresa',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-green',
            ], [
                'id' => 4,
                'name' => 'Retirada',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-red',
            ], [
                'id' => 5,
                'name' => 'Taxa Retirada',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-red',
            ], [
                'id' => 6,
                'name' => 'Adesão Contrato.',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-green',
            ], [
                'id' => 7,
                'name' => 'Rentabilidade',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-green',
            ], [
                'id' => 8,
                'name' => 'outro',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => '',
            ], [
                'id' => 9,
                'name' => 'Binario vindo de pedido',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-green',
            ], [
                'id' => 10,
                'name' => 'Pagamento de Binario',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-green',
            ], [
                'id' => 11,
                'name' => 'Payback',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-red',
            ], [
                'id' => 12,
                'name' => 'Pagamento com saldo em conta',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-red',
            ], [
                'id' => 13,
                'name' => 'Pagamento de Bonus acumulado',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-green',
            ], [
                'id' => 14,
                'name' => 'Debito empresa',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-red',
            ], [
                'id' => 15,
                'name' => 'Bônus Diamante',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-green',
            ], [
                'id' => 16,
                'name' => 'Pagamento de pontos',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-green',
            ], [
                'id' => 17,
                'name' => 'Bônus de Expansão/Equiparação',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-green',
            ], [
                'id' => 18,
                'name' => 'Bônus Mensalidade Direta',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-green',
            ], [
                'id' => 19,
                'name' => 'Bônus Mensalidade Indireta',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-green',
            ], [
                'id' => 20,
                'name' => 'Contrato',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-green',
            ], [
                'id' => 21,
                'name' => 'Revisão de bonus',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => '',
            ], [
                'id' => 22,
                'name' => 'Estorno (Bonus)',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => '',
            ], [
                'id' => 23,
                'name' => 'Estorno',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => '',
            ],[
                'id' => 24,
                'name' => 'Débito',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-red',
            ],[
                'id' => 25,
                'name' => 'Crédito',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-green',
            ],[
                'id' => 26,
                'name' => 'Liquidação de capitalizado',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-green',
            ],[
                'id' => 27,
                'name' => 'Recebimento de Royalties',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-green',
            ],[
                'id' => 31,
                'name' => 'Pagamento de Royalties',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-red',
            ],[
                'id' => 32,
                'name' => 'Depósito',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-green',
            ],[
                'id' => 33,
                'name' => 'Transferência',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-red',
            ],[
                'id' => 34,
                'name' => 'Recebimento de Transferência',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-success',
            ],[
                'id' => 35,
                'name' => 'Estorno de rentabilidade',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'cor' => 'text-red',
            ],
        ]);
    }
}
