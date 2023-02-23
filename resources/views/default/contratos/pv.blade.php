@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Extrato de PV
        </h1>

        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Extrato</li>
            <li class="active">PV</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    {{--<div class="box-header">
                        <small><strong>Legenda</strong></small>
                        <br>
                        B.E = Binário Esquerdo <br>
                        B.D = Binário Direito <br>
                    </div>--}}
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tabela_index" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Data</th>
                                <th>Pontos</th>
                               {{-- <th>Clube Sul (B.D)</th>
                                <th>Clube Norte (B.D)</th>--}}
                                <th>Operação</th>
                            </tr>
                            </thead>
                            <tbody class="ordenar">
                            @foreach($dados as $dt)
                                <tr>
                                    <td>{{ $dt->id }}</td>
                                    <td>{{ $dt->created_at }}</td>
                                    <td class="{{ $dt->getRelation('operacao')->cor }}">{{ $dt->pontos }}</td>
                                    {{--<td>{{ $dt->saldo_esquerda }}</td>
                                    <td>{{ $dt->saldo_direita }}</td>--}}
                                    <td>{{ $dt->getRelation('operacao')->name }}</td>
                                </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Data</th>
                                <th>Pontos</th>
                                {{--<th>Clube Sul (B.D)</th>
                                <th>Clube Norte (B.D)</th>--}}
                                <th>Operação</th>
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