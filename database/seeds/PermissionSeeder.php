<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            [
                'name' => 'master',
                'display_name' => 'Master',
                'description' => 'Permissão total do sistema',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'admin',
                'display_name' => 'Administrador',
                'description' => 'Administrador do sistema',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'editar-proprio-user',
                'display_name' => 'Editar Próprio Usuário',
                'description' => 'Editar Próprio Usuário',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'cadastrar-usuario',
                'display_name' => 'Cadastrar usuário',
                'description' => 'Possibilita cadastrar usuários',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'habilitar-usuario',
                'display_name' => 'Habilitar usuário',
                'description' => 'Pode habilitar Usuário',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'desabilitar-usuario',
                'display_name' => 'Desabilitar usuário',
                'description' => 'Pode desabilitar usuário',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'gerar-boleto',
                'display_name' => 'Gerar boleto',
                'description' => 'Pode gerar boleto para cliente',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'faz-agendamento',
                'display_name' => 'Faz agendamento',
                'description' => 'Permite fazer agendamentos',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'conclui-agendamento',
                'display_name' => 'Conclui agendamento',
                'description' => 'Pode concluir um agendamento',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'cadastrar-medico',
                'display_name' => 'Cadastrar médico',
                'description' => '',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'cadastrar-procedimentos',
                'display_name' => 'Cadastrar procedimentos',
                'description' => '',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'alterar-procedimentos',
                'display_name' => 'Alterar procedimentos',
                'description' => '',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'verificar-agendamento',
                'display_name' => 'Verificar agendamento',
                'description' => '',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'cadastrar-dependentes',
                'display_name' => 'Cadastrar dependentes',
                'description' => '',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'editar-usuario-empresa',
                'display_name' => 'Editar usuário da empresa',
                'description' => 'Pode editar os usuário ligados a empresa',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'habilitar-usuario-empresa',
                'display_name' => 'Habilitar usuário da empresa',
                'description' => '',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'desabilitar-user-empresa',
                'display_name' => 'Desabilitar usuário da empresa',
                'description' => '',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'add-user-empresa',
                'display_name' => 'Adicionar usuário na empresa',
                'description' => '',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'gerar-guia-consulta',
                'display_name' => 'Gerar guia de consulta',
                'description' => '',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'guia-visualizar-todas',
                'display_name' => 'Visualizar todas as guias',
                'description' => '',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'guia-autorizar',
                'display_name' => 'Autorizar Guias',
                'description' => '',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'guia-cancelar',
                'display_name' => 'Cancelar Guia',
                'description' => '',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'guia-imprimir-qualquer',
                'display_name' => 'Imprimir qualquer guia',
                'description' => '',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ], [
                'name' => 'guia-editar-qualquer',
                'display_name' => 'Editar qualquer guia',
                'description' => '',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
