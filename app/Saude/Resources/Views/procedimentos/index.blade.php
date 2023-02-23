@extends('layout.main')

@section('content')

    @include('errors.errors')

    <section class="content-header">
        <h1>
            Lista de Procedimentos <br>
            <small><i><strong>Procedimentos</strong></i></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Procedimentos</li>
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
                                <div class="box-header text-danger">
                                    Lista de Procedimentos Ativos
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table id="tabela_ativos" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Código</th>
                                            <th>Nome</th>
                                            <th>Valor</th>
                                            <th>Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Código</th>
                                            <th>Nome</th>
                                            <th>Valor</th>
                                            <th>Ações</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="desativados">
                            <div class="box">
                                <div class="box-header text-danger">
                                    Lista de Procedimentos desativados
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table id="tabela_desabled" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Código</th>
                                            <th>Nome</th>
                                            <th>Valor</th>
                                            <th>Desativado em</th>
                                            <th>Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($dados_desativados as $dd)
                                            <tr>
                                                <td>{{ $dd->id }}</td>
                                                <td>{{ $dd->codigo }}</td>
                                                <td>{{ $dd->name }}</td>
                                                <td>{{ mascaraMoeda($sistema->moeda, $dd->valor, 2) }}</td>
                                                <td>{{ Carbon\Carbon::parse($dd->deleted_at)->format('d/m/Y H:m:s') }}</td>
                                                <td>
                                                    <form method="post" id="formDel_{{ $dd->id }}" action="{{ route('saude.procedimentos.destroy', [$dd->id]) }}">
                                                        {!! csrf_field() !!}
                                                        <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                            <a title="Ativar" class="btn btn-default btn-sm" href="{{ route('saude.procedimentos.recovery', [$dd->id]) }}">
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
                                            <th>Código</th>
                                            <th>Nome</th>
                                            <th>Valor</th>
                                            <th>Desativado em</th>
                                            <th>Ações</th>
                                        </tr>
                                        </tfoot>
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
    <link rel="stylesheet" href="{{ asset('plugins/datatables/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="/plugins/datatables/dataTables.bootstrap.css">

    <style>
        td.details-control {
            cursor: pointer;
            width: 18px;
        }

        td.details-control:before {
            font-family: FontAwesome;
            content: "\f055";
            color: #1ead05;
            border: 2px solid rgba(187, 168, 168, 0.59);
            border-radius: 20px;
            background: #ffffff;
            padding: 1px;
        }

        tr.shown > td.details-control:before {
            content: "\f056";
            color: #ff0000;
        }

    </style>
@endsection

@section('script')
    <!-- DataTables -->
    <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="/js/backend/datatables.js" type="text/javascript"></script>
    <script src="{{ asset('js/backend/datatables.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(function() {
            $('#ativos table').DataTable( {
                "processing": true,
                "serverSide": true,
                "searchDelay": 500,
                "ajax": "{!! route('procedimentos.json') !!}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'codigo', name: 'codigo'},
                    {data: 'name', name: 'name'},
                    {data: 'valor', name: 'valor'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                // order: [[1, "desc"]]
            } );

            //adiciona o botão de NOVO
            $('<div class="btn-group"><a href="{{ route('saude.procedimentos.create') }}" class="btn btn-primary">Novo</a></div>').appendTo('div.box-btn');
        })
    </script>
@endsection