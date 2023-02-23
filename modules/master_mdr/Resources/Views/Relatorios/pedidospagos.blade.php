@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Relatório Pedidos Pagos
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Relatórios</li>
            <li class="active">Pedidos pagos</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Relatórios de Pedidos Pagos</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="{{ route('relatorio.pedidos.pagos') }}" method="post" target="_blank">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="form-group col-lg-4 col-xs-12">
                                <label>Método de pagamento</label>
                                <select class="form-control select2" name="metodoPagamento" data-placeholder="Selecione um método de pagamento" style="width: 100%;">
                                    <option value="0">Todos</option>
                                    @foreach($metodosPagamentos as $metodoPagamento)
                                        <option value="{{$metodoPagamento->id}}">{{$metodoPagamento->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-4 col-xs-12">
                                <label>Data Início</label>
                                <input type="date" name="inicio" id="from" class="form-control" required value="">
                            </div>
                            <div class="form-group col-lg-4 col-xs-12">
                                <label>Data Final</label>
                                <input type="date" name="fim" id="to" class="form-control" required value="">
                            </div>

                            <div class="form-group col-xs-12">
                                <label>Itens</label>
                                <select class="form-control select2" multiple="multiple" name="itens[]"
                                        data-placeholder="Todos" style="width: 100%;">
                                    @foreach($itens as $item)
                                        <option value="{{ $item->id }}" @if(old('item')) {{ in_array($item->id, old('item')) ? 'selected' : '' }} @endif >{{"#$item->id - $item->name"}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" name="tipo" value="1" class="btn btn-primary">Relatório Sintetico</button>
                            <button type="submit" name="tipo" value="2" class="btn btn-success">Relatório Análitico</button>
                            {{--<button type="submit" name="tipo" value="2" class="btn btn-primary">Gerar em Excel</button>--}}
                        </div>
                    </form>
                </div>

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection

@section('style')
    {{--<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">--}}
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css')}}">

@endsection

@section('script')

    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/i18n/pt-BR.js') }}"></script>

    <script>
        $(function () {
        //Initialize Select2 Elements
            $(".select2").select2();
        });
    </script>
 {{--   <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


    <script src="../../plugins/input-mask/jquery.inputmask.js"></script>
    <script src="../../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="../../plugins/input-mask/jquery.inputmask.extensions.js"></script>--}}

@endsection