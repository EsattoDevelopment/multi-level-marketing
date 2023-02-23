@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Extrato - Conta digital <br>
            {{--TODO voltar com calculo correto--}}
            {{--<span><small>Ganhos totais: {{ $sistema->moeda }} {{ $todos->first() ? mascaraMoeda($sistema->moeda, $todos->first()->saldo, 2, true) : 0}}</small></span>--}}
        </h1>
        <ol class="breadcrumb hidden-xs">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Extrato</li>
            <li class="active">Conta digital</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-header">
                                        <span>
                                            <strong>
                                                Saldo da Sua Conta digital: {{ $todos->first() ? mascaraMoeda($sistema->moeda, $todos->sortByDesc('id')->first()->saldo, 2, true) : 0}}
                                            </strong>
                                        </span>
                        {{--TODO voltar com calculo correto--}}
                        {{--<strong>Total ganhos {{ $sistema->moeda }} {{  $todos->first() ? mascaraMoeda($sistema->moeda, $todos->first()->saldo, 2, true) : 0 }}</strong>--}}
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tabela_portugues" style="width: 100%;" class="table table-bordered table-striped table-responsive">
                            <thead>
                            <tr>
                                <th>Nº Mov</th>
                                <th>Data</th>
                                <th>Valor</th>
                                <th>Saldo</th>
                                <th>Operação</th>
                                <th>Descrição</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($todos as $dd)
                                <tr>
                                    <td>{{ $dd->id }}</td>
                                    <td>{{ $dd->data }}</td>
                                    <td class="{{ $dd->getRelation('operacao')->cor }}">{{ mascaraMoeda($sistema->moeda, $dd->valor_manipulado, 2, true) }}</td>
                                    <td>{{ mascaraMoeda($sistema->moeda, $dd->saldo, 2, true) }}</td>
                                    <td>{{ $dd->getRelation('operacao')->name }}</td>
                                    <td>{{ $dd->descricao }}</td>
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