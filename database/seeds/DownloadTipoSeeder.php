<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Seeder;

class DownloadTipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipo = DB::table('download_tipo')->insert([
            'titulo' => 'Geral',
            'descricao' => 'Padrao',
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        $tipo = DB::table('download_tipo')->get();

        DB::table('downloads')->where('download_tipo_id', null)->update(['download_tipo_id' => $tipo[0]->id]);
    }
}
