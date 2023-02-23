@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Extrato de milhas <br>
            <span><small>Ganhos totais: {{ mascaraMoeda($sistema->moeda, $dados->sum('quantidade'), 2, true) }}</small></span>
        </h1>

        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Extrato</li>
            <li class="active">Milhas</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <strong>Disponivel {{ mascaraMoeda($sistema->moeda, $disponivel, 2, true) }}</strong>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tabela_index" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Validade</th>
                                <th>Quantidade</th>
                                <th>Descrição</th>
                                <th>Utilizado</th>
                                <th>Utilizado onde</th>
                            </tr>
                            </thead>
                            <tbody class="ordenar">
                            @foreach($dados as $dt)
                                <tr>
                                    <td>{{ $dt->id }}</td>
                                    <td>{{ $dt->validade }}</td>
                                    <td>{{ mascaraMoeda($sistema->moeda, $dt->quantidade, 2, true) }}</td>
                                    <td>{{ $dt->descricao }} #{{ $dt->pedido_id }}</td>
                                    <td>{{ $dt->referencia == 0 ? 'Não' :  'Sim'  }}</td>
                                    <td>{{ $dt->utilizada_onde }}</td>
                                </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Validade</th>
                                <th>Quantidade</th>
                                <th>Descrição</th>
                                <th>Utilizado</th>
                                <th>Utilizado onde</th>
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