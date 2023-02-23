@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Lista de Boletos de adesão
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Boletos</li>
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
                                <th>Data Vencimento</th>
                                <th>Nome</th>
                                <th>Nosso Numero</th>
                                <th>Valor</th>
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
                ajax: '{!! route('boletos.adesoes.json') !!}',
                columns: [
                    {data: 'id', name: 'p.id'},
                    {data: 'vencimento', name: 'b.vencimento'},
                    {data: 'name', name: 'u.name'},
                    {data: 'nosso_numero', name: 'b.nosso_numero'},
                    {data: 'valor_total', name: 'p.valor_total'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[1, "desc"]]
            });
        })
    </script>
@endsection