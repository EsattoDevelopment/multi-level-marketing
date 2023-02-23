@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')
    <section class="content-header">
        <h1>
            Confirmação de depósito
        </h1>
        <ol class="breadcrumb hidden-xs">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Depósito</li>
            <li class="active">#{{ $dados->pedido_id }}</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">

                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-header with-border">
                        @if($dados->metodo_pagamento_id == 9)
                            <h3 class="box-title">{{$metodoPagamento->name . ' - ' . $contasTed->getRelation('banco')->nome}}</h3>
                        @endif
                    </div>
                    <div class="box-body">
                        <dl class="dl-horizontal">
                            <dt>Referência</dt>
                            <dd>#{{ $dados->pedido_id }}</dd>
                            <dt>Valor</dt>
                            @if($dados->metodo_pagamento_id == 9)
                                <dd>{{mascaraMoeda($sistema->moeda, $pedido->getRelation('dadosPagamento')->valor, 2, true)}}</dd>
                                {{--<dt>Cotação do Reais</dt>
                                <dd>R$ {{str_replace(".", ",", $cotacaoMoeda)}}</dd>
                                <dt>Valor convertido em R$<span class="text-red">*</span></dt>
                                <dd>R$ {{mascaraMoeda($sistema->moeda, $valorEmReais, 4, true)}}</dd>--}}
                            @endif
                            {{--<span class="text-muted">* <i>O valor efetivo final é calculado usando a cotação do "Dólar Turismo Compra" no instante da nossa conferência em seu TED.<br>* Fique tranquilo que sempre manteremos o valor original por completo, desde que você tenha feito o TED no valor correto.</i></span>--}}
                        </dl>
                    </div><!-- /.box-body -->
                    <div class="box-header with-border">
                        <i class="glyphicon glyphicon-list-alt"></i>
                        <h3 class="box-title">
                            @if($dados->metodo_pagamento_id == 9)
                                Favor efetuar o TED para:
                            @else
                                Favor efetuar o depósito seguindo as instruções a seguir:
                            @endif
                        </h3>
                    </div>
                    <div class="box-body">
                        <dl class="dl-horizontal">
                            @if($dados->metodo_pagamento_id == 9)
                                <dt>Favorecido</dt>
                                <dd>{{$contasTed->favorecido}}</dd>
                                <dt>CNPJ</dt>
                                <dd>{{$contasTed->cpfcnpj}}</dd>
                                <dt>Banco</dt>
                                <dd>{{$contasTed->getRelation('banco')->nome}}</dd>
                                <dt>Código do banco</dt>
                                <dd>{{$contasTed->getRelation('banco')->codigo}}</dd>
                                <dt>Agência</dt>
                                <dd>{{$contasTed->agencia}}</dd>
                                <dt>Conta Corrente - Digito</dt>
                                <dd>{{$contasTed->conta . '-' . $contasTed->contaDv}}</dd>
                            @endif
                        </dl>
                    </div><!-- /.box-body -->

                    @if($dados->metodo_pagamento_id == 9)
                        <div class="box-header with-border">
                            <i class="glyphicon glyphicon-asterisk"></i>
                            <h3 class="box-title">
                                Atenção
                            </h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-xs-12">
                                <strong class="text-red">É expressamente proibido transferências bancárias por contas de terceiros.<br>
                                    A transferência deve ser feita exclusivamente de uma conta bancária da mesma titularidade da conta {{ env('COMPANY_NAME', 'Nome empresa') }}.
                                </strong>
                            </div>
                        </div>
                    @endif

                    <div class="box-header with-border">
                        <i class="glyphicon glyphicon-list-alt"></i>
                        <h3 class="box-title">
                            @if($dados->metodo_pagamento_id == 9)
                                Enviar Comprovante de Transferência
                            @else
                                Confirmação de pagamento
                            @endif
                        </h3>
                    </div>
                    <div class="box-body">
                        @if($dados->metodo_pagamento_id == 9)
                            <form name="f1" method="post" enctype="multipart/form-data" action="{{route('pedido.confirmarTransactionGatewayPagamento')}}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="pedido_id" value="{{ $dados->pedido_id }}">
                                <input type="hidden" name="metodo_pagamento_id" value="{{ $dados->metodo_pagamento_id }}">
                                <input type="hidden" name="conta_empresa_id" value="{{ $contasTed->id}}">
                                {{--<input type="hidden" name="cotacao_dolar_dia_compra" value="{{ $cotacaoMoeda}}">--}}
                                {{--<input type="hidden" name="valor_real" value="{{ $valorEmReais}}">--}}
                                <div class="form-group col-xs-12 col-sm-1 col-md-4">
                                    <label for="path_comprovante_ted"><strong>Já efetuou a transferência? Nos envie o comprovante</strong></label>
                                    <input type="file" id="path_comprovante_ted" required="true" name="path_comprovante_ted" accept="image/png, image/jpg, image/jpeg">
                                </div>
                                {{--<div class="form-group col-xs-12 col-md-12">
                                    <label for="exampleInputEmail1">Informe o Valor Transferido</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">R$</span>
                                        <input type="text" required="true" name="valor_real" id="valor_real" value="{{old('valor_real') }}" class="form-control"  placeholder="Clique aqui e digite o valor transferido">
                                    </div>
                                </div>--}}
                                <div class="form-group col-xs-12 col-sm-6 col-md-4" style="padding-top: 10px">
                                    <button type="submit" class="btn btn-primary">Enviar comprovante <i class="fa fa-cloud-upload"></i></button>
                                </div>
                            </form>
                        @endif
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <a href="{{ route('pedido.usuario.pedido', [Auth::user()->id, $dados->pedido_id]) }}" class="btn btn-primary pull-right">Voltar</a>
                    </div>
                </div><!-- /.box -->
            </div><!--/.col (left) -->
        </div>   <!-- /.row -->
    </section>
@endsection

@section('style')

@endsection

@section('script')
    <script src="{{asset('js/jquery.mask.min.js')}}"></script>
    <script>
        $('#valor_real').mask('#.##0,00', {reverse: true});
    </script>
@endsection