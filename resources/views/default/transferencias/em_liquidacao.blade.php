@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Minhas Transferências
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Transferências</li>
            <li class="active">Em liquidação</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        Veja abaixo a lista de transferências:
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tabela_portugues" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Nº Doc</th>
                                <th>Nome</th>
                                <th>Valor</th>
                                <th>Taxa</th>
                                <th>Destinatario</th>
                                <th>Status</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($transferencias as $dd)
                                <tr>
                                    <td>{{ $dd->id }}</td>
                                    <td>{{ $dd->usuario->name }} <br> {{ $dd->usuario->email }} <br>{{ $dd->usuario->cpf }} <br> <b>{{ $dd->usuario->celular }}</b></td>
                                    <td>{{ mascaraMoeda($sistema->moeda, $dd->valor, 2, true) }}</td>
                                    <td>{{ mascaraMoeda($sistema->moeda, $dd->valor_taxa, 2, true) }}</td>
                                    <td>{!! $dd->dado_bancario_id ? $dd->conta->dados . "<br>{$dd->cpf}" : $dd->destinatario->name !!}</td>
                                    <td>{{ config('constants.status_transferencia')[$dd->status] }}</td>
                                    <td>{{ $dd->dt_solicitacao->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        @if($dd->status == 1)
                                            <div class="btn-group" role="group" aria-label="Botões de Ação">
                                               {{-- <a title="CONFIRMAR" class="btn btn-default btn-sm" href="{{ route('transferencia.edit', $dd) }}">
                                                    <span class="fa fa-money text-success" aria-hidden="true"></span> Editar
                                                </a>--}}
                                                <a title="Efetivar" class="btn btn-default btn-sm" href="{{ route('transferencia.efetivar', $dd) }}">
                                                    <span class="glyphicon glyphicon-check text-success" aria-hidden="true"></span> Efetivar
                                                </a>
                                                <a title="Cancelar" class="btn btn-default btn-sm" href="{{ route('transferencia.cancelar', $dd) }}">
                                                    <span class="glyphicon glyphicon-trash text-red" aria-hidden="true"></span> Cancelar
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
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
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/backend/tabelas.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/datatables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/list.js') }}" type="text/javascript"></script>
@endsection