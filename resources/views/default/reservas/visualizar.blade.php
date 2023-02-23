@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Reserva de <strong>{{ $dados->getRelation('pacote')->chamada }}</strong>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Reservas</li>
            <li>{{ $dados->getRelation('pacote')->getRelation('tipoPacote')->name }}</li>
            <li class="active">{{ $dados->getRelation('pacote')->chamada }}</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-widget">
                    <div class="box-header with-border">
                        <h3 class="box-title">Dados da reserva</h3>
                    </div>
                    <div class="box-body">
                        <div class="row col-xs-12">


                                {{--Acomodações--}}
                                <div class="row" id="acomodacao">
                                    <div class="col-md-12">
                                        <div class="box box-solid box-info">
                                            <div class="box-header">
                                                Acomodação escolhida
                                            </div>
                                            <div class="box-body">
                                                {{ $dados->getRelation('acomodacao')->name }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{--Periodo--}}
                                <div class="row" id="periodo">
                                    <div class="col-md-12">
                                        <div class="box box-solid box-warning">
                                            <div class="box-header">
                                                Periodo
                                            </div>
                                            <div class="box-body">
                                                <div class="form-group col-xs-12 col-md-6">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            Ida
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        <input type="text" readonly value="{{ $dados->data_ida }}" name="from" class="form-control pull-right"
                                                               id="from">
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>

                                                <div class="form-group col-xs-12 col-md-6">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            Volta
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        <input type="text" readonly value="{{ $dados->data_volta }}" name="to" class="form-control pull-right"
                                                               id="to">
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--!Periodo--}}


                                {{--Resumo--}}
                                <div class="row" id="resumo">
                                    <div class="col-md-12">
                                        <div class="box box-solid box-danger">
                                            <div class="box-header">
                                                Resumo
                                            </div>
                                            <div class="box-body">
                                                {{--<div class="form-group col-xs-12 col-md-6">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                            Todas de diárias
                                                        </div>
                                                        <input type="text" name="total-diarias" readonly
                                                               class="form-control pull-right" id="diarias">
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>--}}

                                                <div class="form-group col-xs-12 col-md-6">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                            Situação
                                                        </div>
                                                        <input type="text" value="{{ $dados->getRelation('statusPedidoPacote')->name }}" readonly
                                                               class="form-control pull-right" id="diarias">
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>

                                                <div class="form-group col-xs-12 col-md-6">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            Valor em GMilhas<i class="fa fa-registered"></i>
                                                        </div>
                                                        <input type="text" name="gmilhas-total" value="{{ mascaraMoeda($sistema->moeda, $dados->valor_milhas_dia_compra, 0) }}" readonly
                                                               class="form-control pull-right text-red" id="gmilhas-total">
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>
                                            </div>
                                            <div class="box-footer">
                                                @if($dados->pode_cancelar && $dados->getRelation('statusPedidoPacote')->id == 1)
                                                    <form id="cancelamento" action="{{ route('reservas.cancelamento') }}" method="post">
                                                        {!! csrf_field() !!}
                                                        <input type="hidden" name="reserva" value="{{ $dados->id }}">
                                                        <button type="submit" class="btn btn-danger">Solicitar cancelamento de reserva</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--!Resumo--}}

                                {{--<div class="row hidden" id="submit-form">
                                    <input type="hidden" value="{{ $dados->id }}" name="pacote">
                                    <div class="col-md-12">
                                        <div class="box">
                                            <div class="box-body">
                                                <button type="submit" class="btn btn-success btn-block btn-lg">Usar GMilhas</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- Widget: user widget style 1 -->
                <div class="box box-widget">
                    <div class="box-header with-border">
                        <h3 class="box-title">Dados {{ $dados->getRelation('pacote')->getRelation('tipoPacote')->name }}</h3>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel"
                                     style="max-width: 900px; margin: auto;">
                                    <ol class="carousel-indicators">
                                        @foreach($dados->getRelation('pacote')->getRelation('galeria')->getRelation('imagens') as $key => $img)
                                            <li data-target="#carousel-example-generic" data-slide-to="{{ $key }}"
                                                @if($key == 0) class="active" @endif></li>
                                        @endforeach
                                    </ol>
                                    <div class="carousel-inner">
                                        @foreach($dados->getRelation('pacote')->getRelation('galeria')->getRelation('imagens') as $key => $img)
                                            <div class="item @if($key == 0) active @endif">
                                                <img src="{{ route('imagecache', ['carousel', $img->imagem]) }}"
                                                     alt="{{ $img->legenda }}">

                                                <div class="carousel-caption">
                                                    {{ $img->legenda }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                                        <span class="fa fa-angle-left"></span>
                                    </a>
                                    <a class="right carousel-control" href="#carousel-example-generic"
                                       data-slide="next">
                                        <span class="fa fa-angle-right"></span>
                                    </a>
                                </div>
                                <!-- /.box -->
                            </div>
                            <div class="col-sm-12">
                                <div class="box box-solid">
                                    <div class="box-body">
                                        <h2>
                                            {{ $dados->getRelation('pacote')->chamada }}
                                        </h2>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box box-solid">
                                    <div class="box-header with-border">
                                        <i class="fa fa-text-width"></i>

                                        <h3 class="box-title text-black">Descrição</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        {!! $dados->getRelation('pacote')->descricao !!}
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">

                    </div>
                    <!-- /.widget-user -->
                </div>
                <!-- /.col -->
            </div>
        </div>
    </section>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert/sweetalert.css') }}">
@endsection

@section('script')
    <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script>
        $(function () {
            $('#cancelamento').submit(function (event) {

                event.preventDefault();

                swal({
                        title: "Você tem certeza?",
                        text: "Você esta preste a solicitar o cancelamento da sua reserva!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Solicitar",
                        cancelButtonText: "Cancelar",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm){
                        if (isConfirm) {
                            $('#cancelamento').unbind('submit').submit();
                            swal("Enviando...", "Solicitação sendo enviada!", "success");
                        } else {
                            swal("Opa", "Sua reserva esta segura =)", "error");
                        }
                    });
            });
        })
    </script>
@endsection

