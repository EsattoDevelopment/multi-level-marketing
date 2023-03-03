@extends('default.layout.main')

@section('content')

    <section class="content-header">
        <h1>
            <i class="fa fa-angle-left"></i> <a href="{{ route('capitalizacao.item', $item) }}">Voltar</a>
        </h1>
        <ol class="breadcrumb hidden-xs">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('capitalizacao.index') }}">Credenciamentos</a></li>
            <li><a href="{{ route('capitalizacao.item', $item->id) }}">{{ $item->name }}</a></li>
            <li><b>Contrato N°{{ $pedido->id }}</b></li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Widget: user widget style 1 -->
                <div class="box box-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header" style="background-color: {{ $item->cor_item }}; color: #ffffff;">
                        <h3 class="widget-user-username" style="font-weight: 700;">Contrato N°{{ $pedido->id }}</h3>
                        <h5 class="widget-user-desc pull-right"><b>{{ $item->name }}</b></h5>
                        <h5 class="widget-user-desc">Depositado em <b>{{ $pedido->dadosPagamento->data_pagamento_efetivo->format('d/m/Y') }}</b></h5>
                        <h5 class="widget-user-desc">Término do contrato <br class="visible-xs"> <b>{{ $pedido->dadosPagamento->data_pagamento_efetivo->addMonths($pedido->itens->first()->total_meses_contrato)->format('d/m/Y') }}</b></h5>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" src="{{ asset('images/logo-fundo.png') }}" alt="{{ env('COMPANY_NAME') }}">
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">{{ mascaraMoeda($sistema->moeda, $pedido->valor_total, 2, true) }}</h5>
                                    <span class="description-text">Contratado</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">{{ mascaraMoeda($sistema->moeda, $pedido->movimento_total, 2, true) }}</h5>
                                    <span class="description-text">Capitalizado</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">{{ mascaraMoeda($sistema->moeda, $pedido->movimento_total + ((Auth::user()->titulo->habilita_rede && $item->quitar_com_bonus) ? 0 : $pedido->valor_total), 2, true) }}</h5>
                                    <span class="description-text">Total</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                </div>
                <!-- /.widget-user -->
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-default"><i class="fa fa-line-chart"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total Pago</span>
                        <span class="info-box-number">{{ $pedido->percentual_pago }}<small>%</small></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-default"><i class="fa fa-money"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Correção efetuada</span>
                        <span class="info-box-number">{{ mascaraMoeda($sistema->moeda, $pedido->movimento_total - $pedido->transferido, 2, true) }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-default"><i class="fa fa-bank"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Transferido para Carteira</span>
                        <span class="info-box-number">{{ mascaraMoeda($sistema->moeda, $pedido->transferido, 2, true) }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
{{--            <div class="col-lg-3">
                <div class="info-box bg-aqua">
                    <span class="info-box-icon"><i class="fa fa-line-chart"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Correção total</span>
                        <span class="info-box-number">{{ $pedido->percentual_pago }}%</span>

                        <div class="progress">
                            <div class="progress-bar" style="width: {{ round($pedido->percentual_pago * 100 / ($item->potencial_mensal_teto * $item->meses), 2) }}%"></div>
                        </div>
                        <span class="progress-description">
                    {{ mascaraMoeda($sistema->moeda, $pedido->movimento_total, 2, true) }} correção recebida
                  </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>--}}
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Extrato</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="tabela_index" style="width: 100%;" class="table table-striped table-bordered nowrap table-responsive">
                                <thead>
                                <tr>
                                    <th>Valor Pago</th>
                                    <th>Porcentagem</th>
                                    <th>Data</th>
                                    <th>Saldo</th>
                                    <th>Observação</th>
                                </tr>
                                </thead>
                                {{--<tfoot align="right">
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </tfoot>--}}
                            </table>
                            <p id="tabela_index_capitalizado"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
    <script src="{{ asset('js/backend/datatables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/bootstrap-confirmation.js') }}" type="text/javascript"></script>
    <script>
        $(function(){
            Number.prototype.formatMoney = function(places, symbol, thousand, decimal) {
                places = !isNaN(places = Math.abs(places)) ? places : 2;
                symbol = symbol !== undefined ? symbol : "$";
                thousand = thousand || ",";
                decimal = decimal || ".";
                var number = this,
                    negative = number < 0 ? "-" : "",
                    i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
                    j = (j = i.length) > 3 ? j % 3 : 0;
                return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
            };

            $('#tabela_index').DataTable({
                responsive: true,
                columnDefs: [
                    { responsivePriority: 1, targets: 0 },
                    { responsivePriority: 2, targets: 1 },
                    { responsivePriority: 3, targets: 2 },
                    { responsivePriority: 4, targets: -1 }
                ],
                processing: true,
                serverSide: true,
                searchDelay: 500,
                ajax: {
                    type: 'GET',
                    url: '{!! route('deposito.extrato.json') !!}',
                    data: {id: {{ $pedido->id }}}
                },
                columns: [
                    {data: 'valor_manipulado', name: 'pm.valor_manipulado'},
                    {data: 'porcentagem', name: 'porcentagem', orderable: false, searchable: false},
                    {data: 'created_at', name: 'pm.created_at'},
                    {data: 'saldo', name: 'pm.saldo', orderable: false, searchable: false},
                    {data: 'descricao', name: 'pm.descricao'},
                ],
                "ordering": false/*,
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api(), data;

                    // converting to interger to find total
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,\%,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    // computing column Total of the complete result
                    var capitalizado = api
                        .column(0)
                        .data()
                        .reduce( function (soma, valor, index) {
                            //pivot = data[index].operacao_id == 26 ? -intVal(valor.replace('(', '').replace(')', '')) : intVal(valor);
                            pivot = data[index].operacao_id == 26 ? 0 : intVal(valor);
                            return intVal(soma) + pivot;
                        }, 0);

                    var transferido = api
                        .column(0)
                        .data()
                        .reduce( function (soma, valor, index) {
                            //pivot = data[index].operacao_id == 26 ? -intVal(valor.replace('(', '').replace(')', '')) : intVal(valor);
                            pivot = data[index].operacao_id != 26 ? 0 : intVal(valor.replace('(', '').replace(')', ''));
                            return intVal(soma) + pivot;
                        }, 0);

                    var porcentagem = api
                        .column(1)
                        .data()
                        .reduce( function (soma, valor, index) {
                            pivot = data[index].operacao_id == 26 ? 0 : intVal(valor);
                            return intVal(soma) + pivot;
                        }, 0);

                    // Update footer by showing the total with the reference of the column index
                    $(api.column(0).footer()).html('Total Pago: ' + capitalizado.formatMoney(2, "{{ $sistema->moeda }}", ",", "."));
                    $(api.column(1).footer()).html('Percentual: ' + porcentagem.toFixed(2) + "%");
                    $(api.column(3).footer()).html('Transferido para Carteira: ' + transferido.formatMoney(2, "{{ $sistema->moeda }}", ",", "."));
                },*/
            });
        });
    </script>
@endsection
