@extends('default.layout.main')

@section('content')
    <section class="content-header">
        <h1>
            Transferência para sua conta bancária
        </h1>
        <ol class="breadcrumb hidden-xs">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Transferências</li>
            <li class="active">Nova</li>
        </ol>
    </section>

    <section class="content" id="content">
        @include('default.errors.errors')

        <div class="col-lg-6" style="float: none; margin: 0 auto;">
            <div class="box box-solid">
                <div class="box-body" style="text-align: center;">
                    <form action="{{ route('transferencia.store') }}" method="post" id="transferencia">
                        {{ csrf_field() }}
                        <input type="hidden" name="code">
                        <h2><b>Quanto</b> você quer <b>Transferir</b>?</h2>
                        <input type="text" name="valor" v-model="transferir" placeholder="{{ $sistema->moeda }} 0,00" min="{{ round($sistema->min_transferencia) }}" value="{{ old('valor') }}" data-affixes-stay="true" data-prefix="{{ $sistema->moeda }} " data-thousands="." data-decimal=",">
                        <br><small :class="{'text-red': excede}">Saldo disponível: {{ $sistema->moeda }}@{{saldo | price}}</small>
                        <br>
                        <label class="pull-left">Contas cadastradas</label>
                        @if($dados_bancarios->count() > 0)
                            <select class="pull-left form-control" required name="conta_id" id="banco">
                                <option value=" ">Escolha uma conta bancária</option>
                                @foreach($dados_bancarios as $dados)
                                    <option {{ old('conta_id') == $dados->id ? 'selected' : '' }} value="{{ $dados->id }}">{{ $dados->bancoReferencia->nome }} - {{ $dados->agencia }} -{{ $dados->conta }}</option>
                                @endforeach
                            </select>
                        @else
                            <br>
                            <h2 class="text-red">
                                Você não tem contas bancárias verificadas.
                            </h2>
                            <p>
                                Clique <a href="{{ route('dados-usuario.dados-bancarios') }}">aqui</a> para cadastrar contas ou aguarde a aprovação da conta bancária já cadastrada.
                            </p>
                        @endif
                        <br>
                        <br>
                        <button type="submit" class="btn btn-primary btn-block">TRANSFERIR</button>
                        @if($sistema->transferencia_interna_valor_minimo > 0)
                            <p>Valor mínimo de {{ mascaraMoeda($sistema->moeda, $sistema->transferencia_externa_valor_minimo, 2, true) }}.</p>
                        @endif
                        <small>Prazo para processamento da transferência: até {{ $sistema->dias_para_transferencia }} dias úteis</small><br>
                        @if($sistema->transferencia_externa_valor_taxa > 0)
                            @if($sistema->transferencia_externa_valor_minimo_gratis > $sistema->transferencia_externa_valor_minimo )
                                @if($transferencia_gratuitas_restante === 'Ilimitada')
                                    <small>Tarifa: transferências gratuitas para valores a partir de {{mascaraMoeda($sistema->moeda, $sistema->transferencia_externa_valor_minimo_gratis, 2, true)}}</small><br>
                                    <small>Demais transferências: taxa de {{ mascaraMoeda($sistema->moeda, $sistema->transferencia_externa_valor_taxa, 2, true) }}</small>
                                @else
                                    <small>Tarifa: {{$sistema->transferencia_externa_qtde_gratis}} transferências gratuitas por mês para valores a partir de {{mascaraMoeda($sistema->moeda, $sistema->transferencia_externa_valor_minimo_gratis, 2, true)}}</small><br>
                                    <small>Demais transferências: taxa de {{ mascaraMoeda($sistema->moeda, $sistema->transferencia_externa_valor_taxa, 2, true) }}</small><br>
                                    @if($transferencia_gratuitas_restante > 0)
                                        <small id="taxa_v">Transferências gratuitas restantes: {{ $transferencia_gratuitas_restante }}</small>
                                    @else
                                        <small id="taxa">Você já utilizou todas as suas transferências gratuitas</small>
                                    @endif
                                @endif
                            @else
                                @if($transferencia_gratuitas_restante === 'Ilimitada')
                                    <small>Tarifa: gratuita</small>
                                @else
                                    <small>Tarifa: {{$sistema->transferencia_externa_qtde_gratis}} transferências gratuitas por mês</small><br>
                                    <small>Demais transferências: taxa de {{ mascaraMoeda($sistema->moeda, $sistema->transferencia_externa_valor_taxa, 2, true) }}</small><br>
                                    @if($transferencia_gratuitas_restante > 0)
                                        <small id="taxa_v">Transferências gratuitas restantes: {{ $transferencia_gratuitas_restante }}</small>
                                    @else
                                        <small id="taxa">Você já utilizou todas as suas transferências gratuitas</small>
                                    @endif
                                @endif
                            @endif
                        @else
                            <small>Tarifa: gratuita</small>
                        @endif
                    </form>
                </div>
            </div>
        </div>

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
        .sweet-alert input{ text-align: center; }
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

                @if($sistema->habilita_autenticacao_transferencias&& (Auth::user()->google2fa_secret == null || strlen(Auth::user()->google2fa_secret) == 0))
                    swal({
                        title: "Atenção",
                        text: 'Esta operação necessita que a verificação de 2 fatores esteja ativa',
                        type: "info",
                        showCancelButton: true,
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true,
                        confirmButtonText: "Ativar agora",
                        cancelButtonText: "Cancelar",
                    }, function () {
                        window.location = "{{ route('dados-usuario.seguranca') }}";
                    });

                    this.modal('hide');
                    return;

                @else
                    valor = $('input[name="valor"]').val();
                    valor_calc = valor.replace("{{$sistema->moeda}}", "").replace(".","").replace(",",".").replace(" ", "");
                    total_calc = 0;
                    msg = "Confimar a transferência de <b>" + valor + '</b>?';
                    //verifico se vai cobrar taxa
                    if ($("#taxa").length){
                        total_calc = parseFloat(valor_calc) + parseFloat({{ $sistema->transferencia_externa_valor_taxa }});
                        msg = "Confimar a transferência de <b>" + valor + '</b> ';
                        msg += 'mais a taxa de {{ mascaraMoeda($sistema->moeda, $sistema->transferencia_externa_valor_taxa, 2, true) }}';
                        msg += ' totalizando {{$sistema->moeda}} ' + total_calc.toLocaleString('pt-BR', { minimumFractionDigits: 2}) + "?";
                    }else if ($("#taxa_v").length){
                        if(valor_calc < {{ $sistema->transferencia_externa_valor_minimo_gratis }}) {
                            total_calc = parseFloat(valor_calc) + parseFloat({{$sistema->transferencia_externa_valor_taxa }});
                            msg = "Confimar a transferência de <b>" + valor + '</b> ';
                            msg += 'mais a taxa de {{ mascaraMoeda($sistema->moeda, $sistema->transferencia_externa_valor_taxa, 2, true) }}';
                            msg += ' totalizando {{$sistema->moeda}} ' + total_calc.toLocaleString('pt-BR', { minimumFractionDigits: 2}) + "?";
                        }
                    }

                    swal({
                        title: "Atenção!",
                        html: true,
                        text: msg,
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#0B9918",
                        confirmButtonText: "Sim",
                        cancelButtonText: "Cancelar",
                        closeOnConfirm: false,
                        closeOnCancel: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            @if($sistema->habilita_autenticacao_transferencias)

                            swal({
                                title: "Atenção!",
                                text: "Informe o código gerado no aplicativo<br/> <b>Google Authenticator</b>.",
                                type: "input",
                                showCancelButton: true,
                                closeOnConfirm: false,
                                showLoaderOnConfirm: true,
                                inputPlaceholder: "",
                                confirmButtonText: "Validar",
                                cancelButtonText: "Cancelar",
                                html: true,
                            }, function (inputValue) {
                                if (inputValue === false) return false;
                                if (inputValue === "") {
                                    swal.showInputError("Você precisa informar o código para continuar.");
                                    return false
                                }

                                $.ajax({
                                    url: "{{ route('transferencia.validate.2fa') }}",
                                    type: 'POST',
                                    data: {code: inputValue},
                                    success: function(){
                                        $("input[name='code']").val(inputValue);
                                        $('#transferencia').unbind('submit').submit();
                                        swal("Enviando...", "Gerando transferência!", "success");
                                    },
                                    error: function(){
                                        swal.showInputError("Código inválido, tente novamente.");
                                        return false;
                                    }
                                });
                            });
                            @else
                            $('#transferencia').unbind('submit').submit();
                            swal("Enviando...", "Gerando transferência!", "success");
                            @endif
                        }
                    });
                @endif
            });

            @if(!Auth::user()->validado)
            swal({
                    title: "Aviso",
                    html: true,
                    text: "<p class='text-red'>Para você fazer transferências, seus documentos precisam estar aprovados!</p>\n" +
                        "Se você ainda não os enviou, <a href=\"{{ route('dados-usuario.identificacao') }}\">aqui</a> para enviar agora.\n" +
                        "Caso já os tenha enviado, aguarde a aprovação.",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#00ff00",
                    confirmButtonText: "OK",
                    cancelButtonText: "Cancelar",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }
                @if(!Auth::user()->google2fa_secret)
                ,
                function (isConfirm) {
                    if (isConfirm) {
                        swal({
                            title: "Atenção",
                            html: true,
                            text: "Para sua segurança, esta operação requer que você ative a \"Autenticação de 2 fatores\", <a href=\"{{ route('dados-usuario.seguranca') }}\">clique aqui</a>" +
                                " para ativar.",
                            type: "error",
                            showCancelButton: false,
                            confirmButtonColor: "#00ff5a",
                            confirmButtonText: "OK",
                            cancelButtonText: "Cancelar",
                            closeOnConfirm: false,
                            closeOnCancel: false
                        });
                    }
                }
            @endif
);
            @endif

            {{--@if(!Auth::user()->google2fa_secret)
            swal({
                title: "Atenção",
                html: true,
                text: "Esta operação necessita que a verificação de 2 fatores esteja ativa, <a href=\"{{ route('dados-usuario.seguranca') }}\">clique aqui</a>" +
                    " para ativar.",
                type: "warning",
                showCancelButton: false,
                confirmButtonColor: "#00ff00",
                confirmButtonText: "OK",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false,
                closeOnCancel: false
            });
            @endif--}}
        });

        var app = new Vue({
            el: "#content",
            data: {
                saldo: {{ (Auth::user()->ultimoMovimento() ? Auth::user()->ultimoMovimento()->saldo : 0)}},
                excede: false,
                transferir: 0
            },
            /*watch: {
                transferir: function (val) {
                    console.log('teste');
                    this.input = val > this.saldo;
                }
            },*/
            filters: {
                price: function (value) {
                    let val = (value/1).toFixed(2).replace('.', ',')
                    return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                }
            }
        });
    </script>
@endsection
