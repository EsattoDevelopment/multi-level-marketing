@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
        </h1>
        <ol class="breadcrumb hidden-xs">
            <li class="active"><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            {{--Tranferências aguardado--}}
            <div class="col-xs-12 col-md-4 col-lg-4">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Solicitações de transferência</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <ul class="products-list product-list-in-box">
                            @foreach($transferencias as $solicitacao)
                                <li class="item">
                                    <div class="product-img">
                                        <img src="@if($solicitacao->usuario->image){{ route('imagecache', ['user', 'user/'.$solicitacao->usuario->image]) }}@else{{ route('imagecache', ['user', 'user-img.jpg']) }}@endif" alt="Product Image">
                                    </div>
                                    <div class="product-info">
                                        <a href="javascript:void(0)" class="product-title">{{ $solicitacao->usuario->name }}
                                            <span class="label label-warning pull-right">{{ mascaraMoeda($sistema->moeda, $solicitacao->valor, 2, true) }}</span></a>
                                        <span class="label label-info pull-right">{{ $solicitacao->dt_solicitacao->format('d/m/Y H:i:s') }}</span>
                                        <span class="product-description">{!! $solicitacao->conta->dados_min !!}</span>
                                    </div>
                                </li>
                                <!-- /.item -->
                                @endforeach
                        </ul>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer text-center">
                        <a href="{{ route('transferencia.em_liquidacao') }}" class="uppercase">Ver todas solicitações</a>
                    </div>
                    <!-- /.box-footer -->
                </div>

            </div>

            {{--Transferências realizadas--}}
            <div class="col-xs-12 col-md-4 col-lg-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Ultimas Transferencia efetivadas</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <ul class="products-list product-list-in-box">
                            @foreach($transfEfetivas as $tre)
                                <li class="item">
                                    <div class="product-img">
                                        <img src="@if($tre->usuario->image){{ route('imagecache', ['user', 'user/'.$tre->usuario->image]) }}@else{{ route('imagecache', ['user', 'user-img.jpg']) }}@endif" alt="Product Image">
                                    </div>
                                    <div class="product-info">
                                        <a href="javascript:void(0)" class="product-title">{{ $tre->usuario->name }}
                                            <span class="label label-warning pull-right">{{ mascaraMoeda($sistema->moeda, $tre->valor, 2, true) }}</span></a>
                                            <span class="label label-info pull-right">{{ $tre->dt_efetivacao->format('d/m/Y H:i:s') }}</span>
                                        <span class="product-description">{!! $tre->dado_bancario_id ? $tre->conta->dados_min : 'para: ' . $tre->destinatario->name !!}</span>
                                    </div>
                                </li>
                                <!-- /.item -->
                            @endforeach
                        </ul>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer text-center">
                        <a href="{{ route('transferencia.todos') }}" class="uppercase">Ver todas solicitaçoes</a>
                    </div>
                    <!-- /.box-footer -->
                </div>

            </div>

            {{--Depósitos aguardando confirmação--}}
            <div class="col-xs-12 col-md-4 col-lg-4">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Depósitos aguardando confirmação</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <ul class="products-list product-list-in-box">
                            @foreach($depositoAguardandoConfirmacao as $dep)
                                <li class="item">
                                    <div class="product-img">
                                        <img src="@if($dep->usuario->image){{ route('imagecache', ['user', 'user/'.$dep->usuario->image]) }}@else{{ route('imagecache', ['user', 'user-img.jpg']) }}@endif" alt="Product Image">
                                    </div>
                                    <div class="product-info">
                                        <a href="javascript:void(0)" class="product-title">{{ $dep->usuario->name }}
                                            <span class="label label-warning pull-right">{{ $dep->dadosPagamento->metodoPagamento ? $dep->dadosPagamento->metodoPagamento->name : '' }}</span></a>
                                            <span class="label label-info pull-right">{{ $dep->updated_at->format('d/m/Y H:i:s') }}</span>
                                        <span class="product-description text-black text-bold">{{ mascaraMoeda($sistema->moeda, $dep->valor_total, 2, true) }}</span>
                                    </div>
                                </li>
                                <!-- /.item -->
                            @endforeach
                        </ul>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer text-center">
                        <a href="{{ route('pedido.aguardando-confirmacao') }}" class="uppercase">Ver todas</a>
                    </div>
                    <!-- /.box-footer -->
                </div>

            </div>

        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-xs-12 col-md-6">
                <!-- solid sales graph -->
                <div class="box box-solid bg-olive-active">
                    <div class="box-header">
                        <i class="fa fa-th"></i>

                        <h3 class="box-title">Depósitos</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body border-radius-none">
                        <div class="chart" id="line-chart" style="height: 250px;"></div>
                    </div>
                    <!-- /.box-body -->
                    {{--TODO Colocar metodos de pagamentos ou ainda algum parametros relevante--}}
                    {{--<div class="box-footer no-border">
                        <div class="row">
                            <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                                <input type="text" class="knob" data-readonly="true" value="20" data-width="60" data-height="60"
                                       data-fgColor="#39CCCC">

                                <div class="knob-label">Mail-Orders</div>
                            </div>
                            <!-- ./col -->
                            <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                                <input type="text" class="knob" data-readonly="true" value="50" data-width="60" data-height="60"
                                       data-fgColor="#39CCCC">

                                <div class="knob-label">Online</div>
                            </div>
                            <!-- ./col -->
                            <div class="col-xs-4 text-center">
                                <input type="text" class="knob" data-readonly="true" value="30" data-width="60" data-height="60"
                                       data-fgColor="#39CCCC">

                                <div class="knob-label">In-Store</div>
                            </div>
                            <!-- ./col -->
                        </div>
                        <!-- /.row -->
                    </div>--}}
                    <!-- /.box-footer -->
                </div>
                <!-- /.box -->
            </div>
        </div>

    </section>
    <!-- /.content -->

@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert/sweetalert.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/fancybox/jquery.fancybox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert/sweetalert.css') }}">
    <!-- Morris chart -->
    <link rel="stylesheet" href="{{ asset('plugins/morris.js/morris.css') }}">
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <!-- ChartJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.js"></script>
    <script src="{{ asset('plugins/fancybox/jquery.fancybox.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>

    <!-- Morris.js charts -->
    <script src="{{ asset('plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('plugins/morris.js/morris.min.js') }}"></script>
    <!-- jQuery Knob Chart -->
 {{--   <script src="{{ asset('plugins/knob/jquery.knob.js') }}"></script>--}}

    <script>
        $(function () {
            /* jQueryKnob */
            /*$('.knob').knob();
*/
            var line = new Morris.Bar({
                element          : 'line-chart',
                resize           : true,
                data             : {!! $dadosChartDeposito !!},
                xkey             : 'dia',
                ykeys            : ['depositos'],
                /*xLabelFormat: function(d) {
                    return d.getDate()+'/'+(d.getMonth()+1)+'/'+d.getFullYear();
                },*/
 /*               hoverCallback: function (index, options, content, row) {
                    console.log(index, options, content, row);
                    return "test";
                },*/
                labels           : ['Depósitos R$'],
                xLabelAngle      : 45,
                lineColors       : ['#333333'],
                lineWidth        : 2,
                hideHover        : 'auto',
                gridTextColor    : '#fff',
                gridStrokeWidth  : 0.4,
                pointSize        : 4,
                pointStrokeColors: ['#efefef'],
                gridLineColor    : '#efefef',
                gridTextFamily   : 'Open Sans',
                gridTextSize     : 14,
               /* dateFormat: function (ts) {
                    var d = new Date(ts);
                    return d.getDate()+'/'+(d.getMonth()+1)+'/'+d.getFullYear();
                }*/
            });
        })
    </script>

@endsection
