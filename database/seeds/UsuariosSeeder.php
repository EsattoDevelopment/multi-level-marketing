<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

    use Illuminate\Database\Seeder;

    class UsuariosSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
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
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
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
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        }
    }
