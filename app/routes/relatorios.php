<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

    Route::group(['middleware' => ['auth', 'cadastroOK']], function () {

        // relatorios
        Route::get('relatorio/colaboradores/{id}', ['as' => 'relatorio.colaboradores', 'uses' => 'RelatoriosController@relatorioColaboradores']);
        Route::get('relatorio/pagamentos', ['as' => 'relatorio.pagamento', 'uses' => 'RelatoriosController@pagamentos']);
        Route::get('relatorio/recebimentos', ['as' => 'relatorio.pagamento-diarios', 'uses' => 'RelatoriosController@pagamentosDiarios']);
        Route::post('relatorio/recebimentos', ['as' => 'relatorio.pagamento-diarios', 'uses' => 'RelatoriosController@relatorioPagamentosDiarios']);
        Route::get('relatorio/inadimplentes', ['as' => 'relatorio.inadimplentes', 'uses' => 'RelatoriosController@inadimplentes']);
        Route::get('relatorio/usuarios/inadimplentes', ['as' => 'relatorio.usuarios.inadimplentes', 'uses' => 'RelatoriosController@relatorioUsuariosInadimplentes']);
        Route::get('relatorio/usuarios', ['as' => 'relatorio.usuarios', 'uses' => 'RelatoriosController@user']);
        Route::post('relatorio/usuarios', ['as' => 'relatorio.usuarios', 'uses' => 'RelatoriosController@relatorioUser']);
        /*Route::get('relatorio/faturamento', ['as' => 'relatorio.faturamento', 'uses' => 'RelatoriosController@faturamento']);
        Route::post('relatorio/faturamento', ['as' => 'relatorio.faturamento', 'uses' => 'RelatoriosController@relatorioFaturamento']);*/
        Route::get('relatorio/contratos', ['as' => 'relatorio.contratos', 'uses' => 'RelatoriosController@contratos']);
        Route::post('relatorio/contratos', ['as' => 'relatorio.contratos', 'uses' => 'RelatoriosController@relatorioContratos']);
        Route::get('relatorio/consultor', ['as' => 'relatorio.consultor', 'uses' => 'RelatoriosController@consultor']);
        Route::post('relatorio/consultor', ['as' => 'relatorio.consultor', 'uses' => 'RelatoriosController@relatorioConsultor']);
    });
