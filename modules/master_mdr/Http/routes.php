<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

    Route::group(['middleware' => ['auth', 'cadastroOK']], function () {
        Route::get('relatorio/pedidos.pagos', ['as' => 'relatorio.pedidos.pagos', 'uses' => 'RelatoriosController@pedidosPagos']);
        Route::post('relatorio/pedidos.pagos', ['as' => 'relatorio.pedidos.pagos', 'uses' => 'RelatoriosController@relatorioPedidosPagos']);
        Route::get('relatorio/saques', ['as' => 'relatorio.saques', 'uses' => 'RelatoriosController@saques']);
        Route::post('relatorio/saques', ['as' => 'relatorio.saques', 'uses' => 'RelatoriosController@relatorioSaques']);
        Route::get('relatorio/depositos', ['as' => 'relatorio.depositos', 'uses' => 'RelatoriosController@depositos']);
        Route::post('relatorio/depositos', ['as' => 'relatorio.depositos', 'uses' => 'RelatoriosController@relatorioDepositos']);
    });
