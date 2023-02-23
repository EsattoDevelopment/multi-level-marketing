@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Bônus de equipe <br>
            <small class="text-black">O Agente de Expansão é valorizado na {{ ucfirst(env('COMPANY_NAME', 'empresa')) }}!</small>
            {{--TODO voltar com calculo correto--}}
           {{-- <span><small>Ganhos totais: {{ $sistema->moeda }} {{ $todos->first() ? mascaraMoeda($sistema->moeda, $todos->first()->saldo, 2, true) : 0}}</small></span>--}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Extrato</li>
            <li class="active">Bônus de equipe</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <strong>Total de ganhos {{ mascaraMoeda($sistema->moeda, $bonus_equiparacao->sum('valor_manipulado'), 2, true) }}</strong>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tabela_espanhol" style="width: 100%;" class="table dt-responsive nowap table-bordered table-striped table-responsive">
                            <thead>
                            <tr>
                                <th>N° Doc</th>
                                <th>Data</th>
                                <th>Valor</th>
                                <th>Descrição</th>
                                <th>Creditado</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bonus_equiparacao as $dd)
                                <tr>
                                    <td>{{ $dd->id }}</td>
                                    <td>{{ $dd->data }}</td>
                                    <td class="{{ $dd->getRelation('operacao')->cor }}">{{ mascaraMoeda($sistema->moeda, $dd->valor_manipulado, 2, true) }}</td>
                                    <td>{{ $dd->descricao }}</td>
                                    <td>Conta digital</td>
                                </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            </tfoot>
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

    <script src="{{ asset('js/backend/tabelas.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/datatables.js') }}" type="text/javascript"></script>
    {{--<script src="{{ asset('js/backend/list.js') }}" type="text/javascript"></script>--}}

@endsection