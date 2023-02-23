@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Lista de Itens
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Itens</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#ativos" data-toggle="tab">Ativos</a></li>
                        <li><a href="#desativados" data-toggle="tab">Desativados</a></li>
                    </ul>
                    <div class="tab-content"  data-sort="{{ route('api.item.order') }}">

                        <div class="active tab-pane" id="ativos">
                            <div class="box">
                                <div class="box-header">
                                    <small class="pull-right text-red">Para ordenar a exibição dos itens arraste as linhas*</small>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table id="tabela_index" style="width: 100%;" class="table table-bordered table-striped table-responsive sortable">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nome</th>
                                            <th>Valor</th>
                                            <th>Tipo Pedido</th>
                                            <th>Título Upgrade</th>
                                            <th>Paga Bônus?</th>
                                            <th>Paga Bônus do Titulo?</th>
                                            <th>Ativo Compra?</th>
                                            <th>Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tabela_index_interna" class="ordernar">
                                        @foreach($dados as $dd)
                                            <tr id="item-{{ $dd->id }}">
                                                <td>{{ $dd->id }}</td>
                                                <td>{{ $dd->name }}</td>
                                                <td>{{ mascaraMoeda($sistema->moeda, $dd->valor, 2, true) }}</td>
                                                <td>{{$dd->tipoPedidos->name}}</td>
                                                <td>{{$dd->titulo ? $dd->titulo->name : ''}}</td>
                                                <td>{{$dd->pagar_bonus ? "Sim" : "Não"}}</td>
                                                <td>{{$dd->pagar_bonus_titulo ? "Sim" : "Não"}}</td>
                                                <td>{{$dd->ativo ? "Sim" : "Não"}}</td>
                                                <td>
                                                    <form method="post" id="formDel_{{ $dd->id }}" action="{{ route('item.destroy', $dd->id) }}">
                                                        {!! csrf_field() !!}
                                                        <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                            <a title="Editar" class="btn btn-default btn-sm" href="{{ route('item.edit', $dd->id) }}">
                                                                <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"></span>
                                                            </a>
                                                            <a title="Desativar" class="btn btn-default btn-sm" href="{{ route('item.delete', $dd->id) }}">
                                                                <span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span>
                                                            </a>
                                                            {{--<input type="hidden" name="_method" value="DELETE">
                                                            <button type="submit" data-id="{{ $dd->id }}" class="btn btn-danger btn-sm botao-del">
                                                                <span class="glyphicon glyphicon-trash text-black"></span>
                                                            </button>--}}
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>

                        <div class="tab-pane" id="desativados">
                            <div class="box-body">
                                <table id="tabela_desabled" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Valor</th>
                                        <th>Pontos Binários</th>
                                        <th>Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody class="ordernar">
                                    @foreach($dados_desativados as $dd)
                                        <tr>
                                            <td>{{ $dd->id }}</td>
                                            <td>{{ $dd->name }}</td>
                                            <td>{{ $dd->valor }}</td>
                                            <td>{{ $dd->pontos_binarios }}</td>
                                            <td>
                                                <form method="post" id="formDel_{{ $dd->id }}" action="{{ route('item.destroy', $dd->id) }}">
                                                    {!! csrf_field() !!}
                                                    <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                        <a title="Ativar" class="btn btn-default btn-sm" href="{{ route('item.recovery', $dd->id) }}">
                                                            <span class="glyphicon glyphicon-ok text-success" aria-hidden="true"></span>
                                                        </a>
                                                        {{--<input type="hidden" name="_method" value="DELETE">
                                                        <button type="submit" data-id="{{ $dd->id }}" class="btn btn-danger btn-sm botao-del">
                                                            <span class="glyphicon glyphicon-trash text-black"></span>
                                                        </button>--}}
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
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
    <link rel="stylesheet" href="{{ asset('css/imagens/jquery-ui.min.css') }}">
@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/backend/tabelas.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/datatables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/imagens/jquery-ui.min.js') }}"></script>
{{--    <script src="{{ asset('plugins/jQueryUI/jquery-ui.min.js') }}" type="text/javascript"></script>--}}
    {{--<script src="{{ asset('js/backend/list.js') }}" type="text/javascript"></script>--}}
    <script type="text/javascript">
        $(function() {
            //adiciona o botão de NOVO
            $('<div class="btn-group"><a href="{{ route('item.create') }}" class="btn btn-primary">Novo</a></div>').appendTo('div.box-btn');

            $('.table tbody.ordernar').sortable({
                update: function( event, ui ) {
                    if(ui.item[0].offsetParent.id != 'table_desabled'){
                        data = $('#' + ui.item[0].offsetParent.id + ' tbody').sortable('serialize');
                        $.ajax({
                            data: data,
                            type: 'POST',
                            url: $('.tab-content').attr('data-sort'),
                            datatype: 'json',
                        }).done(function(data) {
                            console.log(data);
                        }).fail(function(data) {
                            swal('Erro!', data.message, 'error');
                        });
                    }
                }
            });
        })
    </script>
@endsection