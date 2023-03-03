@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Depósito/TED a efetuar
        </h1>
        @if(isset($movimento))
            <span>
            <small>
                Saldo da Sua Conta: {{ mascaraMoeda($sistema->moeda, $movimento->saldo, 2, true) }}
            </small>
        </span>
        @endif
        <ol class="breadcrumb hidden-xs">
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

                        <h3 class="box-title">Nº de referência do seu pedido: #{{ $dados->id }}</h3>
                    </div>
                    <div class="box-body">
                        <dl class="dl-horizontal">
                            <dt>Status</dt>
                            <dd>{{ $dados->getRelation('status')->name }}</dd>
                            <dt>Associado</dt>
                            <dd>{{ $dados->getRelation('usuario')->name }}</dd>
                            <dt>Valor</dt>
                            <dd>{{mascaraMoeda($sistema->moeda, $dados->getRelation('dadosPagamento')->valor, 2, true) }}</dd>
                            <dt>Data</dt>
                            <dd>{{ $dados->data_compra->format('d/m/Y') }}</dd>
                        </dl>
                    </div><!-- /.box-body -->

                    <div class="box-header with-border">
                        <i class="glyphicon glyphicon-list-alt"></i>
                        <h3 class="box-title">Clique na opção desejada para ver os detalhes:</h3><br>
                        <small class="text-red">{{$mensagemBoleto}}</small><br>

                        @if($empresa)
                            <small class="text-warning">Para pagamentos via TED, por favor contate o suporte! Whatsapp: <a target="_blank" href="https://api.whatsapp.com/send?phone={{preg_replace('/[^0-9]/', '', $empresa->celular)}}">{{$empresa->celular}}</a></small>
                            <br>
                        @endif
                        <small class="text-info">Pagamentos via PagSeguro e PayPal podem ser parcelados (consulte as taxas diretamente com eles) </small>
                    </div>
                    <!-- /.box-header -->
                    @if($dados->status != 2 && $dados->status != 3)
                        <div class="box-body">
                            @if(isset($metodoPagamento))
                                <div class="col-md-12">
                                    @foreach($metodoPagamento as $metodoPagto)
                                        @if($metodoPagto->id == 1){{--boleto--}}
                                        @if($exibeBoleto)
                                            {{--<div class="form-group col-md-1">--}}
                                            <button name="boleto" value="boleto" class="btn btn-danger botao-pagamento modal-show" data-toggle="modal" data-target="#modal-boleto">Boleto <i class="glyphicon glyphicon-barcode"></i></button>
                                            {{--</div>--}}
                                            <div class="modal fade" id="modal-boleto" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span></button>
                                                            <h4 class="modal-title">Atenção</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Você selecionou a forma de pagamento <strong>Boleto</strong>, para prosseguir com a emissão do boleto confirme abaixo.</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default pull-left modal-cancelar" data-dismiss="modal">Cancelar</button>
                                                            <form action="{{ route('pedido.pagar.com.boleto') }}" id="form-boleto" method="post">
                                                                {{ csrf_field() }}
                                                                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                                                                <input type="hidden" name="pedido_id" value="{{$dados->id}}">
                                                                <button id="boleto-confirm" type="submit" class="btn btn-primary modal-confirm">Gerar Boleto</button>
                                                                <img height="40px" id="boleto-load" class="pull-right modal-load" src="{{asset('images/load1.gif')}}">
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                        @endif
                                        @elseif($metodoPagto->id == 2){{--PagSeguro--}}
                                            <button name="pagseguro" id="pagseguro" value="PagSeguro" class="btn btn-success botao-pagamento">PagSeguro</button>
                                        @elseif($metodoPagto->id == 3){{--Paypal--}}
                                                <img id="paypal" class="btn btn-primary botao-pagamento modal-show" data-toggle="modal" data-target="#modal-paypal" src="{{asset('images/MetodoPagamento/paypal.png')}}" alt="Paypal">
                                            <div class="modal fade" id="modal-paypal" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span></button>
                                                            <h4 class="modal-title">Atenção</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Você selecionou a forma de pagamento <strong>PayPal</strong>.</p>
                                                            <p>Para concluir sua transação, redirecionaremos você para os servidores seguros do PayPal.</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default pull-left modal-cancelar" data-dismiss="modal">Cancelar</button>
                                                            <form action="{{ route('paypal.pagar') }}" id="form-paypal" method="post">
                                                                {{ csrf_field() }}
                                                                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                                                                <input type="hidden" name="pedido_id" value="{{$dados->id}}">
                                                                <button type="submit" class="btn btn-primary modal-confirm">Prosseguir</button>
                                                                <img height="40px" class="pull-right modal-load" src="{{asset('images/load1.gif')}}">
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                        @elseif($metodoPagto->id == 9){{--TED - transferencia bancaria--}}
                                            @foreach($contasTed as $ted)
                                                {{--<div class="form-group col-md-6">--}}
                                                    <form method="post" action="{{ route('pedido.verificar-pagamento') }}">
                                                        {!! csrf_field() !!}
                                                        <input type="hidden" name="pedido_id" value="{{ $dados->id }}">
                                                        <input type="hidden" name="user_id" value="{{ $dados->user_id }}">
                                                        <input type="hidden" name="metodo_pagamento_id" value="{{$metodoPagto->id}}">
                                                        <input type="hidden" name="conta_empresa_id" value="{{$ted->id}}">
                                                        <button name="boleto" value="{{ $ted->id }}" class="btn btn-primary botao-pagamento">{{$metodoPagto->name . ' - ' . $ted->getRelation('banco')->nome}} <i class="glyphicon glyphicon-transfer"></i></button>
                                                    </form>
                                                {{--</div>--}}
                                            @endforeach
                                        @elseif($metodoPagto->id == 11){{--AstroPay Card--}}
                                            {{--<button id="astropay" name="astropay" value="astropay" class="btn btn-danger botao-pagamento modal-show" data-toggle="modal" data-target="#modal-astropay-old">{{$metodoPagto->name}} <i class="glyphicon glyphicon-barcode"></i></button>--}}
                                            <img id="astropay" class="btn bg-black botao-pagamento modal-show" data-toggle="modal" data-target="#modal-astropay-old" src="{{asset('images/MetodoPagamento/astropay-button.png')}}" alt="AstroPay">
                                            <div class="modal fade" id="modal-astropay" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header text-center text-red bg-gray">
                                                            {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span class="text-red" aria-hidden="true">X</span></button>--}}
                                                            <h4 class="modal-title text-bold">Astropay Card</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <dl class="dl-horizontal">
                                                                <dt>Valor</dt>
                                                                <dd>{{mascaraMoeda($sistema->moeda, $dados->getRelation('dadosPagamento')->valor, 2, true) }}</dd>
                                                                <dt>{{$metodoPagto->taxa_descricao}}</dt>
                                                                <dd>{{$sistema->moeda}} <span id="taxa"></span></dd>
                                                                <dt>Total</dt>
                                                                <dd>{{$sistema->moeda}} <span id="total"></span></dd>
                                                            </dl>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <p class="text-left"><label>Informe abaixo os dados do seu cartão AstroPay.</label></p>
                                                            <form action="{{ route('astropaycard.pagar') }}" id="form-astropay" method="post">
                                                                {{ csrf_field() }}
                                                                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                                                                <input type="hidden" name="pedido_id" value="{{$dados->id}}">
                                                                <div class="box-body text-left">
                                                                    <div class="form-group col-xs-12">
                                                                        <label for="numero_cartao">Número do Cartão</label>
                                                                        <input type="text" id="numero_cartao" name="numero_cartao" maxlength="16" value="{{ old('numero_cartao') }}" class="form-control" placeholder="Número do cartão">
                                                                    </div>
                                                                    <div class="form-group col-xs-6 col-lg-6">
                                                                        <label for="cvv">CVV</label>
                                                                        <input type="text" maxlength="4" id="cvv" name="cvv" value="{{ old('cvv') }}" class="form-control" placeholder="CVV">
                                                                    </div>
                                                                    <div class="form-group col-xs-6 col-lg-6">
                                                                        <label for="data_expiracao">Data de expiração</label>
                                                                        <input type="text" name="data_expiracao" value="{{ old('data_expiracao') }}" class="form-control" placeholder="Data de expiração">
                                                                    </div>
                                                                </div>
                                                                <button type="button" id="cancelar-astropay" class="btn btn-warning pull-left modal-cancelar" data-dismiss="modal">Cancelar</button>
                                                                <button id="astropay-confirm" type="submit" class="btn btn-primary modal-confirm">Efetuar pagamento</button>
                                                                <img height="40px" id="boleto-load" class="pull-right modal-load" src="{{asset('images/load1.gif')}}">
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                        @elseif($metodoPagto->name == "Braziliex" && config('braziliex.key')){{--BRAZILIEX - bitcoin--}}
                                            {{--<div class="form-group col-md-6">--}}
                                                <a href="{{ route('pagamento.braziliex', [$dados->id]) }}" class="btn btn-warning botao-pagamento">{{ $metodoPagto->name }} - Bitcoin <i class="glyphicon glyphicon-bitcoin"></i></a>
                                            {{--</div>--}}
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                    <div class="box-footer">
                        @if($dados->tipo_pedido === 4)
                            <a href="{{ route('depositos.aguardando.deposito')}}" class="btn btn-primary pull-right">Voltar</a>
                        @else
                            <a href="{{ route('pedido.usuario.pedidos', Auth::user()->id) }}" class="btn btn-primary pull-right">Voltar</a>
                        @endif
                    </div>
                </div><!-- /.box -->
            </div><!--/.col (left) -->
        </div>   <!-- /.row -->
    </section>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert/sweetalert.css') }}">

    <style>
        .botao-pagamento {
            float: left;
            height: 34px;
            margin: 5px;
        }
    </style>
@endsection

@section('script')
    {{--<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>--}}
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.min.js?v=50') }}"></script>
    <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>

    @if('sandbox' == env('PAGSEGURO_AMBIENTE'))
        <script type="text/javascript"
                src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js">
        </script>
    @else
        <script type="text/javascript"
                src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js">
        </script>
    @endif

    <script>
        $(function(){
            $(document).ready(function() {
                $("#cvv").keyup(function() {
                    $("#cvv").val(this.value.match(/[0-9]*/));
                });

                $("#numero_cartao").keyup(function() {
                    $("#numero_cartao").val(this.value.match(/[0-9]*/));
                });
            });

            $("input[name='data_expiracao']").inputmask({
                mask: ['99/9999'],
                showTooltip: true,
                showMaskOnHover: true,
                clearIncomplete: true
            });

            $('.modal-show').click(function() {
                $('.modal-cancelar').show();
                $('.modal-confirm').show();
                $('.modal-load').hide();
            });

            $('.modal-confirm').click(function() {
                $('.modal-cancelar').hide();
                $('.modal-confirm').hide();
                $('.modal-load').show();
            });

            $("#cancelar-astropay").click(function() {
                $("#modal-astropay").removeClass("in")
                $("#modal-astropay").css({'display': 'none'});
            });

            $('#pagseguro').click(function(){
                swal({
                        title: "Contatando PagSeguro",
                        text: "Solicitando autorização, aguarde...",
                        type: "info",
                        showCancelButton: false,
                        closeOnConfirm: false,
                        timer: 2000,
                        showConfirmButton: false
                    },
                    function(){
                        var solicitacao =  $.get('{{ route('pagseguro.requisicao', $dados->id) }}');

                        //Solicitação de autorização
                        solicitacao.done(function(data){
                            swal.close();
                            var isOpenLightbox = PagSeguroLightbox({
                                code: data
                            }, {
                                success: function (transactionCode) {
                                    var token = '{!! csrf_token() !!}';
                                    var registroPagamento = $.post('{{ route('pagseguro.registrar.pagamento', $dados->id) }}', {'code': transactionCode, '_token': token});

                                    registroPagamento.done(function(data){
                                        swal({
                                                title: "Pagamento Registrado!",
                                                text: "Assim que o pagamento for liberado enviaremos uma notificação no seu e-mail!",
                                                type: "success",
                                                showCancelButton: false,
                                                confirmButtonColor: "#00ff00",
                                                confirmButtonText: "OK",
                                                closeOnConfirm: false,
                                                closeOnCancel: false
                                            },
                                            function (isConfirm) {
                                                if (isConfirm) {
                                                    $('#pagseguro').remove();
                                                    location.href = "{{ route('depositos.aguardando.deposito', Auth::user()->id) }}";
                                                }
                                            }
                                        );
                                    });

                                    registroPagamento.fail(function(data){
                                        swal('Erro!', 'Ocorreu ao registrar o pagamento no sistema. Não se preocupe ela foi registrada pelo PagSeguro e sera registrada posteriormente.', 'error');
                                    });
                                },
                                abort: function () {
                                    swal('Atenção', 'Transaçao cancelada!', 'info');
                                }
                            });

                            // Redirecionando o cliente caso o navegador não tenha suporte ao Lightbox
                            if (!isOpenLightbox) {
                                @if('sandbox' == env('PAGSEGURO_AMBIENTE'))
                                    location.href = "https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html?code=" + code;
                                @else
                                    location.href = "https://pagseguro.uol.com.br/v2/checkout/payment.html?code=" + code;
                                @endif
                            }
                        }, 'json');

                        //Erro na autorização do pagseguro
                        solicitacao.fail(function () {
                            swal('Erro!', 'Ocorreu um erro ao soliciar autorização no Pagseguro. Se o erro persistir contate-nos.', 'error');
                        });
                    });
            });

            $('#astropay').click(function(){
                swal({
                    title: "AstroPay Card",
                    text: "Carregando informações, aguarde...",
                    type: "info",
                    showCancelButton: false,
                    closeOnConfirm: false,
                    timer: 2000,
                    showConfirmButton: false
                },
                function(){
                    $.get('{{route('taxas.pagamento', [Auth::user()->id, $dados->id ])}}')
                    .done(function (data) {
                        swal.close();
                        $("#taxa").html(data.valorTaxa);
                        $("#total").html(data.valorTotal);
                        $("#modal-astropay").addClass("in");
                        $("#modal-astropay").css({'display': 'block', 'padding-right': '15px'});
                    })
                    .fail(function (data) {
                        var msg = 'Ocorreu um erro ao chamar um serviço. Se o erro persistir contate-nos.';
                        if(data.responseJSON)
                            msg = data.responseJSON.message
                        swal('Erro!', msg, 'error');
                    });
                }, 'json');
            });
        });
    </script>
@endsection