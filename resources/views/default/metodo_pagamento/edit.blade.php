@extends('default.layout.main')

@section('content')
    <section class="content">

        @include('default.errors.errors')

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <!-- form start -->
                <form role="form" action="{{ route('metodo_pagamento.update', $dados->id) }}" method="post">
                {!! csrf_field() !!}
                <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edição de Método de Pagamento</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="form-group col-xs-12">
                                <label for="status">Status</label> <br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary {{ old('status', $dados->status) == 1 ? 'active' : ($dados->status == 1 ? 'active' : '') }}">
                                        <input type="radio" value="1" {{ old('status', $dados->status) == 1 ? 'checked' : ($dados->status == 1 ? 'checked' : '')  }} name="status"
                                               autocomplete="off">Ativo
                                    </label>
                                    <label class="btn btn-primary {{ old('status', $dados->status) == 0 ? 'active' : ($dados->status == 0 ? 'active' : '') }}">
                                        <input type="radio" value="0" {{ old('status', $dados->status) == 0 ? 'checked' : ($dados->status == 0 ? 'checked' : '')  }} name="status" autocomplete="off">Inativo
                                    </label>
                                </div>
                            </div>

                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Usar no depósito</label> <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="usar_deposito" class="flat-red" {{ old('usar_deposito', $dados->usar_deposito) == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="usar_deposito" class="flat-red" {{ old('usar_deposito', $dados->usar_deposito) == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>

                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="">Usar nos itens</label> <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="usar_item" class="flat-red" {{ old('usar_item', $dados->usar_item) == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="usar_item" class="flat-red" {{ old('usar_item', $dados->usar_item) == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>

                            <div class="form-group col-xs-6">
                                <label for="name">Nome</label><br>
                                <small>Aparecerá no label do botão de pagamento</small>
                                <input type="text" name="name" value="{{ old('name', $dados->name) }}" class="form-control" placeholder="Nome">
                            </div>
                            <div class="form-group col-xs-6">
                                <label for="">Descrição da taxa</label><br>
                                <small>Aparecerá na descrição do pagamento quando o valor da taxa for maior que 0.00</small>
                                <input type="text" name="taxa_descricao" value="{{ old('taxa_descricao', $dados->taxa_descricao) }}" class="form-control" placeholder="Descrição da taxa">
                            </div>
                            <div class="form-group col-xs-6">
                                <label for="">Valor da Taxa </label><br>
                                <small> Será somado ao valor do pedido<b class="text-red"> (deixar zerado caso já tenha um campo especifico. Ex: Boleto)</b></small>
                                <div class="input-group">
                                    <span class="input-group-addon">{{ $sistema->moeda }}</span>
                                    <input type="text" class="form-control" name="taxa_valor" placeholder="0,00"
                                           value="{{ old('valor_taxa', $dados->taxa_valor) }}"
                                           data-affixes-stay="true" data-prefix="" data-thousands=""
                                           data-decimal=".">
                                </div>
                            </div>
                            <div class="form-group col-xs-6">
                                <label for="">Valor Percentual da Taxa </label><br>
                                <small> Será somado ao valor do pedido<b class="text-red"> (deixar zerado caso já tenha um campo especifico. Ex: Boleto)</b></small>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="taxa_porcentagem" placeholder="0,00"
                                           value="{{ old('taxa_porcentagem', $dados->taxa_porcentagem) }}"
                                           data-affixes-stay="true" data-prefix="" data-thousands=""
                                           data-decimal=".">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h4 class="box-title">Configurações adicionais</h4>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            @if($dados->id == 1) {{--Boleto Bancario--}}
                                <div class="form-group col-xs-12 col-md-6">
                                    <label for="configuracao[dias_vencimento]">Dias para vencimento (após a geração do boleto)</label>
                                    <input type="number" name="configuracao[dias_vencimento]" maxlength="3" onkeypress="return event.charCode >= 48 && event.charCode <= 57" value="{{ old('configuracao[dias_vencimento]', @isset($dados->configuracao['dias_vencimento']) ? $dados->configuracao['dias_vencimento'] : '') }}" class="form-control" placeholder="Dias para vencimento">
                                </div>
                                <div class="form-group col-xs-12 col-md-6">
                                    <label for="">Tarifa por boleto (será somado ao valor do depósito)</label><br>
                                    <div class="input-group">
                                        <span class="input-group-addon">{{ $sistema->moeda }}</span>
                                        <input type="text" class="form-control boletos"  name="configuracao[tarifa_boleto]" placeholder="0,00"
                                               value="{{ old('configuracao[tarifa_boleto]', $dados->configuracao['tarifa_boleto'] ?? '0.00') }}"
                                               data-affixes-stay="true" data-prefix="" data-thousands=""
                                               data-decimal=".">
                                    </div>
                                </div>
                                <div class="form-group col-xs-12 col-md-6">
                                    <label for="">Limite máximo de recebimento mensal</label><br>
                                    <div class="input-group">
                                        <span class="input-group-addon">{{ $sistema->moeda }}</span>
                                        <input type="text" class="form-control boletos" name="configuracao[limite_mensal]" placeholder="0,00"
                                               value="{{ old('configuracao[limite_mensal]', $dados->configuracao['limite_mensal'] ?? '0.00') }}"
                                               data-affixes-stay="true" data-prefix="" data-thousands=""
                                               data-decimal=".">
                                    </div>
                                </div>

                                <div class="form-group col-xs-12 col-md-6">
                                    <label for="">Limite máximo de recebimento diário</label><br>
                                    <div class="input-group">
                                        <span class="input-group-addon">{{ $sistema->moeda }}</span>
                                        <input type="text" class="form-control boletos" name="configuracao[limite_diario]" placeholder="0,00"
                                               value="{{ old('configuracao[limite_diario]', $dados->configuracao['limite_diario'] ?? '0.00') }}"
                                               data-affixes-stay="true" data-prefix="" data-thousands=""
                                               data-decimal=".">
                                    </div>
                                </div>
                                <div class="form-group col-xs-12 col-md-6">
                                    <label for="">Valor máximo para geração do boleto</label><br>
                                    <div class="input-group">
                                        <span class="input-group-addon">{{ $sistema->moeda }}</span>
                                        <input type="text" class="form-control boletos" name="configuracao[limite_geracao_boleto]" placeholder="0,00"
                                               value="{{ old('configuracao[limite_geracao_boleto]', $dados->configuracao['limite_geracao_boleto'] ?? '0.00') }}"
                                               data-affixes-stay="true" data-prefix="" data-thousands=""
                                               data-decimal=".">
                                    </div>
                                </div>

                                <div class="form-group col-xs-12 col-md-6">
                                    <label class="text-red" for="">Informações sobre limites</label><br>
                                    @foreach(limitesBoleto(1) as $chave => $value)
                                        @if($chave == 'limiteMensalDisponivel')
                                            <label for="">Limite mensal disponível: </label> {{mascaraMoeda($sistema->moeda, $value, 2, true)}}
                                        @endif
                                        @if($chave == 'limiteMensalUsado')
                                            <label for=""> / Limite mensal utilizado: </label> {{mascaraMoeda($sistema->moeda, $value, 2, true)}}<br>
                                        @endif
                                        @if($chave == 'limiteDiarioDisponivel')
                                            <label for="">Limite diário disponível: </label> {{mascaraMoeda($sistema->moeda, $value, 2, true)}}
                                        @endif
                                            @if($chave == 'limiteDiarioUsado')
                                                <label for=""> / Limite diário utilizado: </label> {{mascaraMoeda($sistema->moeda, $value, 2, true)}}<br>
                                            @endif
                                    @endforeach
                                </div>

                                <div class="form-group col-xs-12">
                                    <label for="configuracao[ambiente_ativo]">Ambiente Ativo</label><br>
                                    <label style="padding-right: 25px">
                                        <input type="radio" value="P" name="configuracao[ambiente_ativo]" class="check-red flat-red" {{ old('configuracao[ambiente_ativo]', @isset($dados->configuracao['ambiente_ativo']) ? $dados->configuracao['ambiente_ativo'] : '' )  == "P" ? 'checked' : '' }}>
                                        Produção
                                    </label>
                                    <label>
                                        <input type="radio" value="H" name="configuracao[ambiente_ativo]" class="check-red flat-red" {{ old('configuracao[ambiente_ativo]', @isset($dados->configuracao['ambiente_ativo']) ? $dados->configuracao['ambiente_ativo'] : '' )  == "H" ? 'checked' : '' }}>
                                        Homologação
                                    </label>
                                </div>
                                <div class="col-md-12">
                                    <h4 class="box-title">Dados Ambiente de Homologação</h4>
                                </div>
                                <div class="form-group col-xs-12">
                                    <label for="configuracao[client_id_homolog]">Client ID</label>
                                    <input type="text" name="configuracao[client_id_homolog]" value="{{ old('configuracao[client_id_homolog]', @isset($dados->configuracao['client_id_homolog']) ? $dados->configuracao['client_id_homolog'] : '') }}" class="form-control" placeholder="Client ID ambiente de homologação">
                                </div>
                                <div class="form-group col-xs-12">
                                    <label for="configuracao[client_secret_homolog]">Client Secret</label>
                                    <input type="text" name="configuracao[client_secret_homolog]" value="{{ old('configuracao[client_secret_homolog]', @isset($dados->configuracao['client_secret_homolog']) ? $dados->configuracao['client_secret_homolog'] : '') }}" class="form-control" placeholder="Client Secret ambiente de homologação">
                                </div>
                                <div class="col-md-12">
                                    <h4 class="box-title">Dados Ambiente de Produção</h4>
                                </div>
                                <div class="form-group col-xs-12">
                                    <label for="configuracao[client_id_prod]">Client ID</label>
                                    <input type="text" name="configuracao[client_id_prod]" value="{{ old('configuracao[client_id_prod]', @isset($dados->configuracao['client_id_prod']) ? $dados->configuracao['client_id_prod'] : '') }}" class="form-control" placeholder="Client ID ambiente de produção">
                                </div>
                                <div class="form-group col-xs-12">
                                    <label for="configuracao[client_secret_homolog]">Client Secret</label>
                                    <input type="text" name="configuracao[client_secret_prod]" value="{{ old('configuracao[client_secret_prod]', @isset($dados->configuracao['client_secret_prod']) ? $dados->configuracao['client_secret_prod'] : '') }}" class="form-control" placeholder="Client Secret ambiente de produção">
                                </div>
                            @elseif($dados->id == 11) {{--AstroPay Card--}}
                                <div class="col-md-12">
                                    <h4 class="box-title">Dados Ambiente de Homologação</h4>
                                </div>
                                <div class="form-group col-xs-12">
                                    <label for="configuracao[x_login_homolog]">x_login Homologação</label>
                                    <input type="text" name="configuracao[x_login_homolog]" value="{{ old('configuracao[x_login_homolog]', @isset($dados->configuracao['x_login_homolog']) ? $dados->configuracao['x_login_homolog'] : '') }}" class="form-control" placeholder="x_login">
                                </div>
                                <div class="form-group col-xs-12">
                                    <label for="configuracao[x_trans_key_homolog]">x_trans_key Homologação</label>
                                    <input type="text" name="configuracao[x_trans_key_homolog]" value="{{ old('configuracao[x_trans_key_homolog]', @isset($dados->configuracao['x_trans_key_homolog']) ? $dados->configuracao['x_trans_key_homolog'] : '') }}" class="form-control" placeholder="x_trans_key">
                                </div>
                                <div class="col-md-12">
                                    <h4 class="box-title">Dados Ambiente de Produção</h4>
                                </div>
                                <div class="form-group col-xs-12">
                                    <label for="configuracao[x_login_prod]">x_login Produção</label>
                                    <input type="text" name="configuracao[x_login_prod]" value="{{ old('configuracao[x_login_prod]', @isset($dados->configuracao['x_login_prod']) ? $dados->configuracao['x_login_prod'] : '') }}" class="form-control" placeholder="x_login">
                                </div>
                                <div class="form-group col-xs-12">
                                    <label for="configuracao[x_trans_key_prod]">x_trans_key Produção</label>
                                    <input type="text" name="configuracao[x_trans_key_prod]" value="{{ old('configuracao[x_trans_key_prod]', @isset($dados->configuracao['x_trans_key_prod']) ? $dados->configuracao['x_trans_key_prod'] : '') }}" class="form-control" placeholder="x_trans_key">
                                </div>
                            @else
                                <div class="form-group col-xs-12">
                                    <label for="nome_codigo_conta">Nome ou código da conta</label>
                                    <input type="text" name="nome_codigo_conta" value="{{ old('nome_codigo_conta', $dados->nome_codigo_conta) }}" class="form-control" placeholder="Nome ou código da conta">
                                </div>
                                <div class="form-group col-xs-12">
                                    <label for="codigo_carteira">Código da carteira</label>
                                    <input type="text" name="codigo_carteira" value="{{ old('codigo_carteira', $dados->codigo_carteira) }}" class="form-control" placeholder="Código da carteira">
                                </div>
                                <div class="form-group col-xs-12">
                                    <label for="configuracao">Token comum</label>
                                    <input type="text" name="configuracao[]" value="{{ old('configuracao', $dados->configuracao[0])}}" class="form-control"  placeholder="token comum">
                                </div>
                                <div class="form-group col-xs-12">
                                    <label for="configuracao">Token para Transferência</label>
                                    <input type="text" name="configuracao[]" value="{{ old('configuracao', $dados->configuracao[1])}}" class="form-control"  placeholder="token para transferência">
                                </div>
                                <div class="form-group col-xs-12">
                                    <label for="configuracao">Token para TED</label>
                                    <input type="text" name="configuracao[]" value="{{ old('configuracao', $dados->configuracao[2])}}" class="form-control"  placeholder="token para TED">
                                </div>
                            @endif
                        </div>
                        <input type="hidden" name="_method" value="PUT">
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <a href="{{ route('metodo_pagamento.index') }}" class="btn btn-default pull-right">Voltar</a>
                        </div>
                    </div>
                </form>
            </div><!--/.col (left) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->

@endsection

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="/plugins/iCheck/square/red.css">
@endsection

@section('script')
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script src="/plugins/iCheck/icheck.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>

    <script>
        $(".check-red").iCheck({
            checkboxClass: 'icheckbox_square-red',
            radioClass: 'iradio_square-red',
            increaseArea: '20%' // optional
        });

        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();

            $(".boletos").maskMoney({
                defaultZero:true,
                allowZero:true
            });
        });
    </script>
@endsection
