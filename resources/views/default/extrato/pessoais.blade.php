@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Extrato de GMilhas Pessoais<br>
        </h1>

        <div style="width: 100%;">
            Total de pontos: <i id="total_pontos" class="text-green text-bold" style="font-size:1.3em;"></i>
        </div>

        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Extrato</li>
            <li>Pontos</li>
            <li class="active">Pessoais</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table id="tabela_index" style="width: 100%;" class="table table-bordered table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Data</th>
                                    <th>Pontos</th>
                                    <th>Operação</th>
                                </tr>
                            </thead>
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
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/extensions/Responsive/css/dataTables.responsive.css') }}">
@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/backend/datatables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/bootstrap-confirmation.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(function() {
            var table = $('#tabela_index').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 500,
                ajax: '{!! route('extrato.pessoais.json') !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'pontos', name: 'pontos'},
                    {data: 'operacao', name: 'operacao'},
                ],
                initComplete: function(settings, json) {
                    $('#total_pontos').text(json.pontos);
                },
                order: [[1, "desc"]]
            });
        })
    </script>
@endsection
