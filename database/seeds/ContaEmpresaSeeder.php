<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Seeder;

class ContaEmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contas_empresa')->insert([
            [
                'usar_boleto' => 0,
                'banco_id' => \App\Models\Bancos::whereCodigo('104')->first()->id,
                'dataVencimento' => 0,
                'multa' => 0,
                'juros' => 0,
                'juros_apos' => 0,
                'diasProtesto' => 0,
                'agencia' => 0000,
                'agenciaDv' => 0,
                'conta' => '000000',
                'contaDv' => 0,
                'codigoCliente' => '00000',
                'aceite' => 0,
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
