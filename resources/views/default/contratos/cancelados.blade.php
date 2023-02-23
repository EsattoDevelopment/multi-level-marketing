@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Lista de contratos cancelados <br>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Contratos</li>
            <li class="active">cancelados</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-body">
                        <table id="tabela_index" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Nº Doc</th>
                                <th>N° Contrato</th>
                                <th>Usuário Solicitante</th>
                                <th>Plano</th>
                                <th>Data cancelamento</th>
                                <th>Tipo</th>
                                {{--<th>Ações</th>--}}
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($dados as $dd)

                                <tr>
                                    <td>{{ $dd->id }}</td>
                                    <td>{{ $dd->getRelation('usuario')->codigo }}</td>
                                    <td>{{ !is_null($dd->getRelation('usuario')) ? $dd->getRelation('usuario')->name : '' }}</td>
                                    <td>{{ $dd->getRelation('item')->name }}</td>
                                    <td>{{ $dd->dt_cancelamento->format('d/m/Y') }}</td>
                                    <td>{{ config('constants.contrato_status')[$dd->status] }}</td>
                                    {{--<td>
                                        <div class="btn-group" role="group" aria-label="Botões de Ação">
                                            <a href="{{ route('contrato.impressao', $dd) }}" target="_blank"
                                               class="btn btn-warning btn-sm"> <i class="fa fa-print"></i> Imprimir contrato {{ config('constants')['tipo_pacote'][ $dd->getRelation('item')->tipo_pacote ] }}</a>
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

@endsection