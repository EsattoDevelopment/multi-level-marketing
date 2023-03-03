@extends('default.layout.main')

@section('content')

    <section class="content-header">
        <h1>
            Extrato
        </h1>
        <ol class="breadcrumb hidden-xs">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><b>Credenciamentos</b></li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Widget: user widget style 1 -->
                <div class="box box-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-black" style="background: url('{{ asset('storage/images/empresa/background.webp') }}') center center;">
                        <h3 class="widget-user-username">Credenciamentos</h3>
                        <h5 class="widget-user-desc hidden-xs">Posição em {{ \Carbon\Carbon::now()->format('d/m/Y') }}</h5>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" src="{{ asset('images/logo-fundo.png') }}" alt="{{ env('COMPANY_NAME') }}">
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">{{ mascaraMoeda($sistema->moeda, $depositado, 2, true) }}</h5>
                                    <span class="description-text">Contratado</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">{{ mascaraMoeda($sistema->moeda, $capitalizado, 2, true) }}</h5>
                                    <span class="description-text">Capitalizado</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">{{ mascaraMoeda($sistema->moeda, $total, 2, true) }}</h5>
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
            <div class="col-md-12">
                @forelse($extrato as $key => $ex)
                    <div class="col-md-4">
                        <!-- Widget: user widget style 1 -->
                        <div class="box box-widget widget-user-2">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header" style="background-color: {{ $ex['cor_item'] }}; color: #FFF;">
                                {{--<div class="widget-user-image" style="background-color: #ffffff;">
                                    <img class="img-circle" src="{{ asset('images/logo-fundo.png') }}" alt="User Avatar">
                                </div>--}}
                                <!-- /.widget-user-image -->
                                <h3 class="widget-user-username" style="font-weight: 700;">{{ $ex['name_item'] }}</h3>
                                {{--<h5 class="widget-user-desc">Lead Developer</h5>--}}
                            </div>
                            <div class="box-footer no-padding">
                                <ul class="nav nav-stacked">
                                    <li><a href="javascript:;">Total <span class="pull-right badge bg-blue">{{ mascaraMoeda($sistema->moeda, $ex['total'], 2, true) }}</span></a></li>
                                    <li><a href="javascript:;">Contratado <span class="pull-right badge bg-aqua">{{ mascaraMoeda($sistema->moeda, $ex['depositado'], 2, true) }}</span></a></li>
                                    <li><a href="javascript:;">Capitalizado <span class="pull-right badge bg-green">{{ mascaraMoeda($sistema->moeda, $ex['capitalizado'], 2, true) }}</span></a></li>
                                    <li><a href="javascript:;">Qtd. de Contratos <span class="pull-right badge bg-red">{{ count($ex['itens']) }}</span></a></li>
                                    <li class="bg-gray"><a href="{{ route('capitalizacao.item', $key) }}" class="text-bold">Clique para ver mais</a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- /.widget-user -->
                    </div>
                @empty

                @endforelse
            </div>
        </div>
    </section>
@endsection

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/extensions/Responsive/css/dataTables.responsive.css') }}">
    <style>
        .timeline .box{
            position: inherit;
            border-radius: inherit;
            background: none;
            border-top: none;
            margin-bottom: inherit;
            width: inherit;
            box-shadow: inherit;
        }

        .timeline-item p:last-child{
            margin-bottom: 0px;
        }

        ul.timeline:not(:first-child) {
            margin-top: 40px;
        }

        .timeline-header span[data-widget='collapse']{
            cursor: pointer;
        }

        .timeline-item.box-body .time{
            color: #999;
            float: right;
            padding: 0px;
            font-size: 12px;
        }
    </style>
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

            $('.box-movimento').bind({
                expanded: function (e) {
                    var element = $(e.target);
                    var p = element.data('pedido');
                    if  (p) {
                        if (! $.fn.DataTable.isDataTable('#tabela_index_' + p)) {
                            $('#tabela_index_' + p).DataTable({
                                responsive: true,
                                processing: true,
                                serverSide: true,
                                searchDelay: 500,
                                ajax: {
                                    type: 'GET',
                                    url: '{!! route('deposito.extrato.json') !!}',
                                    data: {id: p}
                                },
                                columns: [
                                    {data: 'valor_manipulado', name: 'pm.valor_manipulado'},
                                    {data: 'porcentagem', name: 'porcentagem', orderable: false, searchable: false},
                                    {data: 'created_at', name: 'pm.created_at'},
                                    {data: 'descricao', name: 'pm.descricao'},
                                ],
                                "ordering": false,
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
                                },
                            });
                        }
                    }
                }
            });
        });
    </script>
@endsection
