@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Extrato de Bônus <br>
            {{--TODO voltar com calculo correto--}}
            {{-- <span><small>Ganhos totais: {{ $sistema->moeda }} {{ $todos->first() ? mascaraMoeda($sistema->moeda, $todos->first()->saldo, 2, true) : 0}}</small></span>--}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Extrato</li>
            <li class="active">Extrato de Bônus</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-header">
                        <strong>Total de ganhos {{ mascaraMoeda($sistema->moeda, $bonus->sum('valor_manipulado'), 2, true) }}</strong><br>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tabela_portugues" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Data</th>
                                <th>Valor</th>
                                <th>Operação</th>
                                <th>Licença Nº</th>
                                <th>Licença tipo</th>
                                <th>Descrição</th>
                                <th>Creditado</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($bonus as $dd)
                                <tr>
                                    <td>{{ $dd->created_at->format('d/m/Y') }}</td>
                                    <td class="{{ $dd->getRelation('operacao')->cor }}">{{ mascaraMoeda($sistema->moeda, $dd->valor_manipulado, 2, true) }}</td>
                                    <td>{{ $dd->getRelation('operacao')->name }}</td>
                                    <td>#{{ $dd->rentabilidade_id ? 'Rentabilidade' : $dd->pedido_referencia_id ?? $dd->pedido_id }}</td>
                                    <td>{{ $dd->item->name ?? '' }}</td>
                                    <td>{{ $dd->descricao }}</td>
                                    <td>{{ $dd instanceof \App\Models\Movimentos ? 'Conta digital' : 'Carteira #'.$dd->pedido_id}}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>


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

    <script>
        $(function(){
            $("table.table").DataTable({
                "ordering": false,
                responsive: true,
                scrollCollapse: true,
                scrollX: true,
                scroller: true
            });
        })
    </script>
    <script src="{{ asset('js/backend/datatables.js') }}" type="text/javascript"></script>
    {{--<script src="{{ asset('js/backend/list.js') }}" type="text/javascript"></script>--}}

@endsection