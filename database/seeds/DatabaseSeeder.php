<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(PermissionRoleSeeder::class);
        $this->call(TituloSeeder::class);
        $this->call(UsuariosSeeder::class);
        $this->call(EnderecoAdminSeeder::class);
        $this->call(RoleUserSeeder::class);
        $this->call(BancoSeeder::class);
        $this->call(RedeBinariaSeeder::class);
        $this->call(OperacoesSeeder::class);
        $this->call(TipoPedidosSeeder::class);
        $this->call(PedidoStatusSeeder::class);
        $this->call(MetodoPagamentoSeeder::class);
        $this->call(ContaEmpresaSeeder::class);

        $this->call(EmpresaSeeder::class);
        $this->call(EnderecoBancoEmpresaSeeder::class);

        $this->call(SistemaSeeder::class);

        /*
        $this->call(TipoAcomodacaoSeeder::class);
        $this->call(TipoPacoteSeeder::class);
        $this->call(StatusPedidoPacoteSeeder::class);*/

        /*gerar varios usuarios comuns*/
        //$this->call(UserTableSeeder::class);

        Model::reguard();
    }
}
