@extends('layout.main')

@section('content')

    @include('errors.errors')

    <section class="content-header">
        <h1>
            Lista de {{ $name }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">{{ $name }}</li>
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
                                @permission(['master', 'admin', 'guia-visualizar-todas'])
                                <th>Clinica</th>
                                @endpermission
                                <th>Titular</th>
                                <th>Paciente</th>
                                <th>Data atendimento</th>
                                <th>Tipo atendimento</th>
                                <th>Medico</th>
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
        $(function() {
            var table = $('#tabela_index').DataTable({
                processing: true,
                serverSide: true,
                searchDelay: 500,
                ajax: '{!! $getAll !!}',
                columns: [
                    {data: 'id', name: 'g.id'},
                    @permission(['master', 'admin', 'guia-visualizar-todas'])
                    {data: 'clinica', name: 'c.name'},
                    @endpermission
                    {data: 'titular', name: 'u.name'},
                    {data: 'paciente', name: 'd.name'},
                    {data: 'data', name: 'g.dt_atendimento'},
                    {data: 'tipo_atendimento', name: 'g.tipo_atendimento'},
                    {data: 'medico', name: 'm.name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[0, "desc"]]
            });

            //adiciona o botão de NOVO
            $('<div class="btn-group"><a href="{{ route('saude.guias.create') }}" class="btn btn-primary">Novo</a></div>').appendTo('div.box-btn');
        })
    </script>
@endsection