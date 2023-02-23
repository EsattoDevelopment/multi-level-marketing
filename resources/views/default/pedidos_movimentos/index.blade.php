@extends('default.layout.main')

@section('content')

    <section class="content-header">
        <h1>
            Licenciamentos
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Licenciamentos</li>
        </ol>
    </section>

    <section class="content">
        <!-- row -->
        <div class="row">
            <div class="col-md-12 col-xs-12">
                @forelse($extrato as $ex)
                    <ul class="timeline">
                        <li class="time-label">
                            <span class="bg-default" style="background-color: {{ $ex['cor_item'] }}; color: #FFF;">{{ $ex['name_item'] }}</span>
                        </li>
                        <li class="box collapsed-box">
                            <i class="fa fa-usd bg-gray"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header">
                                    <span class="label label-default" data-widget="collapse"><i class="fa fa-plus"></i></span>
                                    Valor bruto: {{ mascaraMoeda($sistema->moeda, $ex['total'], 2, true) }}
                                </h3>
                            </div>
                            <div class="timeline-item box-body" style="background: none; border: none; box-shadow: none; margin-top: 10px; margin-left: 50px; display:none;">
                                <ul class="timeline">
                                    <li style="margin-right: 0;">
                                        <i class="fa fa-usd bg-gray"></i>
                                        @foreach($ex['itens'] as $item)
                                            <div class="timeline-item box box-body collapsed-box" style="margin-right: 0; margin-bottom: 10px;">
                                                <h3 class="timeline-header col-lg-9 col-md-9 col-xs-12">
                                                    <span class="label label-default" data-widget="collapse"><i class="fa fa-plus"></i></span>
                                                    Depósito: {{ mascaraMoeda($sistema->moeda, $item['valor'], 2, true) }} - Contrato N° {{ $item['id_pedido'] }}
                                                </h3>
                                                <span class="time col-lg-3 col-md-3 col-xs-12 text-right" style="border-bottom: 1px solid #f4f4f4;"><i class="fa fa-calendar"></i> Depositado em {{ $item['data'] }}</span>
                                                @if($item['status'] == 7)
                                                    <p class="text-green col-md-12">
                                                        Parabéns! Este depósito atingiu o valor total contratado de Capital.
                                                    </p>
                                                    @if($item['total'] > 0)
                                                        <p class="text-info col-md-12">
                                                            Correção de Capital atual {{ mascaraMoeda($sistema->moeda, $item['total'], 2, true) }}
                                                        </p>
                                                    @endif
                                                @endif
                                                <div class="timeline-item box-body box-movimento col-xs-12 col-md-12 col-lg-12" data-pedido="{{ $item['id_pedido'] }}" style="display: none;">
                                                    <table id="tabela_index_{{ $item['id_pedido'] }}" data-id="{{ $item['id_pedido'] }}" class="table table-striped table-bordered nowrap">
                                                        <thead>
                                                        <tr>
                                                            <th>Valor Pago</th>
                                                            <th>Porcentagem</th>
                                                            <th>Data</th>
                                                            <th>Observação</th>
                                                        </tr>
                                                        </thead>
                                                        <tfoot align="right">
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                    <p id="tabela_index_{{ $item['id_pedido'] }}_capitalizado"></p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </li>
                                    <li>
                                        <i class="fa fa-th-large bg-gray"></i>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <i class="fa fa-th-large bg-gray"></i>
                        </li>
                    </ul>
        @empty
        @endforelse
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