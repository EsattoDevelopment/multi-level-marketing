@extends('default.layout.main')

@section('content')

    <section class="content-header">
        <h1>
            <i class="fa fa-angle-left"></i> <a href="{{ route('capitalizacao.index') }}">Voltar</a>
        </h1>
        <ol class="breadcrumb hidden-xs">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('capitalizacao.index') }}">Credenciamentos</a></li>
            <li><b>{{ $item->name }}</b></li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Widget: user widget style 1 -->
                <div class="box box-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header" style="background-color: {{ $item->cor_item }}; color: #ffffff;">
                        <h3 class="widget-user-username" style="font-weight: 700;">{{ $item->name }}</h3>
                        <h5 class="widget-user-desc hidden-xs">Posição em {{ \Carbon\Carbon::now()->format('d/m/Y') }}</h5>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" src="{{ asset('images/logo-fundo.png') }}" alt="{{ env('COMPANY_NAME') }}">
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">{{ mascaraMoeda($sistema->moeda, $totalDeposito, 2, true) }}</h5>
                                    <span class="description-text">Contratado</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">{{ mascaraMoeda($sistema->moeda, $totalMovimento, 2, true) }}</h5>
                                    <span class="description-text">Capitalizado</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">{{ mascaraMoeda($sistema->moeda, $totalMovimento + ((Auth::user()->titulo->habilita_rede && $item->quitar_com_bonus) ? 0 : $totalDeposito), 2, true) }}</h5>
                                    <span class="description-text">Total</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                </div>
                <!-- /.widget-user -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @forelse($pedidos as $pedido)
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <!-- Widget: user widget style 1 -->
                        <div class="box box-widget widget-user">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header" style="background-color: {{ $item->cor_item }}; color: #FFF;">
                                {{--<div class="widget-user-image" style="background-color: #ffffff;">
                                    <img class="img-circle" src="{{ asset('images/logo-fundo.png') }}" alt="User Avatar">
                                </div>--}}
                                <!-- /.widget-user-image -->
                                <h3 class="widget-user-username" style="font-weight: 700;">Contrato Nº {{ $pedido->id }}</h3>
                                <h5 class="widget-user-desc">Confirmado em {{ $pedido->dadosPagamento->data_pagamento_efetivo->format('d/m/Y') }}</h5>
                            </div>
                            <div class="box-footer no-padding">
                                <ul class="nav nav-stacked">
                                    <li><a>Posição em <span class="pull-right badge bg-warning">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</span></a></li>
                                    <li><a>Total <span class="pull-right badge bg-blue">{{ mascaraMoeda($sistema->moeda, $pedido->movimento_total + ((Auth::user()->titulo->habilita_rede && $item->quitar_com_bonus) ? 0 : $pedido->valor_total), 2, true) }}</span></a></li>
                                    <li><a>Contratado <span class="pull-right badge bg-aqua">{{ mascaraMoeda($sistema->moeda, $pedido->valor_total, 2, true) }}</span></a></li>
                                    <li><a>Capitalizado <span class="pull-right badge bg-green">{{ mascaraMoeda($sistema->moeda, $pedido->movimento_total, 2, true) }}</span></a></li>
                                    <li><a>Vigente até<span class="pull-right badge bg-red">{{ $pedido->dadosPagamento->data_pagamento_efetivo->addMonths($item->meses)->format('d/m/Y') }}</span></a></li>

                                    @if($item->habilita_recontratacao_automatica || $pedido->itens->first()->modo_recontratacao_automatica > 0)
                                        <li class="recontratacao-titulo"><a>Ao final do contrato:
                                                <br>
                                        @if($item->habilita_recontratacao_automatica && $pedido->itens->first()->modo_recontratacao_automatica == 0)
                                            {{ array_search($pedido->itens->first()->modo_recontratacao_automatica, config('constants.modo_recontratacao_automatica_exibicao')) }} <br/> <br/>
                                                @else
                                            @if($pedido->itens->first()->modo_recontratacao_automatica > 0)
                                                {{ array_search($pedido->itens->first()->modo_recontratacao_automatica, config('constants.modo_recontratacao_automatica_exibicao')) }} <br/> @if($pedido->itens->first()->modo_recontratacao_automatica > 0) @if($pedido->itens->first()->modo_recontratacao_automatica == 1) {{ mascaraMoeda($sistema->moeda, $pedido->itens->first()->valor_total, 2, true) }} @else {{ mascaraMoeda($sistema->moeda, ($pedido->valor_total * $pedido->itens->first()->total_meses_contrato * ($pedido->itens->first()->potencial_mensal_teto / 100) + $pedido->valor_total), 2, true) }}  @endif @endif
                                                @endif
                                        @endif
                                            </a></li>
                                    @endif

                                    @if($item->habilita_recontratacao_automatica && $pedido->itens->first()->modo_recontratacao_automatica == 0)
                                        <li class="recontratacao-off"><a href="javascript:;" data-toggle="modal" data-target="#modal-pedido-{{ $pedido->id }}"> Clique para ativar novo contrato automático </a></li>
                                    @else
                                        @if($pedido->itens->first()->modo_recontratacao_automatica > 0)
                                            <li class="recontratacao-on"><a href="javascript:;" data-toggle="modal" data-target="#modal-pedido-{{ $pedido->id }}">Clique para desativar novo contrato automático </a></li>
                                        @endif
                                    @endif
                                    <li class="bg-gray-active"><a target="_blank" class="btn" href="{{ route('pedido.usuario.contrato', $pedido->id) }}"><i class="fa fa-print"></i> Imprimir contrato</a></li>
                                    <li class="bg-gray text-center"><a href="{{ route('capitalizacao.pedido', [$pedido, $item]) }}" class="text-bold">Clique para ver mais detalhes</a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- /.widget-user -->
                    </div>

                    <div class="modal fade" id="modal-pedido-{{ $pedido->id }}" data-id="{{ $pedido->id }}" style="display: none;">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span></button>
                                    <h4 class="modal-title">O que fazer na finalização do contrato ?</h4>
                                </div>
                                <form action="{{ route('pedido.modorecontratacao') }}" class="form-modo-recontratacao" method="post">
                                    <input type="hidden" name="code">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Ao final do contrato:</label>
                                            <div class="form-group">
                                                <label>
                                                    <input type="radio" value="0" name="modo_recontratacao_automatica_{{ $pedido->itens->first()->id }}" class="flat-red" {{ old("modo_recontratacao_automatica_$pedido->itens->first()->id", $pedido->itens->first()->modo_recontratacao_automatica)  == 0 ? 'checked' : '' }}>
                                                    {{ array_search(0, config('constants.modo_recontratacao_automatica_exibicao')) }}.
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <label>
                                                    <input type="radio" value="1" name="modo_recontratacao_automatica_{{ $pedido->itens->first()->id }}" class="flat-red" {{ old("modo_recontratacao_automatica_$pedido->itens->first()->id", $pedido->itens->first()->modo_recontratacao_automatica)  == 1 ? 'checked' : '' }}>
                                                    {{ array_search(1, config('constants.modo_recontratacao_automatica_exibicao')) }}. {{ mascaraMoeda($sistema->moeda, $pedido->itens->first()->valor_total, 2, true) }}
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <label>
                                                    <input type="radio" value="2" name="modo_recontratacao_automatica_{{ $pedido->itens->first()->id }}" class="flat-red" {{ old("modo_recontratacao_automatica_$pedido->itens->first()->id", $pedido->itens->first()->modo_recontratacao_automatica)  == 2 ? 'checked' : '' }}>
                                                    {{ array_search(2, config('constants.modo_recontratacao_automatica_exibicao')) }}. {{ mascaraMoeda($sistema->moeda, ($pedido->valor_total * $pedido->itens->first()->total_meses_contrato * ($pedido->itens->first()->potencial_mensal_teto / 100) + $pedido->valor_total), 2, true) }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                                        {{ csrf_field() }}
                                        <input type="hidden" name="pedido_id" value="{{ $pedido->id }}">
                                        <input type="hidden" name="pedido_item_id" value="{{ $pedido->itens->first()->id }}">
                                        <input type="hidden" name="modo_recontratacao_automatica_original" value="{{ $pedido->itens->first()->modo_recontratacao_automatica }}">
                                        <button type="submit" class="btn btn-primary">Confirmar</button>

                                    </div>
                                </form>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </section>
@endsection

@section('style')

    <link rel="stylesheet" href="{{ asset('plugins/sweetalert/sweetalert.css') }}">

    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/square/red.css') }}">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/extensions/Responsive/css/dataTables.responsive.css') }}">
    <style>
        .timeline .box{
            position: inherit;
            border-radius: inherit;
            background: none;
            border-top: none;
            margin-bottom: inherit;
            width: inherit;
            box-shadow: inherit;
        }

        .timeline-item p:last-child{
            margin-bottom: 0px;
        }

        ul.timeline:not(:first-child) {
            margin-top: 40px;
        }

        .timeline-header span[data-widget='collapse']{
            cursor: pointer;
        }

        .timeline-item.box-body .time{
            color: #999;
            float: right;
            padding: 0px;
            font-size: 12px;
        }

        li.recontratacao-titulo{
            background-color: {{ $item->cor_item }};
            color: #ffffff !important;
        }

        li.recontratacao-titulo a{
            color: #ffffff !important;
            text-align: center;
        }

        li.recontratacao-titulo a:hover,li.recontratacao-on a:active, li.recontratacao-on a:visited, li.recontratacao-on a:focus {
            background-color: {{ $item->cor_item }} !important;
            color: #ffffff !important;
        }


        li.recontratacao-on {
            background: #124771;
            color: white !important;
        }

        li.recontratacao-on a{
            color: white !important;
            text-align: center;
        }

        li.recontratacao-on a:hover,li.recontratacao-on a:active, li.recontratacao-on a:visited, li.recontratacao-on a:focus {
            background: #12254a !important;
            color: white !important;
        }

        li.recontratacao-off {
            background: #00a65a;
            color: white !important;
        }

        li.recontratacao-off a{
            color: white !important;
            text-align: center;
        }

        li.recontratacao-off a:hover,li.recontratacao-off a:active, li.recontratacao-off a:visited, li.recontratacao-off a:focus {
            background: #005900 !important;
            color: white !important;
        }

    </style>
@endsection

@section('script')

    <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>

    <!-- iCheck -->
    <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>


    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/backend/datatables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/bootstrap-confirmation.js') }}" type="text/javascript"></script>
    <script>
        $(function(){
            Number.prototype.formatMoney = function(places, symbol, thousand, decimal) {
                places = !isNaN(places = Math.abs(places)) ? places : 2;
                symbol = symbol !== undefined ? symbol : "$";
                thousand = thousand || ",";
                decimal = decimal || ".";
                var number = this,
                    negative = number < 0 ? "-" : "",
                    i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
                    j = (j = i.length) > 3 ? j % 3 : 0;
                return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
            };

            $('.box-movimento').bind({
                expanded: function (e) {
                    var element = $(e.target);
                    var p = element.data('pedido');
                    if  (p) {
                        if (! $.fn.DataTable.isDataTable('#tabela_index_' + p)) {
                            $('#tabela_index_' + p).DataTable({
                                responsive: true,
                                processing: true,
                                serverSide: true,
                                searchDelay: 500,
                                ajax: {
                                    type: 'GET',
                                    url: '{!! route('deposito.extrato.json') !!}',
                                    data: {id: p}
                                },
                                columns: [
                                    {data: 'valor_manipulado', name: 'pm.valor_manipulado'},
                                    {data: 'porcentagem', name: 'porcentagem', orderable: false, searchable: false},
                                    {data: 'created_at', name: 'pm.created_at'},
                                    {data: 'descricao', name: 'pm.descricao'},
                                ],
                                "ordering": false,
                                footerCallback: function (row, data, start, end, display) {
                                    var api = this.api(), data;

                                    // converting to interger to find total
                                    var intVal = function ( i ) {
                                        return typeof i === 'string' ?
                                            i.replace(/[\$,\%,]/g, '')*1 :
                                            typeof i === 'number' ?
                                                i : 0;
                                    };

                                    // computing column Total of the complete result
                                    var capitalizado = api
                                        .column(0)
                                        .data()
                                        .reduce( function (soma, valor, index) {
                                            //pivot = data[index].operacao_id == 26 ? -intVal(valor.replace('(', '').replace(')', '')) : intVal(valor);
                                            pivot = data[index].operacao_id == 26 ? 0 : intVal(valor);
                                            return intVal(soma) + pivot;
                                        }, 0);

                                    var transferido = api
                                        .column(0)
                                        .data()
                                        .reduce( function (soma, valor, index) {
                                            //pivot = data[index].operacao_id == 26 ? -intVal(valor.replace('(', '').replace(')', '')) : intVal(valor);
                                            pivot = data[index].operacao_id != 26 ? 0 : intVal(valor.replace('(', '').replace(')', ''));
                                            return intVal(soma) + pivot;
                                        }, 0);

                                    var porcentagem = api
                                        .column(1)
                                        .data()
                                        .reduce( function (soma, valor, index) {
                                            pivot = data[index].operacao_id == 26 ? 0 : intVal(valor);
                                            return intVal(soma) + pivot;
                                        }, 0);

                                    // Update footer by showing the total with the reference of the column index
                                    $(api.column(0).footer()).html('Total Pago: ' + capitalizado.formatMoney(2, "{{ $sistema->moeda }}", ",", "."));
                                    $(api.column(1).footer()).html('Percentual: ' + porcentagem.toFixed(2) + "%");
                                    $(api.column(3).footer()).html('Transferido para Carteira: ' + transferido.formatMoney(2, "{{ $sistema->moeda }}", ",", "."));
                                },
                            });
                        }
                    }
                }
            });

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red',
                increaseArea: '20%' // optional
            });

            $('.modal').on('hidden.bs.modal', function () {

                formulario = $(this);

                pedidoItemId = formulario.find('input[name="pedido_item_id"]').val();
                modoRecontratacaoAutomaticaOriginal = formulario.find('input[name="modo_recontratacao_automatica_original"]').val();
                $('input').filter('[name="modo_recontratacao_automatica_' + pedidoItemId + '"]').filter('[value="' + modoRecontratacaoAutomaticaOriginal + '"]').iCheck('check');
            });

            $('.form-modo-recontratacao').submit(function(event){
                event.preventDefault();

                @if($sistema->habilita_autenticacao_recontratacao && (Auth::user()->google2fa_secret == null || strlen(Auth::user()->google2fa_secret) == 0))
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
                return false;

                @else

                    formulario = $(this);
                    pedidoId = formulario.find('input[name="pedido_id"]').val();

                    @if($sistema->habilita_autenticacao_recontratacao)

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
                            url: "{{ route('recontratacao.validate.2fa') }}",
                            type: 'POST',
                            data: {code: inputValue},
                            success: function(){
                                $("input[name='code']").val(inputValue);
                                formulario.unbind('submit').submit();
                                swal("Enviando...", "Modo de recontratação automática atualizado com sucesso!", "success");
                            },
                            error: function(){
                                swal.showInputError("Código inválido, tente novamente.");
                                return false;
                            }
                        });
                    });
                    @else
                        formulario.unbind('submit').submit();
                        swal("Enviando...", "Modo de recontratação automática atualizado com sucesso!", "success");
                    @endif

                @endif
            });
        });
    </script>
@endsection
