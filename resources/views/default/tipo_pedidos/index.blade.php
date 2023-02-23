@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Lista de Tipo pedidos
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Tipo pedido</li>
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
                    <div class="tab-content">

                        <div class="active tab-pane" id="ativos">
                            <div class="box">
                                <div class="box-header">
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table id="tabela_index" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Titulo</th>
                                            <th>Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tabela_index_interna">
                                        @foreach($dados as $dd)
                                            <tr>
                                                <td>{{ $dd->id }}</td>
                                                <td>{{ $dd->nome }}</td>
                                                <td>
                                                    <form method="post" id="formDel_{{ $dd->id }}" action="{{ route('tipo_pedido.destroy', $dd->id) }}">
                                                        {!! csrf_field() !!}
                                                        <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                            <a title="Editar" class="btn btn-default btn-sm" href="{{ route('tipo_pedido.edit', $dd->id) }}">
                                                                <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"></span>
                                                            </a>
                                                            <a title="Desativar" class="btn btn-default btn-sm" href="{{ route('tipo_pedido.delete', $dd->id) }}">
                                                                <span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span>
                                                            </a>
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button type="submit" data-id="{{ $dd->id }}" class="btn btn-danger btn-sm botao-del">
                                                                <span class="glyphicon glyphicon-trash text-black"></span>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Titulo</th>
                                            <th>Ações</th>
                                        </tr>
                                        </tfoot>
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
                                        <th>#</th>
                                        <th>Titulo</th>
                                        <th>Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($dados_desativados as $dd)
                                        <tr>
                                            <td>{{ $dd->id }}</td>
                                            <td>{{ $dd->nome }}</td>
                                            <td>
                                                <form method="post" id="formDel_{{ $dd->id }}" action="{{ route('tipo_pedido.destroy', $dd->id) }}">
                                                    {!! csrf_field() !!}
                                                    <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                        <a title="Ativar" class="btn btn-default btn-sm" href="{{ route('tipo_pedido.recovery', $dd->id) }}">
                                                            <span class="glyphicon glyphicon-ok text-success" aria-hidden="true"></span>
                                                        </a>
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <button type="submit" data-id="{{ $dd->id }}" class="btn btn-danger btn-sm botao-del">
                                                            <span class="glyphicon glyphicon-trash text-black"></span>
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Titulo</th>
                                        <th>Ações</th>
                                    </tr>
                                    </tfoot>
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
@endsection

@section('script')
    <!-- DataTables -->
    <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="/js/backend/tabelas.js" type="text/javascript"></script>
    <script src="/js/backend/datatables.js" type="text/javascript"></script>
    <script src="/js/backend/bootstrap-confirmation.js" type="text/javascript"></script>
    <script src="/js/backend/list.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(function() {
            //adiciona o botão de NOVO
            $('<div class="btn-group"><a href="{{ route('tipo_pedido.create') }}" class="btn btn-primary">Novo</a></div>').appendTo('div.box-btn');
        })
    </script>
@endsection