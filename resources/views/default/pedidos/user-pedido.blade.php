@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Meus pedidos
        </h1>
        <ol class="breadcrumb hidden-xs">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Meus pedidos</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#pagos" data-toggle="tab">Confirmados/Contratados <span class="badge bg-green">{{ $pedidos_pagos->count() }}</span></a></li>
                        <li><a href="#concluidos" data-toggle="tab">Concluídos <span class="badge bg-blue">{{ $pedidos_concluidos->count() }}</span></a></li>
                    </ul>
                    <div class="tab-content">

                        <div class="active tab-pane" id="pagos">
                            <div class="box">
                                <div class="box-header">
                                    Veja abaixo a lista de pedidos:
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table id="tabela_ingles" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>Nº Doc</th>
                                            <th>Item</th>
                                            <th>Valor</th>
                                            <th>Data do contrato</th>
                                   {{--         <th>Ao final do contrato</th>
                                            <th>Contrato</th>--}}
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($pedidos_pagos as $pedido)
                                            <tr>
                                                <td>{{ $pedido->id }}</td>
                                                <td>{{ $pedido->itens->first()->item->name }}</td>
                                                <td>{{ mascaraMoeda($sistema->moeda, $pedido->valor_total, 2, true) }}</td>
                                                <td>{{ $pedido->data_compra->format('d/m/Y') }}</td>
                                              {{--  @if($pedido->itens->first()->modo_recontratacao_automatica == 0 && $pedido->itens->first()->habilita_recontratacao_automatica == 0)
                                                        <td>{{ array_search($pedido->itens->first()->modo_recontratacao_automatica, config('constants.modo_recontratacao_automatica_exibicao'))}}</td>
                                                @else
                                                    <td><a class="recontratacao-off btn btn-sm btn-default" href="javascript:;" data-toggle="modal" data-target="#modal-pedido-{{ $pedido->id }}">{{ array_search($pedido->itens->first()->modo_recontratacao_automatica, config('constants.modo_recontratacao_automatica_exibicao')) }} @if($pedido->itens->first()->modo_recontratacao_automatica > 0). @if($pedido->itens->first()->modo_recontratacao_automatica == 1) {{ mascaraMoeda($sistema->moeda, $pedido->itens->first()->valor_total, 2, true) }} @else {{ mascaraMoeda($sistema->moeda, ($pedido->valor_total * $pedido->itens->first()->total_meses_contrato * ($pedido->itens->first()->potencial_mensal_teto / 100) + $pedido->valor_total), 2, true) }}  @endif @endif</a></td>
                                                @endif
                                                <td>
                                                    @if(file_exists(storage_path('app/contratos/' . $pedido->contrato)) && !is_dir(storage_path('app/contratos/' . $pedido->contrato)))
                                                        <a title="Contrato" target="_blank" href="{{ route('pedido.usuario.contrato', $pedido->id) }}" class="btn btn-sm btn-default">
                                                            <span class="fa fa-file-text-o text-success" aria-hidden="true"></span> Ver contrato
                                                        </a>
                                                    @endif
                                                </td>--}}
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>


                                    @foreach($pedidos_pagos as $pedido)

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
                                    @endforeach

                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="concluidos">
                            <div class="box">
                                <div class="box-header">
                                    Veja abaixo os pedidos já finalizados:
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table id="tabela_portugues" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>Nº Doc</th>
                                            <th>Item</th>
                                            <th>Valor</th>
                                            <th>Data do contrato</th>
                                           {{-- <th>Data Finalização</th>
                                            <th>Ao finalizar o contrato:</th>
                                            <th>Contrato</th>--}}
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($pedidos_concluidos as $pedido)
                                            <tr>
                                                <td>{{ $pedido->id }}</td>
                                                <td>{{ $pedido->itens->first()->item->name }}</td>
                                                <td>{{ mascaraMoeda($sistema->moeda, $pedido->valor_total, 2, true) }}</td>
                                                <td>{{ $pedido->data_compra->format('d/m/Y') }}</td>
                            {{--                    <td>{{ $pedido->data_fim->format('d/m/Y') }}</td>
                                                <td>{{ array_search($pedido->itens->first()->modo_recontratacao_automatica, config('constants.modo_recontratacao_automatica_exibicao')) }} @if($pedido->itens->first()->modo_recontratacao_automatica > 0). @if($pedido->itens->first()->modo_recontratacao_automatica == 1) {{ mascaraMoeda($sistema->moeda, $pedido->itens->first()->valor_total, 2, true) }} @else {{ mascaraMoeda($sistema->moeda, ($pedido->valor_total * $pedido->itens->first()->total_meses_contrato * ($pedido->itens->first()->potencial_mensal_teto / 100) + $pedido->valor_total), 2, true) }}  @endif @endif</td>
                                                <td>
                                                    @if(file_exists(storage_path('app/contratos/' . $pedido->contrato)) && !is_dir(storage_path('app/contratos/' . $pedido->contrato)))
                                                        <a title="Contrato" target="_blank" href="{{ route('pedido.usuario.contrato', $pedido->id) }}" class="btn btn-sm btn-default">
                                                            <span class="fa fa-file-text-o text-success" aria-hidden="true"></span> Ver contrato
                                                        </a>
                                                    @endif
                                                </td>--}}
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- /.nav-tabs-custom -->

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/extensions/Responsive/css/dataTables.responsive.css') }}">

    <link rel="stylesheet" href="{{ asset('plugins/sweetalert/sweetalert.css') }}">

    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/square/red.css') }}">

@endsection

@section('script')
    <!-- DataTables -->
    <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="{{ asset('plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="/js/backend/tabelas.js" type="text/javascript"></script>
    <script src="/js/backend/datatables.js" type="text/javascript"></script>
    {{--<script src="/js/backend/list.js" type="text/javascript"></script>--}}

    <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>

    <script>
        $(function(){

            $('.modal').on('hidden.bs.modal', function () {

                formulario = $(this);

                pedidoItemId = formulario.find('input[name="pedido_item_id"]').val();
                modoRecontratacaoAutomaticaOriginal = formulario.find('input[name="modo_recontratacao_automatica_original"]').val();
                $('input').filter('[name="modo_recontratacao_automatica_' + pedidoItemId + '"]').filter('[value="' + modoRecontratacaoAutomaticaOriginal + '"]').iCheck('check');
            });

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red',
                increaseArea: '20%' // optional
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