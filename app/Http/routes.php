<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

Route::group(['namespace' => 'App\Saude\Http\Controllers', 'middleware' => ['auth', 'cadastroOK']], function () {
    Route::get('contrato/consultor/{user}/impressao', ['as' => 'contrato.impressao.consultor', 'uses' => 'ContratoImpressaoController@impressaoConsultor']);
    Route::get('contrato/{contrato}/impressao', ['as' => 'contrato.impressao', 'uses' => 'ContratoImpressaoController@impressao']);
});

    Route::get('contratos/{contrato}/cancelar/dentro-prazo', ['as' => 'contratos.cancelar.dentro-prazo', 'uses' => 'ContratosController@cancelarDentro']);

    Route::group(['namespace' => 'App\Http\Controllers'], function () {
        Route::get('', function () {
            return redirect()->route('home');
        });
        //Route::get('empresa/users', ['as' => 'empresa.users', 'uses' => 'UserController@empresaUsers']);

        Route::get('auth/login', ['as' => 'auth.login', 'uses' => 'Auth\AuthController@getLogin']);
        Route::post('auth/login', ['as' => 'auth.login', 'uses' => 'Auth\AuthController@postLogin']);
        Route::get('auth/logout', ['as' => 'auth.logout', 'uses' => 'Auth\AuthController@getLogout']);

        // Registration routes...
        Route::get('ev/{conta}', ['as' => 'ev.indicador', 'uses' => 'Auth\AuthController@getRegisterIndicador']);
        Route::get('auth/register/{conta}', ['as' => 'auth.register.indicador', 'uses' => 'Auth\AuthController@getRegisterIndicador']);
        Route::get('auth/register', ['as' => 'auth.register', 'uses' => 'Auth\AuthController@getRegister']);
        Route::post('auth/register', ['as' => 'auth.register', 'uses' => 'Auth\AuthController@postRegister']);

        // Password reset link request routes...
        Route::get('password/email', 'Auth\PasswordController@getEmail');
        Route::post('password/email', 'Auth\PasswordController@postEmail');

        // Password reset routes...
        Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
        Route::post('password/reset', 'Auth\PasswordController@postReset');

        Route::post('user/indicador', ['as' => 'user.indicador', 'uses' => 'UserController@indicador']);

        Route::get('termo', ['as' => 'termo.download', 'uses' => 'EmpresaController@termo']);

        // Authentication 2 factor
        Route::get('/2fa/validate', ['as' => '2fa.validate', 'uses' => 'Auth\AuthController@getValidateToken']);
        Route::post('/2fa/validate', ['as' => '2fa.validate', 'uses' => 'Auth\AuthController@postValidateToken']);

        // AUTHENTICATION REQUIRED
        Route::group(['middleware' => ['auth', 'cadastroOK']], function () {
            Route::get('logs', ['middleware' => ['permission:master'], 'uses' => '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index']);

            // Authentication 2 factor
            Route::get('/2fa/enable', ['as' => '2fa.enable', 'uses' => 'Google2FAController@enableTwoFactor']);
            Route::post('/2fa/enable', ['as' => '2fa.enable', 'uses' => 'Google2FAController@verifyEnabledTwoFactor']);
            Route::get('/2fa/disable', ['as' => '2fa.disable', 'uses' => 'Google2FAController@disableTwoFactor']);
            Route::post('/2fa/disable', ['as' => '2fa.disable', 'uses' => 'Google2FAController@verifyDisabledTwoFactor']);

            // Permissões
            Route::resource('permission', 'PermissionController');
            Route::get('permission/delete/{permission}', ['as' => 'permission.delete', 'uses' => 'PermissionController@delete']);
            Route::get('permission/recovery/{permission}', ['as' => 'permission.recovery', 'uses' => 'PermissionController@recovery']);

            // Regras
            Route::resource('role', 'RoleController');
            Route::get('role/delete/{role}', ['as' => 'role.delete', 'uses' => 'RoleController@delete']);
            Route::get('role/recovery/{role}', ['as' => 'role.recovery', 'uses' => 'RoleController@recovery']);

            // faixasCep
            Route::resource('user/{user}/faixas-cep', 'FaixasCepController');

            // Usuários

            //empresa
            Route::get('user/logar/como/{id}', ['as' => 'user.logar.como', 'uses' => 'UserController@logarComo']);
            Route::post('user/logar/back', ['as' => 'user.logar.back', 'uses' => 'UserController@logarComoBack']);
            Route::get('user/empresa/json', ['as' => 'user.empresa.json', 'uses' => 'UserController@empresaUsers']);
            Route::get('user/empresa/{id}', ['as' => 'user.empresa.id', 'uses' => 'UserController@indexEmpresaId']);
            Route::get('user/colaboradores/{id}', ['as' => 'user.colaboradores.id', 'uses' => 'UserController@colaboradores']);
            Route::get('user/empresa', ['as' => 'user.empresa', 'uses' => 'UserController@indexEmpresa']);
            //Route::post('user/empresa', ['as' => 'user.store.empresa', 'uses' => 'UserController@storeEmpresa']);
            Route::get('user/create/empresa', ['as' => 'user.create.empresa', 'uses' => 'UserController@createEmpresa']);
            Route::get('empresa/user/{user}/edit', ['as' => 'empresa.user.edit', 'uses' => 'UserController@editEmpresa']);
            //Route::put('user/{user}/empresa', ['as' => 'user.update.empresa', 'uses' => 'UserController@updateEmpresa']);

            Route::get('user/pendentes', ['as' => 'user.pendentes', 'uses' => 'UserController@pendentes']);
            Route::get('user/diretos', ['as' => 'user.diretos', 'uses' => 'UserController@diretos']);
            Route::get('user/rede-binaria', ['as' => 'user.rede-binaria', 'uses' => 'UserController@redeBinariaIndex']);
            Route::post('user/rede-binaria', ['as' => 'user.rede-binaria', 'uses' => 'UserController@redeBinaria']);

            Route::get('user/{user}/pedefinir/{direto}/{lado}', ['as' => 'user.predefinir.equipe', 'uses' => 'UserController@predefinirEquipe']);
            //Route::get('user/pendentes', ['as' => 'user.pendentes', 'uses' => 'UserController@pendentes']);

            Route::get('user/index/disabled', ['as' => 'user.disabled', 'uses' => 'UserController@disabled']);
            Route::get('user/index/inativo', ['as' => 'user.inativo', 'uses' => 'UserController@inativo']);
            Route::get('user/index/inadimplente', ['as' => 'user.inadimplente', 'uses' => 'UserController@inadimplente']);
            Route::get('user/index/finalizado', ['as' => 'user.finalizado', 'uses' => 'UserController@finalizado']);
            Route::get('user/index/consultor', ['as' => 'user.consultor', 'uses' => 'UserController@consultor']);
            Route::get('user/index/clinicas', ['as' => 'user.clinica', 'uses' => 'UserController@clinica']);
            Route::get('user/index/aprovacao/documento', ['as' => 'user.aprovacao.doc', 'uses' => 'UserController@aprovacaoDoc']);
            Route::post('user/aprovar/doc', ['as' => 'user.aprovar.doc', 'uses' => 'UserController@aprovarDoc']);
            Route::resource('user', 'UserController');
            Route::get('user/delete/{user}', ['as' => 'user.delete', 'uses' => 'UserController@delete']);
            Route::get('user/recovery/{user}', ['as' => 'user.recovery', 'uses' => 'UserController@recovery']);
            Route::get('clinicas/medicos', ['as' => 'clinica.medicos', 'uses' => 'UserController@apiBuscaMedicos']);
            Route::get('users/titulos/update/visualizar', ['as' => 'user.verificar.update.titulo', 'uses' => 'UserController@verificarUpdateTitulos']);
            Route::get('user/{user}/titulo/update', ['as' => 'user.update.titulo', 'uses' => 'UserController@updateUserTitulo']);
            Route::get('users/titulos/update/all', ['as' => 'user.update.titulo.all', 'uses' => 'UserController@updateUserTituloAll']);

            // boletos
            Route::get('boletos/mensalidades', ['as' => 'boletos.mensalidades', 'uses' => 'BoletosController@mensalidades']);
            Route::get('boletos/mensalidades/json', ['as' => 'boletos.mensalidades.json', 'uses' => 'BoletosController@getBoletosMensalidades']);

            Route::get('boletos/adesoes', ['as' => 'boletos.adesoes', 'uses' => 'BoletosController@adesoes']);
            Route::get('boletos/adesoes/json', ['as' => 'boletos.adesoes.json', 'uses' => 'BoletosController@getBoletosAdesoes']);

            //metodo_pagamento
            Route::resource('metodo_pagamento', 'MetodoPagamentoController');
            Route::get('metodo_pagamento/inativar/{user}', ['as' => 'metodo_pagamento.inativar', 'uses' => 'MetodoPagamentoController@inativar']);
            //Route::get('image/{path}', ['as' => 'getcomprovante', 'uses' => 'MetodoPagamentoController@viewComprovante']);
            //Route::get('titulo/recovery/{user}', ['as' => 'titulo.recovery', 'uses' => 'TitulosController@recovery']);

            //pagamento
            //Route::resource('verificar-pagamento', 'PagamentosController');
            Route::post('pedido/confirmar-transacao', ['as' => 'pedido.confirmarTransactionGatewayPagamento', 'uses' => 'PagamentosController@confirmarTransactionGatewayPagamento']);
            Route::post('pedido/verificar-pagamento', ['as' => 'pedido.verificar-pagamento', 'uses' => 'PagamentosController@verificarPagamento']);
            Route::post('pedido/pagar-com-boleto', ['as' => 'pedido.pagar.com.boleto', 'uses' => 'PagamentosController@pagarComBoleto']);

            //Pagamento com Paypal
            Route::post('paypal/pagamento', ['as' => 'paypal.pagar', 'uses' => 'PaypalController@pagarComPayPal']);
            Route::get('paypal/pedido/{pedido_id}/status', ['as' => 'paypal.status', 'uses' => 'PaypalController@statusPagamento']);

            //Pagamento com AstroPay Card
            Route::post('astropaycard/pagamento', ['as' => 'astropaycard.pagar', 'uses' => 'AstroPayCardController@pagar']);

            // titulos
            Route::resource('titulo', 'TitulosController');
            Route::get('titulo/delete/{user}', ['as' => 'titulo.delete', 'uses' => 'TitulosController@delete']);
            Route::get('titulo/recovery/{user}', ['as' => 'titulo.recovery', 'uses' => 'TitulosController@recovery']);

            // titulos
            Route::resource('configuracao-bonus', 'ConfiguracaoBonusController');
            /*Route::get('titulo/delete/{user}', ['as' => 'titulo.delete', 'uses' => 'TitulosController@delete']);
            Route::get('titulo/recovery/{user}', ['as' => 'titulo.recovery', 'uses' => 'TitulosController@recovery']);*/

            // Dados usuario
            Route::get('dados-usuario', ['as' => 'dados-usuario.show', 'uses' => 'DadosUsuariosController@show']);
            Route::put('dados-usuario', ['as' => 'dados-usuario.update', 'uses' => 'DadosUsuariosController@update']);
            Route::post('dados-usuario', ['as' => 'dados-usuario.store', 'uses' => 'DadosUsuariosController@store']);
            Route::post('dados-usuario/imagem', ['as' => 'dados-usuario.imagem', 'uses' => 'DadosUsuariosController@storeImagem']);

            Route::get('dados-usuario/endereco', ['as' => 'dados-usuario.endereco', 'uses' => 'DadosUsuariosController@endereco']);
            Route::put('dados-usuario/endereco', ['as' => 'dados-usuario.endereco.update', 'uses' => 'DadosUsuariosController@updateEndereco']);

            Route::get('dados-usuario/pessoais', ['as' => 'dados-usuario.pessoais', 'uses' => 'DadosUsuariosController@pessoais']);
            Route::put('dados-usuario/pessoais', ['as' => 'dados-usuario.pessoais.update', 'uses' => 'DadosUsuariosController@updatePessoais']);

            Route::get('dados-usuario/enviar-documentos', ['as' => 'dados-usuario.identificacao', 'uses' => 'DadosUsuariosController@identificacao']);
            Route::post('dados-usuario/enviar-documentos', ['as' => 'dados-usuario.identificacao.store', 'uses' => 'DadosUsuariosController@storeImagemDocumentos']);

            Route::get('dados-usuario/seguranca', ['as' => 'dados-usuario.seguranca', 'uses' => 'DadosUsuariosController@seguranca']);
            Route::put('dados-usuario/seguranca', ['as' => 'dados-usuario.seguranca.update', 'uses' => 'DadosUsuariosController@updateSeguranca']);

            Route::get('dados-usuario/dados-bancarios', ['as' => 'dados-usuario.dados-bancarios', 'uses' => 'DadosUsuariosBancariosController@index']);
            Route::get('dados-usuario/dados-bancarios/create', ['as' => 'dados-usuario.dados-bancarios-create', 'uses' => 'DadosUsuariosBancariosController@create']);
            Route::post('dados-usuario/dados-bancarios/store', ['as' => 'dados-usuario.dados-bancarios-store', 'uses' => 'DadosUsuariosBancariosController@store']);
            Route::delete('dados-usuario/dados-bancarios/{id}/destroy', ['as' => 'dados-usuario.dados-bancarios-destroy', 'uses' => 'DadosUsuariosBancariosController@destroy']);
            Route::post('dados-usuario/dados-bancarios/comprovante', ['as' => 'dados-usuario.dados-bancarios-comprovante', 'uses' => 'DadosUsuariosBancariosController@comprovante']);

            // Empresa
            Route::resource('empresa', 'EmpresaController');
            Route::resource('contas_empresa', 'ContasEmpresaController');

            Route::resource('sistema', 'SistemaController', [
                'except' => ['index', 'create', 'show'],
            ]);

            // Itens
            Route::resource('item', 'ItensController');
            Route::get('item/{item}/delete', ['as' => 'item.delete', 'uses' => 'ItensController@delete']);
            Route::get('item/{item}/recovery', ['as' => 'item.recovery', 'uses' => 'ItensController@recovery']);

            // Boleto
            Route::get('boleto', ['as' => 'boleto.retorno', 'uses' => 'BoletoRetornoController@retorno']);
            Route::post('boleto/processar', ['as' => 'boleto.processa.retorno', 'uses' => 'BoletoRetornoController@processarRetorno']);

            // movimento
            Route::resource('movimento', 'MovimentoController');

            // movimento binário
            Route::get('movimento/binario/create', ['as' => 'movimento.binario.create', 'uses' => 'MovimentoBinarioController@create']);
            Route::post('movimento/binario', ['as' => 'movimento.binario.store', 'uses' => 'MovimentoBinarioController@store']);

            // Pedidos
            Route::get('pedido/comprovantes/{pedido}/{nomeArquivo}', ['as' => 'pedido.comprovante', 'uses' => 'PedidoController@download']);
            Route::get('pedido/bonus/{pedido}', ['as' => 'pedido.bonus.visualizar', 'uses' => 'PedidoController@bonusVisualizar']);
            Route::get('pedido/interna/{pedido}', ['as' => 'pedido.interna', 'uses' => 'PedidoController@interna']);
            Route::get('pedido/bonus', ['as' => 'pedido.bonus', 'uses' => 'PedidoController@bonus']);
            Route::post('pedido/bonus', ['as' => 'pedido.bonus', 'uses' => 'PedidoController@bonus']);
            Route::get('pedido/pagos/json', ['as' => 'pedido.pagos.json', 'uses' => 'PedidoController@pagosJson']);
            Route::get('pedido/pagos', ['as' => 'pedido.pagos', 'uses' => 'PedidoController@pagos']);
            Route::get('pedido/cancelados', ['as' => 'pedido.cancelados', 'uses' => 'PedidoController@cancelados']);
            Route::get('pedido/aguardando-pagamento', ['as' => 'pedido.aguardando-pagamento', 'uses' => 'PedidoController@aguardandoPagamento']);
            Route::get('pedido/aguardando-confirmacao', ['as' => 'pedido.aguardando-confirmacao', 'uses' => 'PedidoController@aguardandoConfirmacao']);
            Route::get('deposito/{pedido}/boleto/visualizar/{msg}', ['as' => 'pedido.boleto.visualizar.dados', 'uses' => 'PedidoController@visualizarBoleto']);
            Route::get('pedido/usuario/{user}/visualizar/{pedido}', ['as' => 'pedido.usuario.pedido', 'uses' => 'PedidoController@visualizarPedido']);
            Route::get('deposito/usuario/{user}/visualizar/{pedido}', ['as' => 'deposito.usuario', 'uses' => 'DepositoController@visualizarDeposito']);

            Route::get('pedido/normal/aguardando-pagamento', ['as' => 'pedidos.normal.aguardando.pagamento', 'uses' => 'PedidoController@normalAguardandoPagamento']);
            Route::get('pedido/normal/aguardando-confirmacao', ['as' => 'pedidos.normal.aguardando.confirmacao', 'uses' => 'PedidoController@normalAguardandoConfirmacao']);
            Route::get('pedido/normal/cancelados', ['as' => 'pedidos.normal.cancelados', 'uses' => 'PedidoController@nomalCancelados']);

            // contratos de capital
            Route::get('contratos/capital/ativos', ['as' => 'contratos.capital.ativos', 'uses' => 'ContratosCapitalController@ativos']);
            Route::get('contratos/capital/finalizados', ['as' => 'contratos.capital.finalizados', 'uses' => 'ContratosCapitalController@finalizados']);

            Route::post('pedido/usuario/{user}/pagar/{pedido}/boleto', ['as' => 'pedido.usuario.pedido.pagar', 'uses' => 'PagamentosController@pagarPedido']);

            //confirmaação de TED
            Route::post('pedido/usuario/confirmar-ted/pedido/{pedido}', ['as' => 'pedido.usuario.pedido.confirmar.ted', 'uses' => 'PagamentosController@confirmarTed']);

            Route::post('pedido/usuario/{user}/pagar/{pedido}/boleto', ['as' => 'pedido.usuario.pedido.pagar.boleto', 'uses' => 'PagamentosController@pagarPedido']);
            Route::post('pedido/usuario/{user}/pagar/{pedido}/boleto', ['as' =>   'pedido.usuario.pedido.pagar.boleto', 'uses' => 'PagamentosController@pagarPedido']);

            Route::get('pedido/usuario/{user}/abrir/{pedido}/boleto', ['as' => 'pedido.usuario.pedido.abrir.boleto', 'uses' => 'PagamentosController@pagarPedido']);
            Route::post('pedido/usuario/{user}/pagar/{pedido}/saldo', ['as' => 'pedido.usuario.pedido.pagar.saldo', 'uses' => 'PagamentosController@pagarPedido']);
            Route::get('pedido/agente', ['as' => 'pedido.consultor', 'uses' => 'PedidoController@consultor']);

            Route::post('pedido/modorecontratacao', ['as' => 'pedido.modorecontratacao', 'uses' => 'PedidoController@modoRecontratacao']);

            Route::resource('pedido', 'PedidoController');

            Route::get('transferencia/todos', ['as' => 'transferencia.todos', 'uses' => 'TransferenciaController@todos']);
            Route::get('transferencia/em-liquicacao', ['as' => 'transferencia.em_liquidacao', 'uses' => 'TransferenciaController@em_liquidacao']);
            Route::get('transferencia/cancelados', ['as' => 'transferencia.cancelados', 'uses' => 'TransferenciaController@cancelados']);
            Route::get('transferencia/{id}/efetivar', ['as' => 'transferencia.efetivar', 'uses' => 'TransferenciaController@efetivar']);
            Route::get('transferencia/{id}/cancelar', ['as' => 'transferencia.cancelar', 'uses' => 'TransferenciaController@cancelar']);
            Route::get('transferencia/contas/internas', ['as' => 'transferencia.liberty', 'uses' => 'TransferenciaController@createTransferenciaLiberty']);
            //Route::post('transferencia/liberty/create', ['as' => 'transferencia.liberty.create', 'uses' => 'TransferenciaController@createTransferenciaLiberty']);
            Route::get('transferencia/destinatario', ['as' => 'transferencia.destinatario', 'uses' => 'TransferenciaController@destinatario']);
            //Route::get('transferencia/destinatario/valor', ['as' => 'transferencia.destinatario.valor', 'uses' => 'TransferenciaController@destinatarioValor']);
            Route::resource('transferencia', 'TransferenciaController');

            Route::get('portfolio', ['as' => 'portfolio.lista', 'uses' => 'PedidoController@create']);
            Route::post('portfolio/contratar', ['as' => 'portfolio.contratar', 'uses' => 'PedidoController@novoContrato']);

            // Empréstimos
            Route::get('emprestimos', ['as' => 'emprestimos', 'uses' => 'EmprestimosController@index']);
            Route::get('emprestimos/simular', ['as' => 'emprestimos.calculadora', 'uses' => 'EmprestimosController@calculadora']);
            Route::post('emprestimos/simular', ['as' => 'emprestimos.simular', 'uses' => 'EmprestimosController@simular']);
            Route::get('emprestimos/configuracoes', ['as' => 'emprestimos.configuracoes', 'uses' => 'EmprestimosController@getConfiguracoes']);
            Route::get('emprestimos/pagar', ['as' => 'emprestimos.pagar', 'uses' => 'EmprestimosController@getPagar']);
            Route::post('emprestimos/pagar', ['as' => 'emprestimos.pagar', 'uses' => 'EmprestimosController@pagar']);
            Route::post('emprestimos/atualizar-status', ['as' => 'emprestimos.atualizar-status', 'uses' => 'EmprestimosController@atualizarStatus']);

            Route::get('depositar', ['as' => 'deposito.depositar', 'uses' => 'DepositoController@create']);
            Route::post('depositar', ['as' => 'deposito.depositar.store', 'uses' => 'DepositoController@store']);

            Route::get('depositos/aguardando', ['as' => 'depositos.aguardando.deposito', 'uses' => 'DepositoController@usuarioDepositosAguardandoDeposito']);
            Route::get('depositos/conferencia', ['as' => 'depositos.aguardando.conferencia', 'uses' => 'DepositoController@usuarioDepositosAguardandoConferencia']);
            Route::get('depositos/confirmados', ['as' => 'depositos.confirmados', 'uses' => 'DepositoController@usuarioDepositosConfirmados']);
            Route::get('depositos/cancelados', ['as' => 'depositos.cancelados', 'uses' => 'DepositoController@usuarioDepositosCancelados']);

            Route::get('capitalizacao', ['as' => 'capitalizacao.index', 'uses' => 'PedidosMovimentosController@index']);
            //Route::get('capitalizacao-new', ['as' => 'capitalizacao-new.index', 'uses' => 'PedidosMovimentosController@indexNew']);
            Route::get('capitalizacao/{item}', ['as' => 'capitalizacao.item', 'uses' => 'PedidosMovimentosController@extratoItem']);
            Route::get('capitalizacao/pedido/{pedido}/item/{item}', ['as' => 'capitalizacao.pedido', 'uses' => 'PedidosMovimentosController@extratoPedido']);

            //Route::get('pedido/usuario/{user}/pagar/{pedido}', ['as' =>   'pedido.usuario.pedido.pagar', 'uses' => 'PedidoController@pagarPedido']);
            Route::get('pedidos/aguardando', ['as' => 'pedidos.aguardando.pagamento', 'uses' => 'PedidoController@usuarioPedidosAguardandoPagamento']);
            Route::get('pedidos/conferencia', ['as' => 'pedidos.aguardando.conferencia', 'uses' => 'PedidoController@usuarioPedidosAguardandoConferencia']);
            Route::get('pedidos/confirmados', ['as' => 'pedidos.confirmados', 'uses' => 'PedidoController@usuarioPedidosConfirmados']);
            Route::get('pedidos/cancelados', ['as' => 'pedidos.cancelados', 'uses' => 'PedidoController@usuarioPedidosCancelados']);

            Route::get('pedido/usuario/{user}/cancelar/{pedido}', ['as' => 'pedido.usuario.pedido.cancelar', 'uses' => 'PedidoController@cancelarPedido']);
            Route::get('pedido/usuario/{user}', ['as' => 'pedido.usuario.pedidos', 'uses' => 'PedidoController@usuarioPedidos']);
            Route::get('pedido/{pedido}/delete', ['as' => 'pedido.delete', 'uses' => 'PedidoController@delete']);
            Route::get('pedido/{pedido}/recovery', ['as' => 'pedido.recovery', 'uses' => 'PedidoController@recovery']);
            Route::get('pedido/{pedido}/contrato', ['as' => 'pedido.usuario.contrato', 'uses' => 'PedidoController@verContrato']);

            // pagamentos
            Route::post('pagamento/sistema/{pedido}', ['as' => 'pagamento.sistema', 'uses' => 'PagamentosController@pagamentoSistema']);
            Route::get('pagamento/consultor/{pedido}', ['as' => 'pagamento.sistema.consultor', 'uses' => 'PagamentosController@pagamentoPedidoConsultor']);

            //Documentos
            Route::get('documentos/associados/{id}/comprovante/{tipo}', ['as' => 'documentos.associado.ver.comprovante', 'uses' => 'DocumentosController@verComprovante']);

            Route::get('documentos/associados/nao-enviada', ['as' => 'documentos.associado.nao-enviados', 'uses' => 'DocumentosController@associadoDocNaoEnviados']);
            Route::get('documentos/associados/aguardando-aprovacao', ['as' => 'documentos.associado.aguardando', 'uses' => 'DocumentosController@associadoDocAguardando']);
            Route::get('documentos/associado/{usuario}/aguardando-aprovacao/visualizacao', ['as' => 'documentos.associado.aguardando.visualizacao', 'uses' => 'DocumentosController@associadoDocAguardandoVisualizacao']);
            Route::post('documentos/associado/aguardando-aprovacao/confirmacao', ['as' => 'documentos.associado.aguardando.confirmacao', 'uses' => 'DocumentosController@associadoDocAguardandoConfirmacao']);

            Route::get('documentos/associados/aprovados', ['as' => 'documentos.associado.aprovados', 'uses' => 'DocumentosController@associadoDocAprovados']);
            Route::get('documentos/associado/{usuario}/aprovados/visualizacao', ['as' => 'documentos.associado.aprovados.visualizacao', 'uses' => 'DocumentosController@associadoDocAprovadosVisualizacao']);
            Route::post('documentos/associado/aprovados/confirmacao', ['as' => 'documentos.associado.aprovados.confirmacao', 'uses' => 'DocumentosController@associadoDocAprovadosConfirmacao']);

            Route::get('documentos/associados/reprovados', ['as' => 'documentos.associado.reprovados', 'uses' => 'DocumentosController@associadoDocReprovados']);
            Route::get('documentos/associado/{usuario}/reprovados/visualizacao', ['as' => 'documentos.associado.reprovados.visualizacao', 'uses' => 'DocumentosController@associadoDocReprovadosVisualizacao']);
            Route::post('documentos/associado/reprovados/confirmacao', ['as' => 'documentos.associado.reprovados.confirmacao', 'uses' => 'DocumentosController@associadoDocReprovadosConfirmacao']);
            Route::resource('documentos', 'DocumentosController');

            //Rentabilidade
            Route::get('rentabilidade', ['as' => 'rentabilidade.index', 'uses' => 'RentabilidadeController@index']);
            Route::get('rentabilidade/{data}/viewer', ['as' => 'rentabilidade.viewer', 'uses' => 'RentabilidadeController@viewer']);
            Route::get('rentabilidade/create', ['as' => 'rentabilidade.create', 'uses' => 'RentabilidadeController@create']);
            Route::post('rentabilidade', ['as' => 'rentabilidade.store', 'uses' => 'RentabilidadeController@store']);
            Route::get('rentabilidade/{data}/edit', ['as' => 'rentabilidade.edit', 'uses' => 'RentabilidadeController@edit']);
            Route::post('rentabilidade/{data}', ['as' => 'rentabilidade.update', 'uses' => 'RentabilidadeController@update']);
            Route::get('rentabilidade/{data}', ['as' => 'rentabilidade.destroy', 'uses' => 'RentabilidadeController@destroy']);
            Route::get('rentabilidade/{data}/delete', ['as' => 'rentabilidade.delete', 'uses' => 'RentabilidadeController@delete']);
            Route::get('rentabilidade/{data}/recovery', ['as' => 'rentabilidade.recovery', 'uses' => 'RentabilidadeController@recovery']);
            Route::get('rentabilidade/{rentabilidade}/pagar', ['as' => 'rentabilidade.pagar', 'uses' => 'RentabilidadeController@pagar']);

            // Rentabilidade - Histórico
            Route::resource('operacao-historico', 'RentabilidadeHistoricoController');
            Route::get('operacao-historico/{id}/desativar', ['as' => 'operacao-historico.desativar', 'uses' => 'RentabilidadeHistoricoController@desativar']);
            Route::get('operacao-historico/{id}/ativar', ['as' => 'operacao-historico.ativar', 'uses' => 'RentabilidadeHistoricoController@ativar']);
            Route::get('operacao-historico/{plataforma_id}/conta/{id}', ['as' => 'operacao-historico.plataforma.create', 'uses' => 'RentabilidadeHistoricoController@create']);
            //Route::get('download/{download}/recovery', ['as' => 'download.recovery', 'uses' => 'DownloadController@recovery']);
            //Route::get('download/interno/{download}', ['as' => 'download.interno', 'uses' => 'DownloadController@downloadInterno']);

            // Modal de aviso
            Route::resource('modal', 'ModalController');
            Route::get('modal/{modal}/delete', ['as' => 'modal.delete', 'uses' => 'ModalController@delete']);
            Route::get('modal/{modal}/recovery', ['as' => 'modal.recovery', 'uses' => 'ModalController@recovery']);

            //Plataforma
            Route::resource('plataforma', 'PlataformaController');
            Route::get('plataforma/{id}/desativar', ['as' => 'plataforma.desativar', 'uses' => 'PlataformaController@desativar']);
            Route::get('plataforma/{id}/ativar', ['as' => 'plataforma.ativar', 'uses' => 'PlataformaController@ativar']);

            Route::group(['prefix' => 'plataforma/{plataforma}'], function () {
                Route::get('conta/create', ['as' => 'plataforma-conta.create', 'uses' => 'PlataformaContaController@create']);
                Route::get('conta/{id}/edit', ['as' => 'plataforma-conta.edit', 'uses' => 'PlataformaContaController@edit']);
                Route::get('contas', ['as' => 'plataforma-conta.show', 'uses' => 'PlataformaContaController@show']);
                Route::post('conta/store', ['as' => 'plataforma-conta.store', 'uses' => 'PlataformaContaController@store']);
                Route::post('conta/{id}', ['as' => 'plataforma-conta.update', 'uses' => 'PlataformaContaController@update']);
                Route::get('conta/{id}/desativar', ['as' => 'plataforma-conta.desativar', 'uses' => 'PlataformaContaController@desativar']);
                Route::get('conta/{id}/ativar', ['as' => 'plataforma-conta.ativar', 'uses' => 'PlataformaContaController@ativar']);
            });

            //Plataforma - Contas
            //Route::resource('plataforma-conta', 'PlataformaContaController');
            //Route::get('plataforma/{id}/ativar', ['as' => 'plataforma.ativar', 'uses' => 'PlataformaController@ativar']);

            // Hotels
            Route::resource('hotel', 'HotelController');

            // extrato
            Route::get('extrato/financeiro', ['as' => 'extrato.financeiro', 'uses' => 'ExtratoController@financeiro']);
            Route::get('extrato/bonus/equiparacao', ['as' => 'extrato.bonus.equiparacao', 'uses' => 'ExtratoController@equiparacao']);
            Route::get('extrato/bonus/direto', ['as' => 'extrato.bonus.direto', 'uses' => 'ExtratoController@direto']);
            Route::get('extrato/bonus/royalties', ['as' => 'extrato.bonus.royalties', 'uses' => 'ExtratoController@royalties']);
            Route::get('extrato/bonus/royalties/pagos', ['as' => 'extrato.bonus.royalties.pagos', 'uses' => 'ExtratoController@royaltiesPagos']);
            Route::get('extrato/saldoUsuarios/{id}', ['as' => 'extrato.saldoUsers', 'uses' => 'ExtratoController@saldoUsers']);
            Route::get('extrato/milhas', ['as' => 'extrato.milhas', 'uses' => 'ExtratoController@milhas']);
            Route::get('extrato/milhasUsuarios/{id}', ['as' => 'extrato.milhasUsers', 'uses' => 'ExtratoController@milhasUsers']);
            Route::get('extrato/pv', ['as' => 'extrato.pv', 'uses' => 'ExtratoController@pv']);
            Route::get('extrato/pvUsuarios/{id}', ['as' => 'extrato.pvUsers', 'uses' => 'ExtratoController@pvUsers']);
            Route::get('extrato/pessoais', ['as' => 'extrato.pessoais', 'uses' => 'ExtratoController@pessoais']);
            Route::get('extrato/equipe', ['as' => 'extrato.equipe', 'uses' => 'ExtratoController@equipe']);
            Route::resource('extrato', 'ExtratoController');

            //
            Route::get('produtoPago', ['as' => 'produto.pago', 'uses' => 'PagamentosController@produtoPago']);

            //videos
            Route::get('videos/index', ['as' => 'videos.index', 'uses' => 'VideosController@index']);
            Route::get('videos/show/{categoriaId}', ['as' => 'videos.show', 'uses' => 'VideosController@show']);
            Route::get('videos/create', ['as' => 'videos.create', 'uses' => 'VideosController@create']);
            Route::get('videos/{id}/edit', ['as' => 'videos.edit', 'uses' => 'VideosController@edit']);
            Route::post('videos/store', ['as' => 'videos.store', 'uses' => 'VideosController@store']);
            Route::post('videos/update/{id}/update', ['as' => 'videos.update', 'uses' => 'VideosController@update']);
            Route::get('videos/{id}/delete', ['as' => 'videos.delete', 'uses' => 'VideosController@delete']);
            Route::get('videos/{id}/recovery', ['as' => 'videos.recovery', 'uses' => 'VideosController@recovery']);
            Route::delete('videos/{id}/destroy', ['as' => 'videos.destroy', 'uses' => 'VideosController@destroy']);

            // Downloads
            Route::resource('download-tipo', 'DownloadTipoController');
            Route::get('download-tipo/{download}/delete', ['as' => 'download-tipo.delete', 'uses' => 'DownloadTipoController@delete']);
            Route::get('download-tipo/{download}/recovery', ['as' => 'download-tipo.recovery', 'uses' => 'DownloadTipoController@recovery']);

            Route::resource('download', 'DownloadController');
            Route::get('download/{download}/delete', ['as' => 'download.delete', 'uses' => 'DownloadController@delete']);
            Route::get('download/{download}/recovery', ['as' => 'download.recovery', 'uses' => 'DownloadController@recovery']);
            Route::get('download/interno/{download}', ['as' => 'download.interno', 'uses' => 'DownloadController@downloadInterno']);
            //Route::get('download/{download}/{nomeArquivo}', ['as' => 'download.download', 'uses' => 'DownloadController@download']);

            // Galerias
            Route::get('galeria/publicar', ['as' => 'galeria.public.all', 'uses' => 'GaleriaController@publicarAll']);
            Route::get('galeria/{imagem}/template-imagem', ['as' => 'galeria.template', 'uses' => 'GaleriaController@template']);
            Route::resource('galeria', 'GaleriaController');
            Route::get('galeria/{galeria}/delete', ['as' => 'galeria.delete', 'uses' => 'GaleriaController@delete']);
            Route::get('galeria/{id}/recovery', ['as' => 'galeria.recovery', 'uses' => 'GaleriaController@recovery']);
            Route::get('galeria/{galeria}/imagens', ['as' => 'galeria.imagens', 'uses' => 'GaleriaController@imagens']);
            Route::post('galeria/{galeria}/upload', ['as' => 'galeria.upload', 'uses' => 'GaleriaController@upload']);
            Route::post('galeria/imagens/{imagem}/delete', ['as' => 'galeria.deleteImg', 'uses' => 'GaleriaController@deleteImg']);
            Route::post('galeria/imagens/{imagem}/legenda', ['as' => 'galeria.legenda', 'uses' => 'GaleriaController@legenda']);
            Route::post('galeria/imagens/{imagem}/principal', ['as' => 'galeria.imagem.principal', 'uses' => 'GaleriaController@setImagemPrincipal']);
            Route::delete('galeria/{galeria}/delete-all', ['as' => 'galeria.delete.all', 'uses' => 'GaleriaController@deleteAllImg']);
            Route::get('galeria/{galeria}/publicar', ['as' => 'galeria.public', 'uses' => 'GaleriaController@publicar']);
            Route::post('galeria/order', ['as' => 'galeria.order', 'uses' => 'GaleriaController@order']);

            // Contratos
            Route::get('contratos/abertos/get', ['as' => 'contratos.abertos.get', 'uses' => 'ContratosController@getAbertos']);
            Route::get('contratos/abertos', ['as' => 'contratos.abertos', 'uses' => 'ContratosController@abertos']);
            Route::get('contratos/atrasados/get', ['as' => 'contratos.atrasados.get', 'uses' => 'ContratosController@getAtrasados']);
            Route::get('contratos/atrasados', ['as' => 'contratos.atrasados', 'uses' => 'ContratosController@atrasados']);
            Route::get('contratos/finalizando', ['as' => 'contratos.finalizando', 'uses' => 'ContratosController@finalizando']);
            Route::get('contratos/finalizado', ['as' => 'contratos.finalizado', 'uses' => 'ContratosController@finalizados']);
            Route::get('contratos/cancelados', ['as' => 'contratos.cancelados', 'uses' => 'ContratosController@cancelados']);
            Route::get('contratos/{contrato}/cancelar/dentro-prazo', ['as' => 'contratos.cancelar.dentro-prazo', 'uses' => 'ContratosController@cancelarDentro']);
            Route::get('contratos/{contrato}/cancelar/fora-prazo', ['as' => 'contratos.cancelar.fora-prazo', 'uses' => 'ContratosController@cancelarFora']);
            Route::get('contratos/{contrato}/mensalidades/gerar/', ['as' => 'contratos.mensalidades.gerar', 'uses' => 'ContratosController@mensalidadesGerar']);
            Route::get('contratos/{contrato}/mensalidades', ['as' => 'contratos.mensalidades', 'uses' => 'ContratosController@mensalidades']);
            Route::get('contratos/{contrato}/mensalidades/{mensalidade}/edit', ['as' => 'contratos.mensalidade.edit', 'uses' => 'ContratosController@mensalidadesEdit']);
            Route::post('contratos/{contrato}/mensalidades/{mensalidade}', ['as' => 'contratos.mensalidade.update', 'uses' => 'ContratosController@mensalidades']);
            Route::resource('contratos', 'ContratosController');

            Route::get('mensalidade/{mensalidade}/get', ['as' => 'mensalidade.get', 'uses' => 'MensalidadeController@getMensalidadeContrato']);
            Route::resource('mensalidade', 'MensalidadeController');

            Route::get('rede/agentes', ['as' => 'rede.agentes', 'uses' => 'MinhaRedeController@consultores']);
            Route::get('rede/agentes/inadimplente', ['as' => 'rede.agentes.inadimplente', 'uses' => 'MinhaRedeController@consultoresInadimplente']);
            Route::get('rede/contratos', ['as' => 'rede.contratos', 'uses' => 'MinhaRedeController@contratos']);
            Route::get('rede', ['as' => 'rede', 'uses' => 'MinhaRedeController@rede']);
            Route::get('rede/organograma', ['as' => 'rede.organograma', 'uses' => 'MinhaRedeController@visualizar']);

            // pacotes
            /*    Route::get('pacotes/hospedagem' , ['as' => 'pacotes.hospedagem.index', 'uses' => 'PacotesController@hospedagemIndex']);
                Route::get('pacotes/hospedagem/create' , ['as' => 'pacotes.hospedagem.create', 'uses' => 'PacotesController@hospedagemCreate']);
                Route::get('pacotes/hospedagem/{pacotes}/edit/{tipo}' , ['as' => 'pacotes.hospedagem.edit', 'uses' => 'PacotesController@hospedagemEdit']);

                Route::get('pacotes/pacote' , ['as' => 'pacotes.pacote.index', 'uses' => 'PacotesController@pacoteIndex']);
                Route::get('pacotes/pacote/create' , ['as' => 'pacotes.pacote.create', 'uses' => 'PacotesController@pacoteCreate']);
                Route::get('pacotes/pacote/{pacotes}/edit/{tipo}' , ['as' => 'pacotes.pacote.edit', 'uses' => 'PacotesController@pacoteEdit']);

                Route::get('pacotes/cruzeiro' , ['as' => 'pacotes.cruzeiro.index', 'uses' => 'PacotesController@cruzeiroIndex']);
                Route::get('pacotes/cruzeiro/create' , ['as' => 'pacotes.cruzeiro.create', 'uses' => 'PacotesController@cruzeiroCreate']);
                Route::get('pacotes/cruzeiro/{pacotes}/edit/{tipo}' , ['as' => 'pacotes.cruzeiro.edit', 'uses' => 'PacotesController@cruzeiroEdit']);

                Route::get('pacotes/cidades/{estado}' , ['as' => 'pacotes.cidades', 'uses' => 'PacotesController@getCidades']);
                Route::get('pacotes/{pacotes}/galeria', ['as' => 'pacotes.galeria', 'uses' => 'PacotesController@galeria']);
                Route::get('pacotes/{pacotes}/galeria/create', ['as' => 'pacotes.galeria.create', 'uses' => 'PacotesController@galeriaCreate']);

                Route::resource('pacotes', 'PacotesController');*/

            // Usar Gmilhas
            /*    Route::get('usar-gmilhas/hospedagem/{pacote}' , ['as' => 'usar-gmilhas.hospedagem.interna', 'uses' => 'UsarGmilhasController@hospedagemInterna']);
                Route::get('usar-gmilhas/hospedagem' , ['as' => 'usar-gmilhas.hospedagem', 'uses' => 'UsarGmilhasController@hospedagemIndex']);

                Route::get('usar-gmilhas/pacote/{pacote}' , ['as' => 'usar-gmilhas.pacote.interna', 'uses' => 'UsarGmilhasController@pacoteInterna']);
                Route::get('usar-gmilhas/pacote' , ['as' => 'usar-gmilhas.pacote', 'uses' => 'UsarGmilhasController@pacoteIndex']);

                Route::get('usar-gmilhas/cruzeiro/{pacote}' , ['as' => 'usar-gmilhas.cruzeiro.interna', 'uses' => 'UsarGmilhasController@cruzeiroInterna']);
                Route::get('usar-gmilhas/cruzeiro' , ['as' => 'usar-gmilhas.cruzeiro', 'uses' => 'UsarGmilhasController@cruzeiroIndex']);

                Route::post('usar-gmilhas/reservar' , ['as' => 'usar-gmilhas.reservar', 'uses' => 'UsarGmilhasController@reservar']);*/

            // reservas
            /*    Route::get('reservas/hospedagem' , ['as' => 'reservas.hospedagem', 'uses' => 'ReservasController@hospedagem']);
                Route::get('reservas/pacotes' , ['as' => 'reservas.pacotes', 'uses' => 'ReservasController@pacotes']);
                Route::get('reservas/cruzeiros' , ['as' => 'reservas.cruzeiros', 'uses' => 'ReservasController@cruzeiros']);
                Route::get('reservas/visualizar/{pacote}' , ['as' => 'reservas.visualizar', 'uses' => 'ReservasController@visualizar']);
                Route::post('reservas/cancelamento' , ['as' => 'reservas.cancelamento', 'uses' => 'ReservasController@cancelamento']);*/

            Route::get('remessa/all', ['as' => 'remessa.all', 'uses' => 'RemessaController@getRemessas']);
            Route::get('remessa/{remessa}/efetivar', ['as' => 'remessa.efetivar', 'uses' => 'RemessaController@efetivar']);
            Route::get('remessa/{remessa}/boletos', ['as' => 'remessa.boletos', 'uses' => 'RemessaController@getBoletos']);
            Route::get('remessa/{remessa}/download', ['as' => 'remessa.download', 'uses' => 'RemessaController@download']);
            Route::resource('remessa', 'RemessaController');

            // CEP
            Route::get('cep/{cep?}', [
                'as' => 'cep', function ($cep = null) {
                    if (! $cep) {
                        $cep = $_REQUEST['cep'];
                    }

                    $resultCep = Correios::cep($cep);

                    return response()->json($resultCep);
                },
            ]);

            //Route::get('home/{layout}', ['as' => 'home', 'uses' => 'HomeController@newIndex']);
            Route::get('indices-economicos', ['as' => 'indices.economicos', 'uses' => 'HomeController@indiceEconomico']);
            Route::get('home', ['as' => 'home', 'uses' => 'HomeController@index']);

            Route::get('images/{filename}', [
                'as' => 'images', function ($filename) {
                    $path = storage_path('images\\'.$filename);

                    if (! File::exists($path)) {
                        abort(404);
                    }

                    $file = File::get($path);
                    $type = File::mimeType($path);

                    $response = Response::make($file, 200);
                    $response->header('Content-Type', $type);

                    return $response;
                },
            ]);

            Route::get('images/doc/{filename}', [
                'as' => 'images.doc', function ($filename) {
                    $path = storage_path('app/documentos/'.$filename);

                    if (! File::exists($path) || (! Auth::user()->hasRole('master') && ! Auth::user()->hasRole('admin'))) {
                        abort(404, 'Documentation Not Found.');
                    }

                    $file = File::get($path);
                    $type = File::mimeType($path);

                    $response = Response::make($file, 200);
                    $response->header('Content-Type', $type);

                    return $response;
                },
            ]);

            Route::get('test', ['as' => 'test', function () {
                //\Illuminate\Support\Facades\DB::beginTransaction();
                //\Event::fire(new \App\Events\RodarSistema());
                $pagseguro = new \App\Services\PagseguroService();
                $pagseguro->atualizarPagamentos();
            }]);
        }); //end auth

        //Pagseguro
        Route::get('pagseguro/requisicao/{deposito_id}', ['as' => 'pagseguro.requisicao', 'uses' => 'PagseguroController@criaRequisicao']);
        Route::get('pagseguro/transacao/{transaction_id}/consultar', ['as' => 'pagseguro.consultar.transacao.manual', 'uses' => 'PagseguroController@consultaPagamentoManual']);
        Route::post('pagseguro/registrarpagamento/{deposito_id}', ['as' => 'pagseguro.registrar.pagamento', 'uses' => 'PagseguroController@registrarPagamento']);
        Route::post('pagseguro/notificacao', ['as' => 'pagseguro.notificacao', 'uses' => 'PagseguroController@notificacao']);

        Route::get('download/{download}/{nomeArquivo}', ['as' => 'download.download', 'uses' => 'DownloadController@download']);
        Route::get('operacao-historico/visualizar-documento/{nomeArquivo}', ['as' => 'operacao-historico-documento', 'uses' => 'RentabilidadeHistoricoController@visualizarDocumento']);
    });
