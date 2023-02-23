@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Meus pedidos
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Meus pedidos</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#aguardando_pagamento" data-toggle="tab">Aguardando pagamento</a></li>
                        <li><a href="#pagos" data-toggle="tab">Pagos</a></li>
                        <li><a href="#cancelados" data-toggle="tab">Cancelados</a></li>
                    </ul>
                    <div class="tab-content">

                        <div class="active tab-pane" id="aguardando_pagamento">
                            <div class="box">
                                <div class="box-header">
                                    Lista de pedidos aguardando pagamento
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table id="tabela_portugues" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Item</th>
                                            <th>Valor</th>
                                            <th>Data compra</th>
                                            <th>Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($pedidos_aguardando as $dd)
                                            <tr>
                                                <td>{{ $dd->id }}</td>
                                                <td>{{ $dd->getRelation('itens')->first()->getRelation('itens')->name }}</td>
                                                <td>{{ $sistema->moeda }} {{ $dd->valor_total }}</td>
                                                <td>{{ $dd->data_compra->format('d/m/Y') }}</td>
                                                <td>
                                                    <form method="post" id="formDel_{{ $dd->id }}" action="{{ route('pedido.destroy', $dd->id) }}">
                                                        {!! csrf_field() !!}
                                                        <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                            <a title="Editar" class="btn btn-default btn-sm" href="{{ route('pedido.edit', $dd->id) }}">
                                                                <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"></span>
                                                            </a>
                                                            <a title="Desativar" class="btn btn-default btn-sm" href="{{ route('pedido.delete', $dd->id) }}">
                                                                <span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span>
                                                            </a>
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button type="submit" data-id="{{ $dd->id }}" class="btn btn-danger btn-sm botao-del" >
                                                                <span class="glyphicon glyphicon-trash text-black"></span>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Item</th>
                                            <th>Valor</th>
                                            <th>Data compra</th>
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
                        <div class="tab-pane" id="pagos">
                            <div class="box">
                                <div class="box-header">
                                    Lista de pedidos pagos
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table id="tabela_ingles" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Item</th>
                                            <th>Valor</th>
                                            <th>Data compra</th>
                                            <th>Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($pedidos_pagos as $dd)
                                            <tr>
                                                <td>{{ $dd->id }}</td>
                                                <td>{{ $dd->id }}</td>
                                                <td>{{ $sistema->moeda }} {{ $dd->valor_total }}</td>
                                                <td>{{ $dd->data_compra->format('d/m/Y') }}</td>
                                                <td>
                                                    <form method="post" id="formDel_{{ $dd->id }}" action="{{ route('pedido.destroy', $dd->id) }}">
                                                        {!! csrf_field() !!}
                                                        <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                            <a title="Editar" class="btn btn-default btn-sm" href="{{ route('pedido.edit', $dd->id) }}">
                                                                <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"></span>
                                                            </a>
                                                            <a title="Desativar" class="btn btn-default btn-sm" href="{{ route('pedido.delete', $dd->id) }}">
                                                                <span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span>
                                                            </a>
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button type="submit" data-id="{{ $dd->id }}" class="btn btn-danger btn-sm botao-del" >
                                                                <span class="glyphicon glyphicon-trash text-black"></span>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Item</th>
                                            <th>Valor</th>
                                            <th>Data compra</th>
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

                        <div class="tab-pane" id="cancelados">
                            <div class="box">
                                <div class="box-header">
                                    Lista de pedidos cancelados
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table id="tabela_espanhol" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Item</th>
                                            <th>Valor</th>
                                            <th>Data compra</th>
                                            <th>Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($pedidos_cancelados as $dd)
                                            <tr>
                                                <td>{{ $dd->id }}</td>
                                                <td>{{ $dd->id }}</td>
                                                <td>{{ $sistema->moeda }} {{ $dd->valor_total }}</td>
                                                <td>{{ $dd->data_compra->format('d/m/Y') }}</td>
                                                <td>
                                                    <form method="post" id="formDel_{{ $dd->id }}" action="{{ route('pedido.destroy', $dd->id) }}">
                                                        {!! csrf_field() !!}
                                                        <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                            <a title="Editar" class="btn btn-default btn-sm" href="{{ route('pedido.edit', $dd->id) }}">
                                                                <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"></span>
                                                            </a>
                                                            <a title="Desativar" class="btn btn-default btn-sm" href="{{ route('pedido.delete', $dd->id) }}">
                                                                <span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span>
                                                            </a>
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button type="submit" data-id="{{ $dd->id }}" class="btn btn-danger btn-sm botao-del" >
                                                                <span class="glyphicon glyphicon-trash text-black"></span>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Item</th>
                                            <th>Valor</th>
                                            <th>Data compra</th>
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
    <link rel="stylesheet" href="/plugins/datatables/dataTables.bootstrap.css">s
@endsection

@section('script')
    <!-- DataTables -->
    <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="/js/backend/tabelas.js" type="text/javascript"></script>
    <script src="/js/backend/datatables.js" type="text/javascript"></script>
    <script src="/js/backend/list.js" type="text/javascript"></script>
@endsection