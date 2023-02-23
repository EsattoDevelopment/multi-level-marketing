<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Seeder;

class EnderecoBancoEmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('enderecos_usuario')->insert([
            [
                'cep' => '00000-000',
                'logradouro' => 'Logradouro',
                'numero' => '00',
                'bairro' => 'Bairro',
                'cidade' => 'Cidade',
                'estado' => 'UF',
                'telefone1' => '00 00000-0000',
                'telefone2' => '00 00000-0000',
                'celular' => '00 00000-0000',
                'user_id' => 2,
                'user_id_editor' => 1,
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);

        DB::table('dados_bancarios')->insert([
            [
                'banco' => 'Banco',
                'agencia' => 0,
                'agencia_digito' => 0,
                'conta' => 0,
                'conta_digito' => 0,
                'user_id' => 2,
                'user_id_editor' => 1,
                'tipo_conta' => 0,
                'receber_bonus' => 0,
                'banco_id' => 1,
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
