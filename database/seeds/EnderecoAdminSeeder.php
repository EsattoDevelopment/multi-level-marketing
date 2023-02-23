<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Seeder;

class EnderecoAdminSeeder extends Seeder
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
                'logradouro' => 'rua',
                'numero' => 'numero',
                'bairro' => 'bairro',
                'cidade' => 'cidade',
                'estado' => 'estado',
                'telefone1' => '000000000',
                'telefone2' => '000000000000',
                'celular' => '00000000000',
                'complemento' => 'complemento',
                'user_id' => 1,
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'cep' => '00000-000',
                'logradouro' => 'rua',
                'numero' => 'numero',
                'bairro' => 'bairro',
                'cidade' => 'cidade',
                'estado' => 'estado',
                'telefone1' => '000000000',
                'telefone2' => '000000000000',
                'celular' => '00000000000',
                'complemento' => 'complemento',
                'user_id' => 2,
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
