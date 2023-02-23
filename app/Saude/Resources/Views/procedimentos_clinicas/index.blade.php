@extends('layout.main')

@section('content')

    @include('errors.errors')

    <section class="content-header">
        <h1>
            Lista de Procedimentos
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
                    <div class="tab-content">
                        <div class="active tab-pane" id="ativos">
                            <div class="box">
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
                                        @foreach($dados as $dd)
                                            <tr>
                                                <td>{{ $dd->procedimento->id }}</td>
                                                <td>{{ $dd->procedimento->codigo }}</td>
                                                <td>{{ $dd->name ?: $dd->procedimento->name }}</td>
                                                <td>{{ mascaraMoeda($sistema->moeda, ($dd->valor > 0 ? $dd->valor : $dd->procedimento->valor), 2, true) }}</td>
                                                <td>
                                                    <form method="post" id="formDel_{{ $dd->id }}" action="{{ route('saude.procedimentos.destroy', [$dd->id]) }}">
                                                        {!! csrf_field() !!}
                                                        <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                            <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                                <a title="Editar" class="btn btn-default btn-sm"
                                                                   href="{{ route('saude.procedimentos_clinica.edit', [$dd->user_id, $dd->procedimento->id]) }}">
                                                                    <span class="glyphicon glyphicon-edit text-success"
                                                                          aria-hidden="true"></span> Editar
                                                                </a>

{{--                                                            <input type="hidden" name="_method" value="DELETE">--}}
{{--                                                            <button type="submit" data-id="{{ $dd->id }}" class="btn btn-danger btn-sm botao-del">--}}
{{--                                                                <span class="glyphicon glyphicon-trash text-black"></span>--}}
{{--                                                            </button>--}}
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
            $('#ativos table').DataTable();
        })
    </script>
@endsection