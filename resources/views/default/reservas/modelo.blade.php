@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            @yield('h1')
        </h1>

        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Reservas</li>
            <li class="active">@yield('migalha')</li>
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
                                <th>#</th>
                                <th>Descrição</th>
                                <th>Acomodacao</th>
                                <th>Voucher</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody class="ordenar">
                            @foreach($dados as $dt)
                                <tr>
                                    <td>{{ $dt->id }}</td>
                                    <td>{{ $dt->getRelation('pacote')->chamada }}</td>
                                    <td>{{ $dt->getRelation('acomodacao')->name }}</td>
                                    <td>{{ $dt->voucher }}</td>
                                    <td>{{ $dt->getRelation('statusPedidoPacote')->name }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Botões de Ação">
                                            <a title="Visualizar" class="btn btn-default btn-sm" href="{{ route('reservas.visualizar', $dt->id) }}">
                                                <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"></span> Visualizar
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Descrição</th>
                                <th>Acomodacao</th>
                                <th>Voucher</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
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
    <link rel="stylesheet" href="{{ asset('plugins/datatables/extensions/Responsive/css/dataTables.responsive.css') }}">

@endsection

@section('script')
    <!-- DataTables -->
    <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="{{ asset('plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js') }}"></script>

    <script src="/js/backend/tabelas.js" type="text/javascript"></script>
    <script src="/js/backend/datatables.js" type="text/javascript"></script>
    <script src="/js/backend/bootstrap-confirmation.js" type="text/javascript"></script>
    <script src="/js/backend/list.js" type="text/javascript"></script>
@endsection