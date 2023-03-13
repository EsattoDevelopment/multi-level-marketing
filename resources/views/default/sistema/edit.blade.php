@extends('default.layout.main')

@section('content')
    <section class="content-header">
        <h1>Configurações do sistema</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        </ol>
    </section>
    <section class="content">
        @include('default.errors.errors')
        <div class="row">
            <form role="form" action="{{ route('sistema.update', $dados->id) }}" method="post">
                {!! csrf_field() !!}
                <input type="hidden" name="_method" value="PUT">
                <div class="col-md-12">
                    {{--Sistemas--}}
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Sistemas</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Sistema de viagens</label> <br>
                                <small>habilita o sistema de viagens</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="sistema_viagens" class="flat-red" {{ old('sistema_viagens', $dados->sistema_viagens)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="sistema_viagens" class="flat-red" {{ old('sistema_viagens', $dados->sistema_viagens)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Sistema de saúde</label> <br>
                                <small>Habilita o sistema de saúde</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="sistema_saude" class="flat-red" {{ old('sistema_saude', $dados->sistema_saude)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="sistema_saude" class="flat-red" {{ old('sistema_saude', $dados->sistema_saude)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                        </div>
                    </div>

                    {{--Tipo de matrizes--}}
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Tipos de matriz</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Matriz unilevel</label> <br>
                                <small>habilita a matriz unilevel</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="matriz_unilevel" class="flat-red" {{ old('matriz_unilevel', $dados->matriz_unilevel)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="matriz_unilevel" class="flat-red" {{ old('matriz_unilevel', $dados->matriz_unilevel)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Matriz fechada</label> <br>
                                <small>habilita a matriz fechada</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="matriz_fechada" class="flat-red" {{ old('matriz_fechada', $dados->matriz_fechada)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="matriz_fechada" class="flat-red" {{ old('matriz_fechada', $dados->matriz_fechada)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Lateralidade da matriz fechada</label><br>
                                <small><i>Válido somente para matriz fechada</i></small>
                                <input type="number" name="matriz_fechada_tamanho" value="{{ old('matriz_fechada_tamanho', $dados->matriz_fechada_tamanho) }}" class="form-control" placeholder="Lateralidade da matriz fechada">
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Profundidade Matriz</label><br>
                                <small><i>Pronfundidade de pagamento das matrizes</i></small>
                                <input type="number" name="profundidade_pagamento_matriz" value="{{ old('profundidade_pagamento_matriz', $dados->profundidade_pagamento_matriz) }}" class="form-control" placeholder="Profundidade Matriz">
                            </div>
                        </div>
                    </div>

                    {{--Rede binaria--}}
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Binário</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Rede binária</label> <br>
                                <small>habilita a rede binária</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="rede_binaria" class="flat-red" {{ old('rede_binaria', $dados->rede_binaria)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="rede_binaria" class="flat-red" {{ old('rede_binaria', $dados->rede_binaria)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Valor ponto binário</label><br>
                                <small><i>Valores do pontos ao rodar o binário</i></small>
                                <input type="text" name="valor_ponto_binario" value="{{ old('valor_ponto_binario', $dados->valor_ponto_binario) }}" class="form-control" placeholder="Profundidade Matriz">
                            </div>
                        </div>
                    </div>

                    {{--Rentabilidade--}}
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Rentabilidade</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="rendimento_titulo">Paga rendimentos do titulo</label> <br>
                                <small>Faz pagamento de rendimentos através do titulo</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="rendimento_titulo" class="flat-red" {{ old('rendimento_titulo', $dados->rendimento_titulo)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="rendimento_titulo" class="flat-red" {{ old('rendimento_titulo', $dados->rendimento_titulo)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="rendimento_item">Paga rendimentos do item</label> <br>
                                <small>Faz pagamento de rendimentos através do item</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="rendimento_item" class="flat-red" {{ old('rendimento_item', $dados->rendimento_item)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="rendimento_item" class="flat-red" {{ old('rendimento_item', $dados->rendimento_item)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                        </div>
                    </div>

                    {{--Royalties--}}
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Royalties</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Porcentagem de royalties</label> <br>
                                <small>Define a porcentagem a ser paga como royalties sobre os bônus</small>
                                <div class="input-group">
                                    <input type="text" name="royalties_porcentagem"
                                           value="{{ old('royalties_porcentagem', $dados->royalties_porcentagem) }}" min="0"
                                           class="form-control" placeholder="">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Valor mínimo do bônus</label> <br>
                                <small>Define o valor mínimo do bônus que pagará royalties </small>
                                <div class="input-group">
                                    <span class="input-group-addon">{{$sistema->moeda}}</span>
                                    <input type="text" name="royalties_valor_minimo_bonus"
                                           value="{{ old('royalties_valor_minimo_bonus', $dados->royalties_valor_minimo_bonus) }}" min="0"
                                           class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Porcentagem de royalties a distribuir</label> <br>
                                <small>Define a porcentagem dos royalties retido que será distribuido</small>
                                <div class="input-group">
                                    <input type="text" name="royalties_porcentagem_distribuir"
                                           value="{{ old('royalties_porcentagem_distribuir', $dados->royalties_porcentagem_distribuir) }}" min="0"
                                           class="form-control" placeholder="">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{--Recontratação--}}
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Recontratação</h3>
                        </div>
                        <div class="box-body">
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label for="alertas_recontratacao_range_dias">Alertas de recontratação automática</label><br>
                                    <small><i>Range de dias para o envio de e-mail de alertas aos clientes. Ex: 5,3,1 Envio de e-mails em 5, 3 e 1 dia anteriores a renovação automática</i></small>
                                    <div class="input-group">
                                        <input type="text" name="alertas_recontratacao_range_dias"
                                               value="{{ old('alertas_recontratacao_range_dias', $dados->alertas_recontratacao_range_dias) }}" min="0"
                                               class="form-control" placeholder="">
                                        <span class="input-group-addon">range dias</span>
                                    </div>
                                </div>
                        </div>
                    </div>

                    {{--Transferências--}}
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Parâmetros de transferências</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-xs-12 col-lg-12">
                                <label for="">Transferências internas</label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Valor mínimo para transferências internas</label> <br>
                                <small>Valor mínimo para transferência entre contas internas</small>
                                <div class="input-group">
                                    <span class="input-group-addon">{{$sistema->moeda}}</span>
                                    <input type="text" name="transferencia_interna_valor_minimo"
                                           value="{{ old('transferencia_interna_valor_minimo', $dados->transferencia_interna_valor_minimo) }}" min="0"
                                           class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Valor mínimo para transferências internas gratuitas</label> <br>
                                <small>Valor mínimo para transferências gratuitas entre contas internas</small>
                                <div class="input-group">
                                    <span class="input-group-addon">{{$sistema->moeda}}</span>
                                    <input type="text" name="transferencia_interna_valor_minimo_gratis"
                                           value="{{ old('transferencia_interna_valor_minimo_gratis', $dados->transferencia_interna_valor_minimo_gratis) }}" min="0"
                                           class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Taxa de transferência interna</label> <br>
                                <small>Valor da taxa de transferência entre contas internas</small>
                                <div class="input-group">
                                    <span class="input-group-addon">{{$sistema->moeda}}</span>
                                    <input type="text" name="transferencia_interna_valor_taxa"
                                           value="{{ old('transferencia_interna_valor_taxa', $dados->transferencia_interna_valor_taxa) }}" min="0"
                                           class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Quantidade de transferências internas gratuitas por mês</label> <br>
                                <small>Quantidade de transferência gratuitas <b class="text-red">(se for ilimitada coloque um valor acima de 10000)</b></small>
                                <div class="input-group">
                                    <input type="text" name="transferencia_interna_qtde_gratis"
                                           value="{{ old('transferencia_interna_qtde_gratis', $dados->transferencia_interna_qtde_gratis) }}" min="0"
                                           class="form-control" placeholder="">
                                    <span class="input-group-addon">transferências</span>
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-lg-12">
                                <label style="padding-right: 25px">
                                    <input type="checkbox" value="1" name="transferencia_interna_estornar_taxa" class="flat-red" {{ old('transferencia_interna_estornar_taxa', $dados->transferencia_interna_estornar_taxa)  == 1 ? 'checked' : '' }}>
                                    Nas operações de estorno de transferência interna, estornar o valor da taxa quando houver
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-12">
                                <hr>
                                <label for="">Transferências externas</label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Valor mínimo para transferências externas</label> <br>
                                <small>Valor mínimo para transferências externas</small>
                                <div class="input-group">
                                    <span class="input-group-addon">{{$sistema->moeda}}</span>
                                    <input type="text" name="transferencia_externa_valor_minimo"
                                           value="{{ old('transferencia_externa_valor_minimo', $dados->transferencia_externa_valor_minimo) }}" min="0"
                                           class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Valor mínimo para transferência externa gratuita</label> <br>
                                <small>Valor mínimo para transferência gratuita para contas externas</small>
                                <div class="input-group">
                                    <span class="input-group-addon">{{$sistema->moeda}}</span>
                                    <input type="text" name="transferencia_externa_valor_minimo_gratis"
                                           value="{{ old('transferencia_externa_valor_minimo_gratis', $dados->transferencia_externa_valor_minimo_gratis) }}" min="0"
                                           class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Taxa de transferência externa</label> <br>
                                <small>Valor da taxa de transferência para contas externas</small>
                                <div class="input-group">
                                    <span class="input-group-addon">{{$sistema->moeda}}</span>
                                    <input type="text" name="transferencia_externa_valor_taxa"
                                           value="{{ old('transferencia_externa_valor_taxa', $dados->transferencia_externa_valor_taxa) }}" min="0"
                                           class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Quantidade de transferências externas gratuitas</label> <br>
                                <small>Quantidade de transferência gratuitas <b class="text-red">(se for ilimitada coloque um valor acima de 10000)</b></small>
                                <div class="input-group">
                                    <input type="text" name="transferencia_externa_qtde_gratis"
                                           value="{{ old('transferencia_externa_qtde_gratis', $dados->transferencia_externa_qtde_gratis) }}" min="0"
                                           class="form-control" placeholder="">
                                    <span class="input-group-addon">transferências</span>
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Dias úteis para efetivação</label> <br>
                                <div class="input-group">
                                    <input type="text" name="dias_para_transferencia"
                                           value="{{ old('dias_para_transferencia', $dados->dias_para_transferencia) }}" min="0"
                                           class="form-control" placeholder="">
                                    <span class="input-group-addon">Dias úteis</span>
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Dia permitido para transferência</label> <br>
                                <small>Em qual dia do mês a opção de transferência é habilitada para os usuários</small>
                                <div class="input-group">
                                    <input
                                        type="text"
                                        name="dia_permitido_para_saques"
                                        value="{{ old('dia_permitido_para_saques', $dados->dia_permitido_para_saques) }}"
                                        min="0"
                                        class="form-control"
                                    />
                                    <span class="input-group-addon">Dia do mês</span>
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label style="padding-right: 25px">
                                    <input
                                        type="checkbox"
                                        value="1"
                                        name="transferencia_externa_exige_upload_nota_fiscal"
                                        class="flat-red"
                                        {{ old('transferencia_externa_exige_upload_nota_fiscal', $dados->transferencia_externa_exige_upload_nota_fiscal)  == 1 ? 'checked' : '' }}
                                    />
                                    Exigir upload de nota fiscal nas transferências
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label style="padding-right: 25px">
                                    <input
                                        type="checkbox"
                                        value="1"
                                        name="restringir_dias_para_saques"
                                        class="flat-red"
                                        {{ old('restringir_dias_para_saques', $dados->restringir_dias_para_saques)  == 1 ? 'checked' : '' }}
                                    />
                                    Restringir dias para transferências
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label style="padding-right: 25px">
                                    <input
                                        type="checkbox"
                                        value="1"
                                        name="transferencia_externa_estornar_taxa"
                                        class="flat-red"
                                        {{ old('transferencia_externa_estornar_taxa', $dados->transferencia_externa_estornar_taxa)  == 1 ? 'checked' : '' }}
                                    />
                                    Nas operações de estorno de transferência externa, estornar o valor da taxa quando houver
                                </label>
                            </div>
                        </div>
                    </div>

                    {{--Parametros--}}
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">Parametros do sistema</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Bonus diário do titulo</label> <br>
                                <small>Os pagamentos de bonus diários usará os valores cadastrados nos titulos</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="paga_bonus_diario_titulo" class="flat-red" {{ old('paga_bonus_diario_titulo', $dados->paga_bonus_diario_titulo)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="paga_bonus_diario_titulo" class="flat-red" {{ old('paga_bonus_diario_titulo', $dados->paga_bonus_diario_titulo)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Bonus diário do item</label> <br>
                                <small>Os pagamentos de bonus diários usará os valores cadastrados nos itens</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="paga_bonus_diario_item" class="flat-red" {{ old('paga_bonus_diario_item', $dados->paga_bonus_diario_item)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="paga_bonus_diario_item" class="flat-red" {{ old('paga_bonus_diario_item', $dados->paga_bonus_diario_item)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Bonificação diaria</label> <br>
                                <small>As bonificações serão pagas diariamente automaticamente</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="bonificacao_diaria" class="flat-red" {{ old('bonificacao_diaria', $dados->bonificacao_diaria)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="bonificacao_diaria" class="flat-red" {{ old('bonificacao_diaria', $dados->bonificacao_diaria)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Bonificação diaria recorrente</label> <br>
                                <small>Se ativo as bonificações pagas serão referentes ao ultima inserção de bonus</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="bonificacao_diaria_recorrente" class="flat-red" {{ old('bonificacao_diaria_recorrente', $dados->bonificacao_diaria_recorrente)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="bonificacao_diaria_recorrente" class="flat-red" {{ old('bonificacao_diaria_recorrente', $dados->bonificacao_diaria_recorrente)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Tipo Bonus patrocinado</label> <br>
                                <small>Seta qual sera o tipo de calculo usado para bonificar o patrocinador</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="tipo_bonus_indicador" class="flat-red" {{ old('tipo_bonus_indicador', $dados->tipo_bonus_indicador)  == 1 ? 'checked' : '' }}>
                                    Fixo
                                </label>
                                <label>
                                    <input type="radio" value="2" name="tipo_bonus_indicador" class="flat-red" {{ old('tipo_bonus_indicador', $dados->tipo_bonus_indicador)  == 2 ? 'checked' : '' }}>
                                    Percentual
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Tipo Bonus equiparação</label> <br>
                                <small>Seta qual sera o tipo de calculo usado na equiparação</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="tipo_bonus_equiparacao" class="flat-red" {{ old('tipo_bonus_equiparacao', $dados->tipo_bonus_equiparacao)  == 1 ? 'checked' : '' }}>
                                    Fixo
                                </label>
                                <label>
                                    <input type="radio" value="2" name="tipo_bonus_equiparacao" class="flat-red" {{ old('tipo_bonus_equiparacao', $dados->tipo_bonus_equiparacao)  == 2 ? 'checked' : '' }}>
                                    Percentual
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Update de titulo</label> <br>
                                <small>Seta se o sistema terá update de titulo</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="update_titulo" class="flat-red" {{ old('update_titulo', $dados->update_titulo)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="update_titulo" class="flat-red" {{ old('update_titulo', $dados->update_titulo)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Update de titulo automatico</label> <br>
                                <small>Seta se o sistema o update de titulo será automatico</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="update_titulo_automatico" class="flat-red" {{ old('update_titulo_automatico', $dados->update_titulo_automatico)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="update_titulo_automatico" class="flat-red" {{ old('update_titulo_automatico', $dados->update_titulo_automatico)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Moeda</label><br>
                                <small><i>Moeda utilizada no sistema ({{ $sistema->moeda }}, $, £ ...)</i></small>
                                <input type="text" name="moeda" value="{{ old('moeda', $dados->moeda) }}" class="form-control" placeholder="Profundidade Matriz">
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Valor depósito</label><br>
                                <small><i>Valor minimo para realizar depósito (ex 20.00)</i></small>
                                <input type="text" name="min_deposito" value="{{ old('min_deposito', $dados->min_deposito) }}" class="form-control" placeholder="Valor depósito">
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Habilitar deposito?</label> <br>
                                <small>Flag para habilitar e desabilitar o deposito</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="deposito_is_active" class="flat-red" {{ old('deposito_is_active', $dados->deposito_is_active)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="deposito_is_active" class="flat-red" {{ old('deposito_is_active', $dados->deposito_is_active)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            {{--<div class="form-group col-xs-12 col-lg-6">
                                <label for="">Valor transferência</label><br>
                                <small><i>Valor minimo para realizar transferência (ex 20.00)</i></small>
                                <input type="text" name="min_transferencia" value="{{ old('min_transferencia', $dados->min_transferencia) }}" class="form-control" placeholder="Valor transferência">
                            </div>--}}
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Pagar bônus de equiparação</label> <br>
                                <small>Calcula e paga bônus de equiparação</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="pagar_bonus_equiparacao" class="flat-red" {{ old('pagar_bonus_equiparacao', $dados->pagar_bonus_equiparacao)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="pagar_bonus_equiparacao" class="flat-red" {{ old('pagar_bonus_equiparacao', $dados->pagar_bonus_equiparacao)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Item direcionado</label> <br>
                                <small>Os itens terão a opção de serem direcionados para um usuário especifico</small>
                                <br>
                                <label style="padding-right: 25px;">
                                    <input type="radio" value="1" name="item_direcionado" class="flat-red" {{ old('item_direcionado', $dados->item_direcionado)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="item_direcionado" class="flat-red" {{ old('item_direcionado', $dados->item_direcionado)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Cálculo e exibição dos pontos pessoais</label> <br>
                                <small>No momento só está tratando da exibição, continua claculando os pontos</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="pontos_pessoais_calculo_exibicao" class="flat-red" {{ old('pontos_pessoais_calculo_exibicao', $dados->pontos_pessoais_calculo_exibicao)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="pontos_pessoais_calculo_exibicao" class="flat-red" {{ old('pontos_pessoais_calculo_exibicao', $dados->pontos_pessoais_calculo_exibicao)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Cálculo e exibição dos pontos de equipe</label> <br>
                                <small>No momento só está tratando da exibição, continua claculando os pontos</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="pontos_equipe_calculo_exibicao" class="flat-red" {{ old('pontos_equipe_calculo_exibicao', $dados->pontos_equipe_calculo_exibicao)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="pontos_equipe_calculo_exibicao" class="flat-red" {{ old('pontos_equipe_calculo_exibicao', $dados->pontos_equipe_calculo_exibicao)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Exibir extrato de capitalização</label> <br>
                                <small>Exibe o extrato de capitalização para o associado</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="extrato_capitalizacao_exibicao" class="flat-red" {{ old('extrato_capitalizacao_exibicao', $dados->extrato_capitalizacao_exibicao)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="extrato_capitalizacao_exibicao" class="flat-red" {{ old('extrato_capitalizacao_exibicao', $dados->extrato_capitalizacao_exibicao)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Exibir extrato de bônus de equipe (equiparação)</label> <br>
                                <small>Exibe o extrato de bônus de equipe para o associado</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="extrato_bonus_equipe_exibicao" class="flat-red" {{ old('extrato_bonus_equipe_exibicao', $dados->extrato_bonus_equipe_exibicao)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="extrato_bonus_equipe_exibicao" class="flat-red" {{ old('extrato_bonus_equipe_exibicao', $dados->extrato_bonus_equipe_exibicao)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                        </div>
                    </div>

                    {{--Campos sistema--}}
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Campos do sistema</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Campo de CPF</label> <br>
                                <small>habilita o campos de CPF obrigatório</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="campo_cpf" class="flat-red" {{ old('campo_cpf', $dados->campo_cpf)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="campo_cpf" class="flat-red" {{ old('campo_cpf', $dados->campo_cpf)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Campo de RG</label> <br>
                                <small>habilita o campos de RG obrigatório</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="campo_rg" class="flat-red" {{ old('campo_rg', $dados->campo_rg)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="campo_rg" class="flat-red" {{ old('campo_rg', $dados->campo_rg)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Campo de Data de nascimento</label> <br>
                                <small>habilita o campos de Data de nascimento obrigatório</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="campo_dtnasc" class="flat-red" {{ old('campo_dtnasc', $dados->campo_dtnasc)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="campo_dtnasc" class="flat-red" {{ old('campo_dtnasc', $dados->campo_dtnasc)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Campo de Endereço</label> <br>
                                <small>habilita os campos de endereço</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="endereco" class="flat-red" {{ old('endereco', $dados->endereco)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="endereco" class="flat-red" {{ old('endereco', $dados->endereco)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Campo de Endereço obrigatório</label> <br>
                                <small>Torna os campos de endereço obrigatório</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="endereco_obrigatorio" class="flat-red" {{ old('endereco_obrigatorio', $dados->endereco_obrigatorio)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="endereco_obrigatorio" class="flat-red" {{ old('endereco_obrigatorio', $dados->endereco_obrigatorio)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Campo de dados bancarios</label> <br>
                                <small>Habilita os campos de dados bancarios</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="dados_bancarios" class="flat-red" {{ old('dados_bancarios', $dados->dados_bancarios)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="dados_bancarios" class="flat-red" {{ old('dados_bancarios', $dados->dados_bancarios)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Campo de dados bancarios obrigatório</label> <br>
                                <small>Torna os campos de dados bancarios obrigatório</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="dados_bancarios_obrigatorio" class="flat-red" {{ old('dados_bancarios_obrigatorio', $dados->dados_bancarios_obrigatorio)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="dados_bancarios_obrigatorio" class="flat-red" {{ old('dados_bancarios_obrigatorio', $dados->dados_bancarios_obrigatorio)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Campo Estrangeiro</label> <br>
                                <small>Habilitar campo para aceitar cadastro estrangeiro</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="habilita_estrangeiro" class="flat-red" {{ old('habilita_estrangeiro', $dados->habilita_estrangeiro)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="habilita_estrangeiro" class="flat-red" {{ old('habilita_estrangeiro', $dados->habilita_estrangeiro)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                        </div>
                    </div>

                    {{--Interface do usuário--}}
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Interface do usuário</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="habilita_registro_usuario_sem_indicacao">Exibe o botão "Abrir minha conta"</label> <br>
                                <small>Quando habilitado o usuário poderá carregar a tela de registro de usuário sem um link de indicador</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="habilita_registro_usuario_sem_indicacao" class="flat-red" {{ old('habilita_registro_usuario_sem_indicacao', $dados->habilita_registro_usuario_sem_indicacao)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="habilita_registro_usuario_sem_indicacao" class="flat-red" {{ old('habilita_registro_usuario_sem_indicacao', $dados->habilita_registro_usuario_sem_indicacao)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="habilita_registro_usuario_troca_indicador">Exibe o botão "Troca"</label> <br>
                                <small>Quando habilitado o botão "Troca" é exibido na tela de registro de usuários</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="habilita_registro_usuario_troca_indicador" class="flat-red" {{ old('habilita_registro_usuario_troca_indicador', $dados->habilita_registro_usuario_troca_indicador)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="habilita_registro_usuario_troca_indicador" class="flat-red" {{ old('habilita_registro_usuario_troca_indicador', $dados->habilita_registro_usuario_troca_indicador)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                        </div>
                    </div>

                    {{--Segurança--}}
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Autenticações de dois fatores</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="habilita_autenticacao_contratacao">Contratação</label> <br>
                                <small>Habilita a autenticação de dois fatores na contratação de novos contratos pelo usuário</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="habilita_autenticacao_contratacao" class="flat-red" {{ old('habilita_autenticacao_contratacao', $dados->habilita_autenticacao_contratacao)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="habilita_autenticacao_contratacao" class="flat-red" {{ old('habilita_autenticacao_contratacao', $dados->habilita_autenticacao_contratacao)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="habilita_autenticacao_recontratacao">Recontratação</label> <br>
                                <small>Habilita a autenticação de dois fatores na alteração do modo de renovação automática pelo usuário</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="habilita_autenticacao_recontratacao" class="flat-red" {{ old('habilita_autenticacao_recontratacao', $dados->habilita_autenticacao_recontratacao)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="habilita_autenticacao_recontratacao" class="flat-red" {{ old('habilita_autenticacao_recontratacao', $dados->habilita_autenticacao_recontratacao)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="habilita_autenticacao_transferencias">Transferências</label> <br>
                                <small>Habilita a autenticação de dois fatores nas solicitações de transferências do usuário</small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="habilita_autenticacao_transferencias" class="flat-red" {{ old('habilita_autenticacao_transferencias', $dados->habilita_autenticacao_transferencias)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="habilita_autenticacao_transferencias" class="flat-red" {{ old('habilita_autenticacao_transferencias', $dados->habilita_autenticacao_transferencias)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
                        </div>
                    </div>

                    {{--Emails--}}
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">E-mails</h3>
                            <i>Configuração para quem vai receber emails do sistema.</i>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="emails_dados_bancarios">Dados bancários</label> <br>
                                <small>E-mail de quem vai conferir comprovante bancário.</small>
                                <select name="emails_dados_bancarios[]" class="form-control select2-tags" multiple="multiple">
                                    @foreach($dados->emails_dados_bancarios as $key => $value)
                                        <option selected value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="emails_documentacao">Documentação</label> <br>
                                <small>E-mail de quem vai conferir verificação da documentação.</small>
                                <select name="emails_documentacao[]" class="form-control select2-tags" multiple="multiple">
                                    @foreach($dados->emails_documentacao as $key => $value)
                                        <option selected value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="emails_comprovante_pagamento">Comprovante de pagamento</label> <br>
                                <small>E-mail de quem vai conferir comprovante de pagamento.</small>
                                <select name="emails_comprovante_pagamento[]" class="form-control select2-tags" multiple="multiple">
                                    @foreach($dados->emails_comprovante_pagamento as $key => $value)
                                        <option selected value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
@endsection

@section('script')
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script src="/plugins/iCheck/icheck.min.js"></script>
    <link rel="stylesheet" href="/plugins/iCheck/square/red.css">

    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();

            $(".select2-tags").select2({
                tags: true,
                tokenSeparators: [',', ' '],
                createTag: function (params) {
                    var term = $.trim(params.term);
                    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

                    if (term === '') {
                        return null;
                    }

                    if (! re.test(String(term).toLowerCase())) {
                        return null;
                    }

                    return {
                        id: term,
                        text: term,
                        newTag: true // add additional parameters
                    }
                }
            });

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red',
                increaseArea: '20%' // optional
            });

            $('input[name="sistema_viagens"][value="{{ old('sistema_viagens', $dados->sistema_viagens) == 0 ? 0 : 1 }}"]').iCheck('check');
            $('input[name="sistema_saude"][value="{{ old('sistema_saude', $dados->sistema_saude) == 0 ? 0 : 1 }}"]').iCheck('check');
            $('input[name="matriz_unilevel"][value="{{ old('matriz_unilevel', $dados->matriz_unilevel) == 0 ? 0 : 1 }}"]').iCheck('check');
            $('input[name="matriz_fechada"][value="{{ old('matriz_fechada', $dados->matriz_fechada) == 0 ? 0 : 1 }}"]').iCheck('check');
            $('input[name="rede_binaria"][value="{{ old('rede_binaria', $dados->rede_binaria) == 0 ? 0 : 1 }}"]').iCheck('check');

            $('input[name="paga_bonus_diario_titulo"][value="{{ old('paga_bonus_diario_titulo', $dados->paga_bonus_diario_titulo) == 0 ? 0 : 1 }}"]').iCheck('check');
            $('input[name="paga_bonus_diario_item"][value="{{ old('paga_bonus_diario_item', $dados->paga_bonus_diario_item) == 0 ? 0 : 1 }}"]').iCheck('check');
            $('input[name="bonificacao_diaria"][value="{{ old('bonificacao_diaria', $dados->bonificacao_diaria) == 0 ? 0 : 1 }}"]').iCheck('check');
            $('input[name="tipo_teto_pagamento"][value="{{ old('tipo_teto_pagamento', $dados->tipo_teto_pagamento) == 1 ? 1 : 2 }}"]').iCheck('check');
            $('input[name="update_titulo"][value="{{ old('update_titulo', $dados->update_titulo) == 0 ? 0 : 1 }}"]').iCheck('check');

            $('input[name="campo_cpf"][value="{{ old('campo_cpf', $dados->campo_cpf) == 0 ? 0 : 1 }}"]').iCheck('check');
            $('input[name="campo_rg"][value="{{ old('campo_rg', $dados->campo_rg) == 0 ? 0 : 1 }}"]').iCheck('check');
            $('input[name="campo_dtnasc"][value="{{ old('campo_dtnasc', $dados->campo_dtnasc) == 0 ? 0 : 1 }}"]').iCheck('check');
            $('input[name="endereco"][value="{{ old('endereco', $dados->endereco) == 0 ? 0 : 1 }}"]').iCheck('check');
            $('input[name="endereco_obrigatorio"][value="{{ old('endereco_obrigatorio', $dados->endereco_obrigatorio) == 0 ? 0 : 1 }}"]').iCheck('check');
            $('input[name="dados_bancarios"][value="{{ old('dados_bancarios', $dados->dados_bancarios) == 0 ? 0 : 1 }}"]').iCheck('check');
            $('input[name="dados_bancarios_obrigatorio"][value="{{ old('dados_bancarios_obrigatorio', $dados->dados_bancarios_obrigatorio) == 0 ? 0 : 1 }}"]').iCheck('check');

        });
    </script>
@endsection
