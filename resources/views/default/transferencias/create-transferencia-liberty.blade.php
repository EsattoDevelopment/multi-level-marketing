@extends('default.layout.main')

@section('content')
    <section class="content-header">
        <h1>
            Transferência entre contas {{ ucfirst(env('COMPANY_NAME_SHORT', 'empresa')) }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Transferências</li>
            <li class="active">Nova</li>
        </ol>
    </section>

    <section class="content" id="content">
        @include('default.errors.errors')

        <div class="col-lg-6" style="float: none; margin: 0 auto;">
            <form action="{{ route('transferencia.store') }}" method="post" id="transferencia">
                {{ csrf_field() }}
                <div class="box box-solid">
                    <div class="box-body" style="text-align: center;">

                        <h2><b>Quanto</b> você quer <b>Transferir</b>?</h2>
                        <input type="text" name="valor" v-model="transferir" placeholder="{{ $sistema->moeda }} 0,00" min="{{ round($sistema->min_transferencia) }}" value="{{ old('valor') }}" data-affixes-stay="true" data-prefix="{{ $sistema->moeda }} " data-thousands="." data-decimal=",">
                        <br><small :class="{'text-red': excede}">Saldo disponível: {{ $sistema->moeda }}@{{saldo | price}}</small>
                        <p>Valor mínimo de {{ mascaraMoeda($sistema->moeda, $sistema->min_transferencia, 2, true) }}.</p>

                    </div>
                </div>
                <div class="box box-solid">
                    <div class="box-body" style="text-align: center;">
                        <h2>Dados destinatário</h2>
                        <section style="overflow: hidden;" id="destinatario">
                            <div class="form-group pull-left col-sm-12 col-lg-4">
                                <label for="">Agência</label> <br>
                                <input type="text" name="agencia" v-model="agencia" placeholder="Agência" value="{{ old('agencia') }}">
                            </div>
                            <div class="form-group pull-left col-sm-12 col-lg-4">
                                <label for="">Conta</label> <br>
                                <input type="text" name="conta" v-model="conta" placeholder="Conta" value="{{ old('conta') }}">
                            </div>
                            <div class="form-group pull-left col-sm-12 col-lg-4">
                                <label for="">Digito conta</label> <br>
                                <input type="text" name="digito_conta" v-model="digito_conta" placeholder="Digito Conta" value="{{ old('digito_conta') }}">
                            </div>
                            <input type="hidden" name="user" value="">
                        </section>
                        <button type="submit" class="btn btn-primary btn-block">TRANSFERIR</button>

                    </div>
                </div>
            </form>

            <div class="modal fade" id="modal" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Parabéns</h4>
                        </div>
                        <div class="modal-body">
                            <p>Para prosseguir clique no botão confirmar.</p>
                            <h2>$ {{--{{ mascaraMoeda($sistema->moeda, $sistema->min_deposito, 2, true) }}--}}</h2>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Contratar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

    </section>
@endsection

@section('style')
    <style>
        #transferencia h2{margin-top: 5px;}
        #transferencia input{ text-align: center; background: none; border: none; padding-bottom: 5px; border-bottom: 1px solid #000; font-size: 30px; font-family: 'Source Sans Pro',sans-serif; margin: 10px 0px 20px 0; max-width: 230px; outline: none; }
        #destinatario input{ max-width: 186px; }

    </style>
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert/sweetalert.css') }}">
@endsection

@section('script')
    <script src="{{ asset('js/jquery.maskMoney.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>

    <script>
        $(function(){
            $("input[name='valor']").maskMoney().maskMoney('mask');
            $("input[name='valor']").on('keyup', function (e) {
                e = e || window.event;
                var key = e.which || e.charCode || e.keyCode,
                    keyPressedChar,
                    selection,
                    startPos,
                    endPos,
                    value;
                selection = getInputSelection();
                startPos = selection.start;
                maskAndPosition(startPos + 1);
            });

            $('#transferencia').submit(function(event){
                event.preventDefault();

                swal({
                        title: "Atenção!",
                        html: true,
                        text: "Confimar a transferência?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#0B9918",
                        confirmButtonText: "Sim",
                        cancelButtonText: "Cancelar",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            $('#transferencia').unbind('submit').submit();
                            swal("Enviando...", "Guia sendo enviada!", "success");
                        } else {
                            swal("Cancelado", "Cancelado com sucesso!", "error");
                        }
                    });
            });
        });

        var app = new Vue({
            el: "#content",
            data: {
                saldo: {{ (Auth::user()->ultimoMovimento() ? Auth::user()->ultimoMovimento()->saldo : 0) - $saldoBloqueado }},
                excede: false,
                transferir: 0,
                agencia: 0,
                conta: 0,
                digito_conta: 0
            },
            watch: {

            },
            filters: {
                price: function (value) {
                    let val = (value/1).toFixed(2).replace('.', ',')
                    return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                }
            }
        });
    </script>
@endsection
