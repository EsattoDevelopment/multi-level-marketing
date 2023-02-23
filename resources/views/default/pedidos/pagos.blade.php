@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Todos pedidos
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Todos pedidos</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#pagos" data-toggle="tab">Pagos <span class="badge bg-green">{{--{{ $pedidos_pagos->count() }}--}}</span></a></li>
                    </ul>
                    <div class="tab-content">

                        <div class="tab-pane active" id="pagos">
                            <div class="box">
                                <div class="box-header">
                                    Lista de pedidos pagos
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table id="tabela_index" class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Nº Doc</th>
                                            <th>Item</th>
                                            <th>Nome</th>
                                            {{--<th>Usuário</th>--}}
                                            <th>Valor</th>
                                            <th>Método Pag.</th>
                                            <th>Data compra</th>
                                            <th>Data Pagamento</th>
                                            <th>Ações</th>
                                        </tr>
                                        </thead>
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
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/extensions/Responsive/css/dataTables.responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="/plugins/fancybox/jquery.fancybox.min.css">
@endsection

@section('script')
    <!-- DataTables -->
    <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="/js/backend/datatables.js" type="text/javascript"></script>
    <script src="/js/backend/bootstrap-confirmation.js" type="text/javascript"></script>
    <script src="/plugins/fancybox/jquery.fancybox.min.js"></script>
    <script type="text/javascript">
        $(function() {
            var table = $('#tabela_index').DataTable({
                processing: true,
                serverSide: true,
                searchDelay: 500,
                ajax: '{!! route('api.depositos.pagos') !!}',
                columns: [
                    {data: 'id', name: 'p.id'},
                    {data: 'item', name: 'i.name'},
                    {data: 'nome', name: 'u.name'},
                    //{data: 'username', name: 'u.username'},
                    {data: 'valor_total', name: 'p.valor_total'},
                    {data: 'metodo_pagamento', name: 'mp.name'},
                    {data: 'data_compra', name: 'p.data_compra'},
                    {data: 'data_pagamento', name: 'dp.data_pagamento'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[0, "desc"]]
            });
        })
    </script>
@endsection