<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

Route::get('plataforma/contas', 'PlataformaContaController@contas');
Route::get('user/diretos', 'RedeController@diretos');
Route::get('info/pagamento/user/{user_id}/deposito/{pedido_id}', ['as' => 'taxas.pagamento', 'uses' => 'TaxasPagamentoController@astroPayCard']);

    Route::group(['middleware' => ['auth', 'cadastroOK']], function () {
        Route::group(['as' => 'api.'], function () {
            Route::get('user/busca', ['as' => 'user.busca', 'uses' => 'UserController@apiBusca']);
            Route::get('clinica/busca', ['as' => 'clinica.busca', 'uses' => 'UserController@apiBuscaClinica']);
            Route::get('empresa/busca', ['as' => 'empresa.busca', 'uses' => 'UserController@apiBuscaEmpresa']);

            Route::get('item/busca', ['as' => 'item.busca', 'uses' => 'ItensController@apiBusca']);
            Route::post('item/order', ['as' => 'item.order', 'uses' => 'ItensController@order']);

            Route::get('consultor/busca', ['as' => 'consultor.busca', 'uses' => 'UserController@apiBuscaConsultor']);
            Route::get('guias/paciente/busca', ['as' => 'paciente.busca', 'uses' => 'UserController@apiBuscaGuia']);
            Route::get('procedimentos/busca', ['as' => 'procedimentos.busca', 'uses' => '\App\Saude\Http\Controllers\ProcedimentosController@apiBusca']);

            // Pedidos
            Route::get('depositos/pagos', ['as' => 'depositos.pagos', 'uses' => 'PedidoController@depositosPagos']);
            Route::get('contratos/capital/ativos', ['as' => 'contratos.capital.ativos', 'uses' => 'PedidoController@contratosCapitalAtivos']);
            Route::get('contratos/capital/finalizados', ['as' => 'contratos.capital.finalizados', 'uses' => 'PedidoController@contratosCapitalFinalizados']);
        });

        Route::get('user/index/json/inadimplente', ['as' => 'user.index.json.inadimplente', 'uses' => 'UserController@getUsersInadimplente']);
        Route::get('user/index/json/finalizado', ['as' => 'user.index.json.finalizado', 'uses' => 'UserController@getUsersFinalizado']);
        Route::get('user/index/json/inativo', ['as' => 'user.index.json.inativo', 'uses' => 'UserController@getUsersInativo']);
        Route::get('user/index/json/disabled', ['as' => 'user.index.json.disabled', 'uses' => 'UserController@getUsersDisabled']);
        Route::get('user/index/json/consultor', ['as' => 'user.index.json.consultor', 'uses' => 'UserController@getUsersConsultor']);
        Route::get('user/index/json/clinica', ['as' => 'user.index.json.clinica', 'uses' => 'UserController@getUsersClinica']);
        Route::get('user/index/json/aprovacao/doc', ['as' => 'user.index.json.aprovacao.doc', 'uses' => 'UserController@getUsersAprovacaoDoc']);
        Route::get('user/index/json', ['as' => 'user.index.json', 'uses' => 'UserController@getUsers']);

        Route::get('deposito/extrato', ['as' => 'deposito.extrato.json', 'uses' => '\App\Http\Controllers\PedidosMovimentosController@getMovimento']);

        Route::get('extrato/unilevel', ['as' => 'extrato.equipe.json', 'uses' => '\App\Http\Controllers\ExtratoController@getExtratoPontosEquipe']);
        Route::get('extrato/pessoais', ['as' => 'extrato.pessoais.json', 'uses' => '\App\Http\Controllers\ExtratoController@getExtratoPessoais']);

        Route::post('transferencia/validar', ['as' => 'transferencia.validate.2fa', 'uses' => 'Validacao@validate2fa']);
        Route::post('recontratacao/validar', ['as' => 'recontratacao.validate.2fa', 'uses' => 'Validacao@validate2fa']);
        Route::post('contratacao/validar', ['as' => 'contratacao.validate.2fa', 'uses' => 'Validacao@validate2fa']);
    });
