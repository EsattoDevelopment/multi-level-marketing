@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Minhas Transferências
        </h1>
        <ol class="breadcrumb hidden-xs">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Transferências</li>
            <li class="active">Histórico</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table id="tabela_portugues" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Nº Doc</th>
                                <th>Valor</th>
                                <th>Taxa</th>
                                <th>Destinatario</th>
                                <th>Status</th>
                                <th>Solicitação</th>
                                <th>Efetivação</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($transferencias as $dd)
                                <tr>
                                    <td>{{ $dd->id }}</td>
                                    <td>{{ mascaraMoeda($sistema->moeda, $dd->valor, 2, true) }}</td>
                                    <td>{{ mascaraMoeda($sistema->moeda, $dd->valor_taxa, 2, true) }}</td>
                                    <td>{!! $dd->dado_bancario_id ? $dd->conta->dados . "<br> {$dd->usuario->cpf}" : $dd->destinatario->name . "<br> {$dd->destinatario->cpf} <br> 0001/{$dd->destinatario->conta}" !!}</td>
                                    <td>{{ config('constants.status_transferencia')[$dd->status] }}</td>
                                    <td>{{ $dd->dt_solicitacao->format('d/m/Y H:i') }}</td>
                                    <td>{{ $dd->dt_efetivacao->format('d/m/Y H:i') }}</td>
                                    {{--<td>
                                        <div class="btn-group" role="group" aria-label="Botões de Ação">
                                            <a title="CONFIRMAR" class="btn btn-default btn-sm" href="{{ route('pedido.usuario.pedido', [Auth::user()->id, $dd->id]) }}">
                                                <span class="fa fa-money text-success" aria-hidden="true"></span> Opções de Depósito
                                            </a>
                                            <a title="Cancelar" class="btn btn-default btn-sm" href="{{ route('pedido.usuario.pedido.cancelar', [Auth::user()->id, $dd->id]) }}">
                                                <span class="glyphicon glyphicon-remove-circle text-danger" aria-hidden="true"></span> Cancelar
                                            </a>
                                        </div>
                                    </td>--}}
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