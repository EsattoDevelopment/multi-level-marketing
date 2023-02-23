@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            {{ $title }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Colaboradores</li>
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
                        <table id="tabela_index" class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Usuário</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
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
    <script src="/js/backend/datatables.js" type="text/javascript"></script>
    <script src="/js/backend/bootstrap-confirmation.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(function () {
            $('#tabela_index').DataTable({
                processing: true,
                serverSide: true,
                searchDelay: 500,

                @if(\Entrust::hasRole(['master','admin']))
                ajax: '{!! route('user.empresa.id', $id) !!}',
                @elseif(\Entrust::hasRole('user-empresa'))
                ajax: '{!! route('user.empresa.json') !!}',
                @endif

                columns: [
                    {data: 'id', name: 'u.id'},
                    {data: 'name', name: 'u.name'},
                    {data: 'username', name: 'u.username'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[1, "desc"]]
            });

        @role('admin')
            //adiciona o botão de VOltar
            $('<div class="btn-group"><a href="{{ route('relatorio.colaboradores', $id) }}" target="_blank" class="btn btn-success">Relatório </a></div>').appendTo('div.box-btn');
            $('<div class="btn-group"><a href="{{ URL::previous() }}" class="btn btn-default">Voltar </a></div>').appendTo('div.box-btn');
        @endrole
        })
    </script>
@endsection