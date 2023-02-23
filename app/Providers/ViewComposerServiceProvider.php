<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Providers;

use View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer([
            'default/home',
            'default/home/timeline/rentabilidade',
            'default/itens/index',
            'default/itens/create',
            'default/itens/edit',
            'default/pedidos/pedidos',
            'default/pedidos/contrato',
            'default/pedidos/user-pedido',
            'default/pedidos/index',
            'default/pedidos/visualizar-outros',
            'default/pedidos/aguardando-pagamento',
            'default/pedidos/create',
            'default/pedidos/contrato-cap30',
            'default/pedidos/aguardando-confirmacao',
            'default/pedidos/consultor',
            'default/pedidos/contrato-cap60',
            'default/pedidos/interna',
            'default/pedidos/show',
            'default/pedidos/visualizar',
            'default/pedidos/visualizar-deposito',
            'default/pedidos/outros',
            'default/pedidos/cancelados',
            'default/pedidos/edit',
            'default/pedidos/edit-adesao',
            'default/pedidos/visualizar-boleto',
            'default/dados-usuario/blocos/endereco',
            'default/dados-usuario/create',
            'default/dados-usuario/dados-bancarios/create',
            'default/dados-usuario/pessoais',
            'default/dados-usuario/endereco',
            'default/dados-usuario/show',
            'default/dados-usuario/edit',
            'default/boletos/create',
            'default/boletos/edit',
            'default/usar-gmilhas/interna',
            'default/rentabilidade/create',
            'default/rentabilidade/viewer',
            'default/rentabilidade/edit',
            'default/home-admin',
            'default/sistema/edit',
            'default/transferencias/em_liquidacao',
            'default/transferencias/index',
            'default/transferencias/create',
            'default/transferencias/create-transferencia-liberty',
            'default/transferencias/todos',
            'default/transferencias/destinatario-valor',
            'default/transferencias/cancelados',
            'default/diretos/pendentes',
            'default/user/create',
            'default/user/pendentes',
            'default/user/edit',
            'default/metodo_pagamento/create',
            'default/metodo_pagamento/edit',
            'default/relatorios/pagamentos',
            'default/tipo_pedidos/edit',
            'default/reservas/visualizar',
            'default/contratos/milhas',
            'default/contratos/edit',
            'default/titulos/index',
            'default/titulos/create',
            'default/titulos/edit',
            'default/configuracao_bonus/create',
            'default/configuracao_bonus/edit',
            'default/pedidos_movimentos/index',
            'default/pedidos_movimentos/item',
            'default/pedidos_movimentos/item-interna',
            'default/pedidos_movimentos/index-new',
            'default/emails/confirmacoes/capitalizacao',
            'default/emails/confirmacoes/emailTransferenciaValorMinimo',
            'default/emails/transferencias/solicitacao-admin',
            'default/emails/reserva',
            'default/emails/cancelamento-reserva',
            'default/emails/reserva-interna',
            'default/layout/sidebar-master',
            'default/layout/sidebar/extratos',
            'default/deposito/depositos',
            'default/deposito/create',
            'default/deposito/visualizar',
            'default/extrato/direto',
            'default/extrato/equiparacao',
            'default/extrato/financeiro',
            'default/extrato/royalties',
            'default/extrato/milhas',
            'default/extrato/royalties-pagos',
            'default/rentabilidade_historico/index',
            'default/rentabilidade_historico/create',
            'default/rentabilidade_historico/edit',
            'default/pagamentos/verificar-pagamento',
            'auth/register',
            'auth/login',
            '*',
        ], 'App\Http\ViewComposers\ConfiguracaoSistema');

        View::composer([
            '*.layout.sidebar-associado',
        ], 'App\Http\ViewComposers\DadosEmpresa');

        View::composer('*.layout.main', 'App\Http\ViewComposers\AllViewsBackend');
        View::composer(
            [
                'auth.*',
                '*.layout.header',
                '*.home',
                '*.emails.password',
                '*.emails.*',
                'errors.503',
            ], 'App\Http\ViewComposers\AuthViews');

        View::composer(
            [
                '*.layout.footer',
            ], 'App\Http\ViewComposers\ModalViews');

        View::composer('default.layout.sidebar.download', 'App\Http\ViewComposers\ViewDownload');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
