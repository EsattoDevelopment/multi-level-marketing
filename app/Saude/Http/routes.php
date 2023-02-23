<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

Route::group(['middleware' => ['auth']], function () {
    Route::get('procedimentos/index', ['as' => 'procedimentos.json', 'uses' => 'ProcedimentosController@indexJson']);
    Route::get('procedimentos/{procedimento}/recovery', ['as' => 'saude.procedimentos.recovery', 'uses' => 'ProcedimentosController@recovery']);
    Route::resource('procedimentos', 'ProcedimentosController');

    Route::get('procedimentos/clinica/{user}/index', ['as' => 'saude.procedimentos_clinica.index', 'uses' => 'ProcedimentosClinicasController@index']);
    Route::get('procedimentos/clinica/{user}/{procedimento}/edit', ['as' => 'saude.procedimentos_clinica.edit', 'uses' => 'ProcedimentosClinicasController@edit']);
    Route::put('procedimentos/clinica/{user}/{procedimento}/update', ['as' => 'saude.procedimentos_clinica.update', 'uses' => 'ProcedimentosClinicasController@update']);

    Route::get('procedimentos/from/clinica', ['as' => 'saude.procedimentos_clinica.from.clinica', 'uses' => 'ProcedimentosClinicasController@getFromClinica']);

    Route::get('dependentes/busca', ['as' => 'saude.dependentes.busca', 'uses' => 'DependenteController@busca']);
    Route::get('dependentes/{user}/index', ['as' => 'saude.dependentes.index', 'uses' => 'DependenteController@index']);
    Route::post('dependentes/{user}/store', ['as' => 'saude.dependentes.store', 'uses' => 'DependenteController@store']);
    Route::get('dependentes/{user}/create', ['as' => 'saude.dependentes.create', 'uses' => 'DependenteController@create']);
    Route::put('dependentes/{user}/update/{dependentes}', ['as' => 'saude.dependentes.update', 'uses' => 'DependenteController@update']);
    Route::get('dependentes/{user}/edit/{dependentes}', ['as' => 'saude.dependentes.edit', 'uses' => 'DependenteController@edit']);
    Route::delete('dependentes/{user}/destroy/{dependentes}', ['as' => 'saude.dependentes.destroy', 'uses' => 'DependenteController@destroy']);
    Route::get('verificar-mensalidade-contratos', ['as' => 'saude.verificar_mensalidade_contratos', 'uses' => 'SistemaController@verificar_mensalidade_contratos']);

    Route::resource('especialidade', 'EspecialidadeController');
    Route::get('especialidade/get/all', ['as' => 'saude.especialidade.all', 'uses' => 'EspecialidadeController@getAll']);
    Route::get('especialidade/{especialidade}/delete', ['as' => 'saude.especialidade.delete', 'uses' => 'EspecialidadeController@delete']);
    Route::get('especialidade/{especialidade}/recovery', ['as' => 'saude.especialidade.recovery', 'uses' => 'EspecialidadeController@recovery']);

    Route::resource('exames', 'ExameController');
    Route::get('exames/from/usuario', ['as' => 'saude.exames.from.user', 'uses' => 'ExameController@getFromUser']);
    Route::get('exames/get/all', ['as' => 'saude.exames.all', 'uses' => 'ExameController@getAll']);
    Route::get('exames/{exame}/delete', ['as' => 'saude.exames.delete', 'uses' => 'ExameController@delete']);
    Route::get('exames/{exame}/recovery', ['as' => 'saude.exames.recovery', 'uses' => 'ExameController@recovery']);

    Route::get('medicos/get/all/disabled', ['as' => 'saude.medicos.all.disabled', 'uses' => 'MedicoController@getAllDisabled']);
    Route::get('medicos/get/all', ['as' => 'saude.medicos.all', 'uses' => 'MedicoController@getAll']);
    Route::get('medicos/disabled', ['as' => 'saude.medicos.disabled', 'uses' => 'MedicoController@desativados']);
    Route::get('medicos/{exame}/delete', ['as' => 'saude.medicos.delete', 'uses' => 'MedicoController@delete']);
    Route::get('medicos/{exame}/recovery', ['as' => 'saude.medicos.recovery', 'uses' => 'MedicoController@recovery']);
    Route::get('medicos/index/clinica', ['as' => 'saude.medicos.index.clinica', 'uses' => 'MedicoController@indexClinica']);
    Route::resource('medicos', 'MedicoController');

    Route::get('guias/cancelados', ['as' => 'saude.guias.canceladas', 'uses' => 'GuiaController@desativados']);
    Route::get('guias/{guia}/retorno', ['as' => 'saude.guias.retorno', 'uses' => 'GuiaController@retorno']);
    Route::get('guias/autorizadas', ['as' => 'saude.guias.autorizadas', 'uses' => 'GuiaController@autorizadas']);
    Route::get('guias/aguardando', ['as' => 'saude.guias.aguardando', 'uses' => 'GuiaController@aguardando']);
    Route::get('guias/get/all', ['as' => 'saude.guias.all', 'uses' => 'GuiaController@getAll']);
    Route::get('guias/get/aguardando', ['as' => 'saude.guias.json.aguardando', 'uses' => 'GuiaController@getAguardando']);
    Route::get('guias/get/autorizadas', ['as' => 'saude.guias.json.autorizadas', 'uses' => 'GuiaController@getAutorizadas']);
    Route::get('guias/get/all/disabled', ['as' => 'saude.guias.all.disabled', 'uses' => 'GuiaController@getAllDisabled']);
    Route::get('guias/{guia}/imprimir', ['as' => 'saude.guias.imprimir', 'uses' => 'GuiaController@imprimir']);
    Route::get('guias/{guia}/autorizar', ['as' => 'saude.guias.autorizar', 'uses' => 'GuiaController@autorizar']);
    Route::get('guias/{guia}/delete', ['as' => 'saude.guias.delete', 'uses' => 'GuiaController@delete']);
    Route::get('guias/{guia}/recovery', ['as' => 'saude.guias.recovery', 'uses' => 'GuiaController@recovery']);
    Route::resource('guias', 'GuiaController');

    /*        Route::group(['prefix' => 'api/'], function () {
                Route::get('paciente/busca', ['as' => 'api.paciente.busca', 'uses' => 'ApiController@paciente']);
            });*/
});
