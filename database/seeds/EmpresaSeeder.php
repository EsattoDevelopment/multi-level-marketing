<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('empresa')->insert([
            [
                'logo1' => 'logo',
                'logo2' => 'logo',
                'razao_social' => 'Razão Social',
                'nome_fantasia' => 'Nome Fantasia',
                'cnpj' => 'CNPJ',
                'inscricao_estadual' => '000000',

                'nome_contato' => 'Nome Contato',
                'cpf_contato' => '000.000.000-00',
                'rg_contato' => '00.000.000-00',
                'telefone_contato' => '00 0000-0000',
                'email_contato' => 'teste@teste.com',

                'logradouro' => 'Rua',
                'numero' => '000',
                'complemento' => '',
                'bairro' => 'Bairro',
                'cidade' => 'Cidade',
                'cep' => '00000-000',
                'uf' => 'SP',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
