<?php

use Illuminate\Database\Seeder;

class ConfiguracaoEmprestimoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('configuracao_emprestimo')->insert([
            'id' => 1,
            'numero' => 1,
            'nome' => '1x',
            'grupo' => 'Padrão',
            'valor_porcentagem' => 0.02,
            'valor_fixo' => 1.00
        ]);
        DB::table('configuracao_emprestimo')->insert([
            'id' => 2,
            'numero' => 2,
            'nome' => '2x',
            'grupo' => 'Padrão',
            'valor_porcentagem' => 0.02,
            'valor_fixo' => 1.00
        ]);
        DB::table('configuracao_emprestimo')->insert([
            'id' => 3,
            'numero' => 3,
            'nome' => '3x',
            'grupo' => 'Padrão',
            'valor_porcentagem' => 0.02,
            'valor_fixo' => 1.00
        ]);
        DB::table('configuracao_emprestimo')->insert([
            'id' => 4,
            'numero' => 4,
            'nome' => '4x',
            'grupo' => 'Padrão',
            'valor_porcentagem' => 0.02,
            'valor_fixo' => 1.00
        ]);
        DB::table('configuracao_emprestimo')->insert([
            'id' => 5,
            'numero' => 5,
            'nome' => '5x',
            'grupo' => 'Padrão',
            'valor_porcentagem' => 0.02,
            'valor_fixo' => 1.00
        ]);
        DB::table('configuracao_emprestimo')->insert([
            'id' => 6,
            'numero' => 6,
            'nome' => '6x',
            'grupo' => 'Padrão',
            'valor_porcentagem' => 0.02,
            'valor_fixo' => 1.00
        ]);
        DB::table('configuracao_emprestimo')->insert([
            'id' => 7,
            'numero' => 7,
            'nome' => '7x',
            'grupo' => 'Padrão',
            'valor_porcentagem' => 0.02,
            'valor_fixo' => 1.00
        ]);
        DB::table('configuracao_emprestimo')->insert([
            'id' => 8,
            'numero' => 8,
            'nome' => '8x',
            'grupo' => 'Padrão',
            'valor_porcentagem' => 0.02,
            'valor_fixo' => 1.00
        ]);
        DB::table('configuracao_emprestimo')->insert([
            'id' => 9,
            'numero' => 9,
            'nome' => '9x',
            'grupo' => 'Padrão',
            'valor_porcentagem' => 0.02,
            'valor_fixo' => 1.00
        ]);
        DB::table('configuracao_emprestimo')->insert([
            'id' => 10,
            'numero' => 10,
            'nome' => '10x',
            'grupo' => 'Padrão',
            'valor_porcentagem' => 0.02,
            'valor_fixo' => 1.00
        ]);
        DB::table('configuracao_emprestimo')->insert([
            'id' => 11,
            'numero' => 11,
            'nome' => '11x',
            'grupo' => 'Padrão',
            'valor_porcentagem' => 0.02,
            'valor_fixo' => 1.00
        ]);
        DB::table('configuracao_emprestimo')->insert([
            'id' => 12,
            'numero' => 12,
            'nome' => '12x',
            'grupo' => 'Padrão',
            'valor_porcentagem' => 0.02,
            'valor_fixo' => 1.00
        ]);
    }
}
