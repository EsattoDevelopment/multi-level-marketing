<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Seeder;

class SistemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('configuracao_sistema')->insert([
            [
                'sistema_viagens' => 0, //sim = 1, não = 0
                'bonus_milha_cadastro' => 0,
                'bonus_ciclo_hotel' => 0,
                'milhas_ciclo_hotel' => 0,
                'validade_milhas_ciclo_hotel' => 0,

                'diretos_qualificacao' => 0, //sem uso
                'profundidade_unilevel' => 0, //sem uso

                'sistema_saude' => 0, //sim = 1, não = 0

                'paga_bonus_diario_titulo' => 0, //sim = 1, não = 0
                'paga_bonus_diario_item' => 0, //sim = 1, não = 0

                'matriz_unilevel' => 0, //sim = 1, não = 0
                'matriz_fechada' => 0, //sim = 1, não = 0
                'matriz_fechada_tamanho' => 0, // Int
                'profundidade_pagamento_matriz' => 0, // Int

                'item_direcionado' => 0, //sim = 1, não = 0

                'update_titulo' => 0, //sim = 1, não = 0
                'update_titulo_automatico' => 0, //sim = 1, não = 0

                'moeda' => 'R$', //$, R$, £

                'rede_binaria' => 0, //sim = 1, não = 0
                'valor_ponto_binario' => 0.00, // double

                'bonificacao_diaria' => 0, // sim = 1, não = 0
                'bonificacao_diaria_recorrente' => 0, // sim = 1, não = 0

                'tipo_teto_pagamento' => 1, //valor fixo = 1, percentual = 2

                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
