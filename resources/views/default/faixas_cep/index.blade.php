@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>Lista de Faixas de CEP</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Faixas de CEP</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="tab-pane" id="ceps">
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="tabela" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Inicio</th>
                                    <th>Fim</th>
                                    <th>Ações</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($dados as $dd)
                                    <tr>
                                        <td>{{ $dd->id }}</td>
                                        <td>{{ $dd->inicio }}</td>
                                        <td>{{ $dd->fim }}</td>
                                        <td>
                                            <form method="post" id="formDel_{{ $dd->id }}" action="{{ route('user.{user}.faixas-cep.destroy', [$dd->user_id, $dd->id]) }}">
                                                {!! csrf_field() !!}
                                                <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                    <a title="Editar" class="btn btn-default btn-sm" href="{{ route('user.{user}.faixas-cep.edit', [$dd->user_id, $dd->id]) }}">
                                                        <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"></span> Editar
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
                                    <th>Inicio</th>
                                    <th>Fim</th>
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
            $("#tabela").DataTable();

            //adiciona o botão de NOVO
            $('<div class="btn-group"><a href="{{ route('user.{user}.faixas-cep.create', [$user->id]) }}" class="btn btn-primary">Novo</a></div>').appendTo('div.box-btn');
        })
    </script>
@endsection