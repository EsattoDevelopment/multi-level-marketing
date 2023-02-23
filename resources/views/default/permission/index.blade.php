@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Lista de Permissões
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Permissões</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tabela_index" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Nome Exibido</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dados as $dd)
                                <tr>
                                    <td>{{ $dd->id }}</td>
                                    <td>{{ $dd->name }}</td>
                                    <td>{{ $dd->display_name }}</td>
                                    <td>
                                        <form method="post" id="formDel" action="{{ route('permission.destroy', $dd->id) }}">
                                            {!! csrf_field() !!}
                                            <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                <a title="Editar" class="btn btn-default btn-sm" href="{{ route('permission.edit', $dd->id) }}">
                                                    <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"></span>
                                                </a>
                                                <a title="Desativar" class="btn btn-default btn-sm" href="{{ route('permission.delete', $dd->id) }}">
                                                    <span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span>
                                                </a>
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn btn-danger btn-sm" data-toggle="confirmation" data-btn-ok-label="Sim" data-btn-ok-class="btn-success" data-btn-cancel-label="Não" data-btn-cancel-class="btn-danger"  data-title="Deletar Permanentemente!" data-singleton="true">
                                                    <span class="glyphicon glyphicon-trash text-black"></span>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
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
    <script type="text/javascript">
        $(function() {
            //adiciona o botão de NOVO
            $('<div class="btn-group"><a href="{{ route('permission.create') }}" class="btn btn-primary">Novo</a></div>').appendTo('div.box-btn');
        })
    </script>
@endsection