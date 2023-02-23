<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Seeder;

class TituloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('titulos')->insert([
            'name' => 'Teste',
            'acumulo_pessoal_milhas' => 0,
            'milhas_indicador' => 0,
            'bonus_indicador' => 0,
            'binario_patrocinado' => 0,
            'min_diretos_aprovados' => 20,
            'percentual_binario' => 20,
            'teto_pagamento_sobre_binario' => 500,
            'teto_mensal_financeiro' => 50000,
            'min_pontuacao_perna_menor' => 20000,
            'bonus_hvip_diretos' => 1,
            'titulo_inicial' => 1,
            'recebe_pontuacao' => 1,
            'cor'=> 'aaa9a9',
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
