<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permission_role')->insert([
            [
                'role_id' => Role::whereName('master')->first()->id,
                'permission_id' => Permission::whereName('master')->first()->id,
            ], [
                'role_id' => Role::whereName('admin')->first()->id,
                'permission_id' => Permission::whereName('admin')->first()->id,
            ], [
                'role_id' => Role::whereName('usuario-comum')->first()->id,
                'permission_id' => Permission::whereName('editar-proprio-user')->first()->id,
            ], [
                'role_id' => Role::whereName('usuario-comum')->first()->id,
                'permission_id' => Permission::whereName('cadastrar-dependentes')->first()->id,
            ], [
                'role_id' => Role::whereName('user-clinica')->first()->id,
                'permission_id' => Permission::whereName('conclui-agendamento')->first()->id,
            ], [
                'role_id' => Role::whereName('user-clinica')->first()->id,
                'permission_id' => Permission::whereName('verificar-agendamento')->first()->id,
            ], [
                'role_id' => Role::whereName('clinica')->first()->id,
                'permission_id' => Permission::whereName('cadastrar-usuario')->first()->id,
            ], [
                'role_id' => Role::whereName('clinica')->first()->id,
                'permission_id' => Permission::whereName('habilitar-usuario')->first()->id,
            ], [
                'role_id' => Role::whereName('clinica')->first()->id,
                'permission_id' => Permission::whereName('desabilitar-usuario')->first()->id,
            ], [
                'role_id' => Role::whereName('clinica')->first()->id,
                'permission_id' => Permission::whereName('conclui-agendamento')->first()->id,
            ], [
                'role_id' => Role::whereName('clinica')->first()->id,
                'permission_id' => Permission::whereName('cadastrar-medico')->first()->id,
            ], [
                'role_id' => Role::whereName('clinica')->first()->id,
                'permission_id' => Permission::whereName('verificar-agendamento')->first()->id,
            ], [
                'role_id' => Role::whereName('clinica')->first()->id,
                'permission_id' => Permission::whereName('guia-autorizar')->first()->id,
            ], [
                'role_id' => Role::whereName('clinica')->first()->id,
                'permission_id' => Permission::whereName('guia-cancelar')->first()->id,
            ], [
                'role_id' => Role::whereName('user-empresa')->first()->id,
                'permission_id' => Permission::whereName('cadastrar-usuario')->first()->id,
            ], [
                'role_id' => Role::whereName('user-empresa')->first()->id,
                'permission_id' => Permission::whereName('habilitar-usuario')->first()->id,
            ], [
                'role_id' => Role::whereName('user-empresa')->first()->id,
                'permission_id' => Permission::whereName('desabilitar-usuario')->first()->id,
            ], [
                'role_id' => Role::whereName('empresa')->first()->id,
                'permission_id' => Permission::whereName('editar-proprio-user')->first()->id,
            ], [
                'role_id' => Role::whereName('empresa')->first()->id,
                'permission_id' => Permission::whereName('editar-usuario-empresa')->first()->id,
            ], [
                'role_id' => Role::whereName('empresa')->first()->id,
                'permission_id' => Permission::whereName('habilitar-usuario-empresa')->first()->id,
            ], [
                'role_id' => Role::whereName('empresa')->first()->id,
                'permission_id' => Permission::whereName('desabilitar-user-empresa')->first()->id,
            ], [
                'role_id' => Role::whereName('empresa')->first()->id,
                'permission_id' => Permission::whereName('add-user-empresa')->first()->id,
            ], [
                'role_id' => Role::whereName('user-callcenter')->first()->id,
                'permission_id' => Permission::whereName('gerar-guia-consulta')->first()->id,
            ], [
                'role_id' => Role::whereName('user-callcenter')->first()->id,
                'permission_id' => Permission::whereName('guia-visualizar-todas')->first()->id,
            ], [
                'role_id' => Role::whereName('user-callcenter')->first()->id,
                'permission_id' => Permission::whereName('guia-cancelar')->first()->id,
            ], [
                'role_id' => Role::whereName('user-callcenter')->first()->id,
                'permission_id' => Permission::whereName('guia-imprimir-qualquer')->first()->id,
            ], [
                'role_id' => Role::whereName('user-callcenter')->first()->id,
                'permission_id' => Permission::whereName('guia-editar-qualquer')->first()->id,
            ],
        ]);
    }
}
