<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Administrador',
            'username' => 'master',
            'email' => 'admin@teste.com.br',
            'password' => bcrypt('teste123*'),
            'termo' => '1',
            'titulo_id' => 1,
            'status' => '1',
            'celular' => '11111111',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('users')->insert([
            'name' => 'Empresa',
            'username' => 'empresa',
            'email' => 'diretoria@empresa.com.br',
            'password' => bcrypt('teste123*'),
            'termo' => '1',
            'cpf' => '111.111.111-11',
            'titulo_id' => 1,
            'status' => '1',
            'celular' => '111111',
            'equipe_preferencial' => 2,
            'qualificado' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
