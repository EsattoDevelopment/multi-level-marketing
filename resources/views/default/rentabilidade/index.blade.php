@extends('default.layout.main')

@section('content')


    <section class="content-header">
        <h1>
            Lista de pagamentos de rentabilidade
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('rentabilidade.index') }}"><i class="fa fa-line-chart"></i> Rentabilidade</a></li>
            <li class="active"><i class="glyphicon glyphicon-th-list"></i> Lista</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="nav-tabs-custom">
                    @include('default.errors.errors')
                    <div class="tab-content">

                        <div class="active tab-pane" id="ativos">
                            <div class="box">
                                <div class="box-header">
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table id="tabela_index" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Data</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tabela_index_interna">

                                        @foreach($dados as $dd)
                                            <tr>
                                                <td> Rentabilidade do dia</td>
                                                <td>{{ $dd->data->format('d/m/Y') }}</td>
                                                <td>{{$dd->pago == 1 ? 'Pago' : ''}} </td>
                                                <td>
                                                    {!! csrf_field() !!}
                                                    <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                        @if($dd->pago == 0)
                                                            <a title="Editar" class="btn btn-default btn-sm" href="{{ route('rentabilidade.edit', $dd->data->format('Y-m-d')) }}">
                                                                <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"></span>
                                                            </a>
                                                        @else
                                                            <a title="Visualizar" class="btn btn-default btn-sm" href="{{ route('rentabilidade.viewer', $dd->data->format('Y-m-d')) }}">
                                                                <span class="glyphicon glyphicon-eye-open text-success" aria-hidden="true"></span>
                                                            </a>
                                                        @endif
                                                        @if($dd->pago == 0)
                                                            <a title="Apagar" class="btn btn-default btn-sm" href="{{ route('rentabilidade.destroy', $dd->data->format('Y-m-d')) }}">
                                                                <span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span>
                                                            </a>
                                                            <a title="Pagar" class="btn btn-default btn-sm" href="{{ route('rentabilidade.pagar', $dd->data->format('Y-m-d')) }}">
                                                                <span class="fa fa-money text-green" aria-hidden="true"></span> Pagar Rentabilidade
                                                            </a>
                                                        @endif
                                                    </div>
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
    <link rel="stylesheet" href="/plugins/datatables/dataTables.bootstrap.css">
@endsection

@if($permitirCadastro)
@section('script')
    <!-- DataTables -->
    <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="/js/backend/tabelas.js" type="text/javascript"></script>
    <script src="/js/backend/datatables.js" type="text/javascript"></script>
    <script src="/js/backend/bootstrap-confirmation.js" type="text/javascript"></script>
    {{--<script src="/js/backend/list.js" type="text/javascript"></script>--}}
    <script type="text/javascript">
        $(function() {
            //adiciona o botão de NOVO
            $('<div class="btn-group"><a href="{{ route('rentabilidade.create') }}" class="btn btn-primary">Novo</a></div>').appendTo('div.box-btn');
        })
    </script>
@endsection
@endif