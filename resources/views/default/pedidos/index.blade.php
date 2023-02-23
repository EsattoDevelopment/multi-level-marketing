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
                        <li class="active"><a href="#aguardando_pagamento" data-toggle="tab">Aguardando pagamento <span class="badge">{{ $pedidos_aguardando->count() }}</span></a></li>
                        <li><a href="#aguardando_confimacao" data-toggle="tab">Aguardando confirmação <span class="badge bg-yellow">{{ $pedidos_aguarda_confimacao->count() }}</span></a></li>
                        <li><a href="#pagos" data-toggle="tab">Pagos <span class="badge bg-green">{{ $pedidos_pagos->count() }}</span></a></li>
                        <li><a href="#cancelados" data-toggle="tab">Cancelados <span class="badge bg-red">{{ $pedidos_cancelados->count() }}</span></a></li>
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
                                            <th>Nome</th>
                                            <th>Usuário</th>
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
                                                <td>{{ $dd->getRelation('user')->name }}</td>
                                                <td>{{ $dd->getRelation('user')->username }}</td>
                                                <td> {{ mascaraMoeda($sistema->moeda, $dd->valor_total, 2, true) }}</td>
                                                <td>{{ $dd->data_compra->format('d/m/Y') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                        {{--<a title="Editar" class="btn btn-default btn-sm" href="{{ route('pedido.edit', $dd->id) }}">
                                                            <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"></span>
                                                        </a>--}}
                                                        <a title="Pagar" class="btn btn-default btn-sm" href="{{ route('pedido.show', $dd->id) }}">
                                                            <span class="fa fa-money text-success" aria-hidden="true"></span> Visualizar
                                                        </a>
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

                        <div class="tab-pane" id="aguardando_confimacao">
                            <div class="box">
                                <div class="box-header">
                                    Lista de pedidos aguardando confirmação pagamento
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
                                            <th>Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($pedidos_aguarda_confimacao as $dd)
                                            <tr>
                                                <td>{{ $dd->id }}</td>
                                                <td>{{ $dd->getRelation('itens')->first()->getRelation('itens')->name }}</td>
                                                <td>{{ $dd->getRelation('user')->name }}</td>
                                                <td>{{ $dd->getRelation('user')->username }}</td>
                                                <td> {{ mascaraMoeda($sistema->moeda, $dd->valor_total, 2, true) }}</td>

                                                <td>{{ $dd->data_compra->format('d/m/Y') }}</td>
                                                <td>
                                                    <form method="post" id="formDel_{{ $dd->id }}" action="{{ route('pedido.destroy', $dd->id) }}">
                                                        {!! csrf_field() !!}
                                                        <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                            <a title="Pagar" class="btn btn-default btn-sm" href="{{ route('pedido.show', $dd->id) }}">
                                                                <span class="fa fa-money text-success" aria-hidden="true"></span> Visualizar
                                                            </a>
                                                            {{--<a title="Desativar" class="btn btn-default btn-sm" href="{{ route('pedido.delete', $dd->id) }}">
                                                                <span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span>
                                                            </a>
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button type="submit" data-id="{{ $dd->id }}" class="btn btn-danger btn-sm botao-del" >
                                                                <span class="glyphicon glyphicon-trash text-black"></span>
                                                            </button>--}}
                                                        </div>
                                                    </form>
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
                                            <th>Nome</th>
                                            <th>Usuário</th>
                                            <th>Valor</th>
                                            <th>Data compra</th>
                                            <th>Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($pedidos_pagos as $dd)
                                            <tr>
                                                <td>{{ $dd->id }}</td>
                                                <td>{{ $dd->itens->first()->getRelation('itens')->name }}</td>
                                                <td>{{ $dd->user->name }}</td>
                                                <td> {{ $dd->user->username }}</td>
                                                <td> {{ mascaraMoeda($sistema->moeda, $dd->valor_total, 2, true) }}</td>

                                                <td>{{ $dd->data_compra->format('d/m/Y') }}</td>
                                                <td>
                                                    <form method="post" id="formDel_{{ $dd->id }}" action="{{ route('pedido.destroy', $dd->id) }}">
                                                        {!! csrf_field() !!}
                                                        <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                            {{--<a title="Editar" class="btn btn-default btn-sm" href="{{ route('pedido.edit', $dd->id) }}">
                                                                <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"></span>
                                                            </a>--}}
                                                            {{--<a title="Desativar" class="btn btn-default btn-sm" href="{{ route('pedido.delete', $dd->id) }}">
                                                                <span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span>
                                                            </a>
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button type="submit" data-id="{{ $dd->id }}" class="btn btn-danger btn-sm botao-del" >
                                                                <span class="glyphicon glyphicon-trash text-black"></span>
                                                            </button>--}}
                                                        </div>
                                                    </form>
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
                                            <th>Nome</th>
                                            <th>Usuário</th>
                                            <th>Valor</th>
                                            <th>Data compra</th>
                                            <th>Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($pedidos_cancelados as $dd)
                                            <tr>
                                                <td>{{ $dd->id }}</td>
                                                <td>{{ $dd->itens->first()->getRelation('itens')->name }}</td>
                                                <td>{{ $dd->user->name }}</td>
                                                <td>{{ $dd->user->username }}</td>
                                                <td> {{ mascaraMoeda($sistema->moeda, $dd->valor_total, 2, true) }}</td>
                                                <td>{{ $dd->data_compra->format('d/m/Y') }}</td>
                                                <td>
                                                    <form method="post" id="formDel_{{ $dd->id }}" action="{{ route('pedido.destroy', $dd->id) }}">
                                                        {!! csrf_field() !!}
                                                        <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                            {{--<a title="Editar" class="btn btn-default btn-sm" href="{{ route('pedido.edit', $dd->id) }}">
                                                                <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"></span>
                                                            </a>--}}
                                                            {{--<a title="Desativar" class="btn btn-default btn-sm" href="{{ route('pedido.delete', $dd->id) }}">
                                                                <span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span>
                                                            </a>
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button type="submit" data-id="{{ $dd->id }}" class="btn btn-danger btn-sm botao-del" >
                                                                <span class="glyphicon glyphicon-trash text-black"></span>
                                                            </button>--}}
                                                        </div>
                                                    </form>
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
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/backend/tabelas.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/datatables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/list.js') }}" type="text/javascript"></script>

@endsection