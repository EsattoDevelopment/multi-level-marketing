@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            {{$title}}
        </h1>
        <ol class="breadcrumb hidden-xs">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Meus depósitos</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                @if(isset($usuarioDepositosAguardandoDeposito))
                    <div class="box">
                        <div class="box-header">
                            Veja abaixo a lista de depósitos aguardando sua realização:
                        </div>
                        <div class="box-body">
                            <table id="tabela_portugues" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Nº Doc</th>
                                    <th>Valor</th>
                                    <th>Data do depósito</th>
                                    <th class="text-green">INSTRUÇÕES PARA DEPÓSITO</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($usuarioDepositosAguardandoDeposito as $dd)
                                    <tr>
                                        <td>{{ $dd->id }}</td>
                                        <td>{{ mascaraMoeda($sistema->moeda, $dd->valor_total, 2, true) }}</td>
                                        <td>{{ $dd->data_compra->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                <a title="CONFIRMAR" class="btn btn-default btn-sm" href="{{ route('deposito.usuario', [Auth::user()->id, $dd->id]) }}">
                                                    <span class="fa fa-money text-success" aria-hidden="true"></span> Opções de Depósito
                                                </a>
                                                <a title="Cancelar" class="btn btn-default btn-sm" href="{{ route('pedido.usuario.pedido.cancelar', [Auth::user()->id, $dd->id]) }}">
                                                    <span class="glyphicon glyphicon-remove-circle text-danger" aria-hidden="true"></span> Cancelar
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if(isset($usuarioDepositosAguardandoConferencia))
                    <div class="box">
                        <div class="box-header">
                            Veja abaixo a relação de depósitos que estão sendo conferidos pela empresa:
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="tabela_ingles" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Nº Doc</th>
                                    <th>Valor</th>
                                    <th>Data do depósito</th>
                                    <th>Método de depósito</th>
                                    <th>Aguarde nossa conferência</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($usuarioDepositosAguardandoConferencia as $dd)
                                    <tr>
                                        <td>{{ $dd->id }}</td>
                                        <td>{{ mascaraMoeda($sistema->moeda, $dd->valor_total, 2, true) }}</td>
                                        <td>{{ $dd->data_compra->format('d/m/Y') }}</td>
                                        <td>{{ $dd->getRelation('dadosPagamento')->metodoPagamento->name }}</td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                @if($dd->getRelation('dadosPagamento')->metodoPagamento->id == 1)
                                                    <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                        <a title="2ª via boleto" class="btn btn-warning btn-sm" href="{{ route('pedido.boleto.visualizar.dados', [$dd->id, 0]) }}">
                                                            2ª Via Boleto
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if(isset($usuarioDepositosConfirmados))
                    <div class="box">
                        <div class="box-header">
                            Veja abaixo a lista de depósitos já confirmados pela empresa:
                        </div>
                        <div class="box-body">
                            <table id="tabela_ingles" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Nº Doc</th>
                                    <th>Valor</th>
                                    <th>Data do depósito</th>
                                    <th>Método de depósito</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($usuarioDepositosConfirmados as $dd)
                                    <tr>
                                        <td>{{ $dd->id }}</td>
                                        <td>{{ mascaraMoeda($sistema->moeda, $dd->valor_total, 2, true) }}</td>
                                        <td>{{ $dd->data_compra->format('d/m/Y') }}</td>
                                        <td>{{ $dd->getRelation('dadosPagamento')->metodoPagamento->name ?? '' }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if(isset($usuarioDepositosCancelados))
                    <div class="box">
                        <div class="box-header">
                            Veja abaixo a lista de depósitos não efetivados:
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="tabela_espanhol" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Nº Doc</th>
                                    <th>Valor</th>
                                    <th>Data do depósito</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($usuarioDepositosCancelados as $dd)
                                    <tr>
                                        <td>{{ $dd->id }}</td>
                                        <td>{{ mascaraMoeda($sistema->moeda, $dd->valor_total, 0, true) }}</td>
                                        <td>{{ $dd->data_compra->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
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