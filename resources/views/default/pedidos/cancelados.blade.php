@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Todos pedidos
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Todos pedidos</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#cancelados" data-toggle="tab">Cancelados <span class="badge bg-red">{{ $pedidos_cancelados->count() }}</span></a></li>
                    </ul>
                    <div class="tab-content">

                        <div class="tab-pane active" id="cancelados">
                            <div class="box">
                                <div class="box-header">
                                    Lista de pedidos cancelados
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table id="tabela_espanhol" class="table table-bordered table-striped responsive">
                                        <thead>
                                        <tr>
                                            <th>Nº Doc</th>
                                            <th>Item</th>
                                            <th>Nome</th>
                                            {{--<th>Usuário</th>--}}
                                            <th>Valor</th>
                                            <th>Data compra</th>
                                            <th>Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($pedidos_cancelados as $dd)
                                            <tr>
                                                <td>{{ $dd->id }}</td>
                                                <td>{{ $dd->getRelation('itens')->first()->getRelation('itens')->name }}</td>
                                                <td>{{ $dd->user->name }}</td>
                                                {{--<td>{{ $dd->getRelation('user')->username }}</td>--}}
                                                <td>{{ mascaraMoeda($sistema->moeda, $dd->valor_total, 2, true) }}</td>
                                                <td>{{ $dd->data_compra->format('d/m/Y') }}</td>
                                                <td>
                                                    <form method="post" id="formDel_{{ $dd->id }}" action="{{ route('pedido.destroy', $dd->id) }}">
                                                        {!! csrf_field() !!}
                                                        <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                        <tfoot>
                                        </tfoot>
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
    <script src="{{ asset('js/backend/list.js') }}" type="text/javascript"></script>

@endsection