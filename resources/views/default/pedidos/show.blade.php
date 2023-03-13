@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Pedido #{{ $dados->id }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Depósitos</li>
            <li class="active">#{{ $dados->id }}</li>
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
                        <i class="glyphicon glyphicon-user"></i>

                        <h3 class="box-title">Dados do pedido</h3>
                    </div>
                    <div class="box-body">
                        <dl class="dl-horizontal">
                            <dt>Nome usuário</dt>
                            <dd>{{ $dados->getRelation('user')->name }}</dd>
                            @if($dados->getRelation('itens')->first())
                                <dt>Item</dt>
                                <dd>
                                    #{{ $dados->getRelation('itens')->first()->item_id }} {{ $dados->getRelation('itens')->first()->name_item }}
                                </dd>
                            @endif
                            <dt>Valor</dt>
                            <dd>{{ mascaraMoeda($sistema->moeda, $dados->getRelation('dadosPagamento')->valor, 2, true) }}</dd>
                            <dt>Data compra</dt>
                            <dd>{{ $dados->data_compra->format('d/m/Y H:i:s') }}</dd>
                            @if($dados->tipo_pedido == 3)
                                <dt>Pontos válidos</dt>
                                <dd>{{ (int)$dados->getRelation('dadosPagamento')->valor }}</dd>
                            @endif
                        </dl>
                        @if($dados->getRelation('dadosPagamento')->metodo_pagamento_id == 1)
                            <div class="form-group col-xs-12">
                                <a target="_blank" href="{{ $dados->getRelation('dadosPagamento')->dados_boleto['pdf'] }}" class="btn btn-warning">Visualizar / Imprimir Boleto <i class="glyphicon glyphicon-print"></i></a>
                            </div>
                        @endif
                    </div><!-- /.box-body -->

                    <div class="box-header with-border">
                        @if($dados->getRelation('dadosPagamento')->metodo_pagamento_id == 9) {{--pagamento por ted--}}
                        <i class="glyphicon glyphicon-list-alt"></i>
                        <h3 class="box-title">Forma de pagamento</h3>
                        @endif
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @if($dados->getRelation('dadosPagamento')->metodo_pagamento_id == 9) {{--pagamento por ted--}}
                        <dl class="dl-horizontal">
                            <dt>Pago por</dt>
                            <dd>{{$metodo_pagamento->name}} - {{ $contaEmpresa ? $contaEmpresa->getRelation('banco')->nome : ''}}</dd>
                            <dt>Valor Transferido</dt>
                            <dd>{{ mascaraMoeda($sistema->moeda, $dados->getRelation('dadosPagamento')->valor_real, 2, true) }}</dd>
                            <dt>Data do pagamento</dt>
                            <dd>{{$dados->dadosPagamento->data_pagamento->format('d/m/Y H:i:s')}}</dd>
                            <dt>Comprovante</dt>
                            <dd>
                                @if(pathinfo($dados->dadosPagamento->path_comprovante_ted)['extension'] == 'pdf')
                                    <a class="btn btn-primary" href="{{ route('pedido.comprovante', [$dados->id, $dados->dadosPagamento->path_comprovante_ted]) }}">Ver comprovante</a>
                                @else
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">
                                        Ver comprovante
                                    </button>
                                @endif

                            </dd>
                        </dl>
                        <!-- Modal -->
                        <div class="modal fade" id="modal-default">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Comprovante</h4>
                                    </div>
                                    <div class="modal-body">
                                        <img style="width: 100%" src="{{ route('imagecache', ['background', $dados->dadosPagamento->path_comprovante_ted]) }}">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        @else
                            @if(Auth::user()->can(['master']))
                                <div class="box box-warning">
                                    <div class="box-header with-border">
                                        <i class="glyphicon glyphicon-user"></i>
                                        <h3 class="box-title">Pagar pelo sistema</h3>
                                        @if($dados->getRelation('dadosPagamento')->metodo_pagamento_id == 1)
                                            <br>
                                            <small class="text-red">Este depósito possui um boleto em aberto.<br>Ao escolher pagar pelo sistema, este boleto será cancelado para não gerar encargos.</small>
                                        @endif
                                    </div>
                                    <form method="post" id="form-pedido" action="{{ route('pagamento.sistema', $dados->id) }}">
                                        <div class="box-body">
                                            {!! csrf_field() !!}
                                            @if($dados->getRelation('dadosPagamento')->metodo_pagamento_id == 1)
                                                <input type="hidden" name="cancelar_boleto" value="1">
                                            @endif
                                            <div class="form-group col-md-12">
                                                <label class="text-red">Forma de pagamento</label>
                                                <select class="form-control" required id="metodo_pagamento_id"
                                                        name="metodo_pagamento_id">
                                                    @foreach($metodo_pagamento as $mp)
                                                        @if($mp-> id == 9) {{--ted/doc--}}
                                                        @if($metodosPagamentoBancoTed != null) {{--tem banco habilitado a receber TED--}}
                                                        @foreach($metodosPagamentoBancoTed as $banco)
                                                            <option value="{{ $mp->id . '-' . $banco->id}}" {{ old('metodo_pagamento_id', $dados->metodo_pagamento_id) == $mp->id ? 'selected="selected"' : '' }} >{{ $mp->name . ' ' . $banco->getRelation('banco')->nome}}</option>
                                                        @endforeach
                                                        @else
                                                            <option value="{{$mp->id}}" {{ old('metodo_pagamento_id', $dados->metodo_pagamento_id) == $mp->id ? 'selected="selected"' : '' }} >{{ $mp->name }}</option>
                                                        @endif
                                                        @else
                                                            <option value="{{ $mp->id }}" {{ old('metodo_pagamento_id', $dados->metodo_pagamento_id) == $mp->id ? 'selected="selected"' : '' }} >{{ $mp->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <label>Data pagamento</label>
                                                <input type="text" required name="data_pagamento" value="" class="form-control datepicker">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Descrição</label>
                                                <input type="text" required name="descricao" class="form-control"
                                                       placeholder="Descrição">
                                            </div>

                                        </div><!-- /.box-body -->
                                        <div class="box-footer">
                                            <button type="submit" id="botao-confirmar" class="btn btn-success" type="button">Pagar pelo sistema
                                            </button>
                                            <span class="text-red text-bold" id="alerta"></span>

                                        </div>
                                    </form>
                                </div>
                            @endif
                        @endif

                        {{--<div class="box">

                            <div class="box-header with-border">
                                <i class="glyphicon glyphicon-user"></i>

                                <h3 class="box-title">Boleto</h3>
                            </div>
                            <div class="box-body">
                                <form method="post" target="_blank"
                                      action="{{ route('pedido.usuario.pedido.pagar.boleto', [Auth::user()->id, $dados->id]) }}">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="metodo_pagamento" value="1">
                                    <input type="hidden" name="pedido_id" value="{{ $dados->id }}">
                                    <input type="hidden" name="user_id" value="{{ $dados->user_id }}">
                                    <div class="form-group">
                                        <label>Data de vencimento <br>
                                        <small>vencimento do boleto</small></label>
                                        <input type="text" required name="data_vencimento" class="form-control datepicker">
                                    </div>
                                    @foreach($contas as $conta)
                                        <button name="boleto" value="{{ $conta->getRelation('banco')->codigo }}"
                                                class="btn btn-{{ $conta->getRelation('banco')->class_cor }}">
                                            Boleto {{ $conta->getRelation('banco')->nome }} <i
                                                    class="glyphicon glyphicon-barcode"></i></button>
                                    @endforeach
                                </form>
                            </div><!-- /.box-body -->
                        </div>--}}


                    </div>
                    <div class="box-header with-border">
                        @if($dados->getRelation('dadosPagamento')->metodo_pagamento_id == 9) {{--pagamento por ted--}}
                        <i class="glyphicon glyphicon-list-alt"></i>
                        <h3 class="box-title">Confirmação de pagamento</h3>
                        @endif
                    </div>
                    <div class="box-footer">
                        @if($dados->getRelation('dadosPagamento')->metodo_pagamento_id == 9) {{--pagamento por ted--}}
                        <form method="post" action="{{ route('pedido.usuario.pedido.confirmar.ted', $dados->id) }}">
                            {!! csrf_field() !!}
                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">Data de efetivação da transferência</label>
                                <input type="text" name="data_pagamento_efetivo" id="data_pagamento_efetivo"
                                       required="true"
                                       value="{{ old('data_pagamento_efetivo') }}"
                                       class="form-control datepicker" id="exampleInputEmail1"
                                       placeholder="Data de efetivação da transferência">
                            </div>
                            {{--<div class="form-group">
                                <label for="exampleInputEmail1">Informe o valor efetivo da transferência</label>
                                <div class="input-group">
                                    <span class="input-group-addon">R$</span>
                                    <input type="text" required="true" name="valor_efetivo_real" id="valor_efetivo_real" value="{{old('valor_efetivo_real') }}" class="form-control"  placeholder="Informe o valor efetivo da transferência">
                                    <input type="button" id="cotar" class="btn btn-primary" onclick="cotacaoDolar()" value="Calcular cotação do dolar no dia da efetivação">
                                </div>
                            </div>--}}
                            {{--                     <div class="form-group col-xs-12 col-lg-6">
                                                     <label for="exampleInputEmail1">Cotação do dolar na data da efetivação da transferência</label>
                                                     <input type="text" readonly="true" required="true" name="cotacao_dolar_dia_efetivo" id="cotacao_dolar_dia_efetivo" value="{{ old('cotacao_dolar_dia_efetivo')}}"
                                                            class="form-control"
                                                            placeholder="Cotação do dolar">
                                                 </div>
                                                 <div class="form-group col-xs-12 col-lg-6">
                                                     <label for="exampleInputEmail1">Valor efetivo na data de confirmação da transferência</label>
                                                     <div class="input-group">
                                                         <span class="input-group-addon">{{$sistema->moeda}}</span>
                                                         <input type="text" readonly="true" required="true" name="valor_efetivo" id="valor_efetivo" value="{{ old('valor_efetivo') }}"
                                                                class="form-control"
                                                                placeholder="Valor efetivo">
                                                     </div>
                                                 </div>--}}
                            {{--            <div class="form-group col-md-12">
                                            <label for="exampleInputEmail1">Valor autorizado pela diretoria</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">{{$sistema->moeda}}</span>
                                                <input type="text" required="true" name="valor_autorizado_diretoria" id="valor_autorizado_diretoria" value="{{ old('valor_autorizado_diretoria') }}"
                                                       class="form-control"
                                                       placeholder="Valor autorizado pela diretoria">
                                            </div>
                                        </div>--}}
                            <div class="form-group col-md-12">
                                <label>Observação do pagamento</label>
                                <input type="text" required="true" name="documento" class="form-control" value="{{ old('documento') }}"
                                       placeholder="Observação do pagamento">
                            </div>
                            <div class="form-group col-md-6">
                                <button  type="submit" class="btn btn-primary" >
                                    Confirmar pagamento
                                </button>
                                {{--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-comprovante" >
                                    Confirmar pagamento
                                </button>--}}
                            </div>
                        </form>
                        {{--<div class="modal fade" id="modal-comprovante" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span></button>
                                        <h4 class="modal-title">Atenção</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Você está confirmando que essa transferência bancária foi concluída com sucesso</strong>, para prosseguir confirme abaixo.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                                        <form method="post" action="{{ route('pedido.usuario.pedido.confirmar.ted', $dados->id) }}">
                                            {!! csrf_field() !!}
                                            --}}{{--<input type="hidden" name="pedido_id" value="{{ $dados->id }}">--}}{{--
                                            --}}{{--<input type="hidden" name="user_id" value="{{ $dados->user_id }}">--}}{{--
                                            --}}{{--<input type="hidden" name="metodo_pagamento_id" value="{{$dados->getRelation('dadosPagamento')->metodo_pagamento_id}}">--}}{{--
                                            --}}{{--<input type="hidden" name="conta_empresa_id" value="{{$ted->id}}">--}}{{--
                                            <button type="submit" class="btn btn-primary">Confirmar</button>
                                        </form>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>--}}

                        {{--<div class="form-group col-md-6">
                            <button type="submit" class="btn btn-primary" >
                                Confirmar pagamento
                            </button>
                            --}}{{--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-comprovante" >
                                Confirmar pagamento
                            </button>--}}{{--
                        </div>--}}
                        @endif
                        <a href="{{route('pedido.aguardando-confirmacao') }}" class="btn btn-primary pull-right">Voltar</a>
                    </div>
                </div><!-- /.box -->
            </div><!--/.col (left) -->
        </div>   <!-- /.row -->
    </section>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css')}}">
@endsection

@section('script')
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js')}}"></script>
    <script src="{{ asset('plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js')}}"></script>

    <!-- InputMask -->
    <script src="../../plugins/input-mask/jquery.inputmask.js"></script>
    <script src="../../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="../../plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <script src="{{asset('js/jquery.mask.min.js')}}"></script>

    <script>
        $(function () {
            $("input[name='dt_pagamento'], input[name='data_vencimento'], input[name='data_pagamento_efetivo']").inputmask({
                mask: '99/99/9999',
                showTooltip: true,
                showMaskOnHover: true
            });

            $.fn.datepicker.defaults.language = 'pt-BR';

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy'
            });

            $('#form-pedido').submit(function (event) {
                //event.preventDefault();
                $('#botao-confirmar').addClass('hidden');
                $('#alerta').html('Para tentar novamente, atualize a página!');
            });

        })

        $('#valor_efetivo_real').mask('#.##0,00', {reverse: true});
        $('#valor_efetivo').mask('#.##0,00', {reverse: true});
        $('#valor_autorizado_diretoria').mask('#.##0,00', {reverse: true});

        function cotacaoDolar() {
            var data = document.getElementById("data_pagamento_efetivo").value;
            var valorEfetivo = document.getElementById("valor_efetivo_real").value;
            var patternValidaData = /^(((0[1-9]|[12][0-9]|3[01])([-.\/])(0[13578]|10|12)([-.\/])(\d{4}))|(([0][1-9]|[12][0-9]|30)([-.\/])(0[469]|11)([-.\/])(\d{4}))|((0[1-9]|1[0-9]|2[0-8])([-.\/])(02)([-.\/])(\d{4}))|((29)(\.|-|\/)(02)([-.\/])([02468][048]00))|((29)([-.\/])(02)([-.\/])([13579][26]00))|((29)([-.\/])(02)([-.\/])([0-9][0-9][0][48]))|((29)([-.\/])(02)([-.\/])([0-9][0-9][2468][048]))|((29)([-.\/])(02)([-.\/])([0-9][0-9][13579][26])))$/;
            var patternValidaMoeda = /^([1-9]{1}[\d]{0,2}(\.[\d]{3})*(\,[\d]{0,2})?|[1-9]{1}[\d]{0,}(\,[\d]{0,2})?|0(\,[\d]{0,2})?|(\,[\d]{1,2})?)$/;
            if(patternValidaData.test(data)){
                if(valorEfetivo!= "" && patternValidaMoeda.test(valorEfetivo)) {
                    var dataAux = data.split("/");
                    data = dataAux[2] + dataAux[1] + dataAux[0];

                    var url = "https://economia.awesomeapi.com.br/json/list/USDT-BRL/?start_date=" + data + "&end_date=" + data;

                    $.ajax({
                        type: 'GET',
                        url: url,
                        success: function (data) {
                            if(data.length > 0) {
                                $.each(data[0], function (index, value) {
                                    if (index == 'ask') {
                                        var cotacaoDolar = value;

                                        valorEfetivo = valorEfetivo.replace(".", "").replace(",", ".");

                                        var valorDolar = valorEfetivo / cotacaoDolar;
                                        valorDolar = valorDolar.toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+\,)/g, "$1.");

                                        document.getElementById("cotacao_dolar_dia_efetivo").value = cotacaoDolar;
                                        document.getElementById("valor_efetivo").value = valorDolar;
                                    }
                                });
                            }
                            else{
                                alert("Não foi possível recuperar a cotação do dolar da data informada!")
                                document.getElementById("cotacao_dolar_dia_efetivo").value = "";
                                document.getElementById("valor_efetivo").value = "";
                            }
                        }

                    });
                }else{
                    alert('Informe um valor efetivo válido para transferência!');
                }
            }
            else{
                alert('A data informada é inválida!');
            }
        };
    </script>
@endsection