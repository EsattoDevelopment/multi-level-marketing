@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Pedidos outros usuários
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Pedidos</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <form class="form-inline" method="post" action="">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <input type="number" class="form-control" name="pedido_id" placeholder="Numero do pedido">
                    </div>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </form>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-xs-12">

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#aguardando_pagamento" data-toggle="tab">Pedido</a></li>
                    </ul>
                    <div class="tab-content">

                        <div class="active tab-pane" id="aguardando_pagamento">
                            <div class="box">
                                <div class="box-header">
                                    Lista de pedidos
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table id="tabela_portugues" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Item</th>
                                            <th>Nome</th>
                                            <th>Usuário</th>
                                            <th>Valor</th>
                                            <th>Data compra</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {{--@foreach($pedidos as $dd)--}}
                                        @if($pedidos)
                                            <tr>
                                                <td>{{ $pedidos->id }}</td>
                                                <td>{{ $pedidos->getRelation('itens')->first()->getRelation('itens')->name }}</td>
                                                <td>{{ $pedidos->getRelation('user')->name }}</td>
                                                <td>{{ $pedidos->getRelation('user')->username }}</td>
                                                <td>{{ $sistema->moeda }} {{ $pedidos->valor_total }}</td>
                                                <td>{{ $pedidos->data_compra->format('d/m/Y') }}</td>
                                                <td>{{ $pedidos->getRelation('status')->name }}</td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                        {{--<a title="Editar" class="btn btn-default btn-sm" href="{{ route('pedido.edit', $dd->id) }}">
                                                            <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"></span>
                                                        </a>--}}
                                                        @if(in_array($pedidos->status, [1,4]))
                                                            <a title="Pagar" class="btn btn-default btn-sm" href="{{ route('pedido.bonus.visualizar', $pedidos->id) }}">
                                                                <span class="fa fa-money text-success" aria-hidden="true"></span> Pagar
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                        {{-- @endforeach--}}

                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Item</th>
                                            <th>Nome</th>
                                            <th>Usuário</th>
                                            <th>Valor</th>
                                            <th>Data compra</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
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