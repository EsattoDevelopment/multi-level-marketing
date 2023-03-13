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
                        <li class="active"><a href="#aguardando_confimacao" data-toggle="tab">Aguardando confirmação <span class="badge bg-yellow">{{ $pedidos_aguarda_confimacao->count() }}</span></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="aguardando_confimacao">
                            <div class="box">
                                <div class="box-header">
                                    Lista de pedidos aguardando confirmação pagamento
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table id="tabela_portugues" class="table table-bordered table-striped responsive">
                                        <thead>
                                        <tr>
                                            <th>Nº Doc</th>
                                            <th>Item</th>
                                            <th>Nome</th>
                                            <th>Metodo</th>
                                            {{--<th>Usuário</th>--}}
                                            <th>Valor</th>
                                            <th>Data compra</th>
                                            <th>Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($pedidos_aguarda_confimacao as $dd)
                                            <tr>
                                                <td>{{ $dd->id }}</td>
                                                @if($dd->getRelation('itens')->first())
                                                <td>{{ $dd->getRelation('itens')->first()->getRelation('itens')->name }}</td>
                                                @else
                                                <td>Nenhum</td>
                                                @endif
                                                <td>{{ $dd->user->name }}</td>
                                                <td>{{ $dd->dadosPagamento->metodoPagamento ? $dd->dadosPagamento->metodoPagamento->name : '' }}</td>

                                                {{--<td>{{ $dd->getRelation('user')->username }}</td>--}}
                                                <td>{{ mascaraMoeda($sistema->moeda, $dd->valor_total, 2, true) }}</td>
                                                <td>{{ $dd->data_compra->format('d/m/Y H:i:s') }}</td>
                                                <td>
                                                    <form method="post" id="formDel_{{ $dd->id }}" action="{{ route('pedido.destroy', $dd->id) }}">
                                                        {!! csrf_field() !!}
                                                        <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                            <a title="Pagar" class="btn btn-default btn-sm" href="{{ route('pedido.show', $dd->id) }}">
                                                                <span class="fa fa-money text-success" aria-hidden="true"></span> Verificar
                                                            </a>
                                                            {{--<a title="Desativar" class="btn btn-default btn-sm" href="{{ route('pedido.delete', $dd->id) }}">
                                                                <span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span>
                                                            </a>
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <button type="submit" data-id="{{ $dd->id }}" class="btn btn-danger btn-sm botao-del" >
                                                                <span class="glyphicon glyphicon-trash text-black"></span>
                                                            </button>--}}
                                                            <a title="Cancelar" class="btn btn-default btn-sm" href="{{ route('pedido.usuario.pedido.cancelar', [$dd->user_id, $dd->id]) }}">
                                                                <span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span> Cancelar
                                                            </a>
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
{{--    <script src="{{ asset('js/backend/list.js') }}" type="text/javascript"></script>--}}

@endsection