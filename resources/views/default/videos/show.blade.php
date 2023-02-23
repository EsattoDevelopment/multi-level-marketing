@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Lista de vídeos
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('videos.show') }}"><i class="fa fa-play-circle"></i> Vídeos</a></li>
            <li class="active"><i class="glyphicon glyphicon-th-list"></i> Lista</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#{{$categoria}}" data-toggle="tab">{{$categoria}}</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="{{$categoria}}">
                            <div class="box">
                                <div class="box-header">
                                </div>
                                <div class="box-body">
                                    @foreach ($dados as $dd)
                                        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-3 col-xl-3">
                                            @if($dd->tipo == 1)
                                                <a data-fancybox href="https://www.youtube.com/watch?v={{$dd->codigo}}?autoplay=1">
                                                    <img id="{{$dd->id}}" src="{{$dd->capa}}" alt="" width="100%" height="100%">
                                                </a>
                                            @elseif($dd->tipo == 2)
                                                <a data-fancybox href="https://vimeo.com/{{$dd->codigo}}?autoplay=1">
                                                    <img id="{{$dd->id}}" src="{{$dd->capa}}" alt="" width="100%" height="100%">
                                                </a>
                                            @elseif($dd->tipo == 0 && $dd->codigo)
                                                <a data-fancybox href="{{$dd->codigo}}">
                                                    <video id="{{$dd->id}}" width="100%" height="auto">
                                                        <source src="{{$dd->codigo}}" type="video/mp4">
                                                    </video>
                                                </a>
                                            @endif
                                            <div class="text-center form-group">
                                                <h5><strong>{{$dd->nome}}</strong></h5>
                                                <div class="text-justify">
                                                        {{$dd->descricao}}
                                                </div>
                                                <div class="form-group">
                                                    <h6><strong>{{$dd->created_at->format('d/m/Y')}}</strong></h6>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/extensions/Responsive/css/dataTables.responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="/plugins/fancybox/jquery.fancybox.min.css">

@endsection

@section('script')
    <!-- DataTables -->
    <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="/js/backend/tabelas.js" type="text/javascript"></script>
    <script src="/js/backend/datatables.js" type="text/javascript"></script>
    <script src="/js/backend/bootstrap-confirmation.js" type="text/javascript"></script>

    <!-- JS -->
    <script src="/plugins/fancybox/jquery.fancybox.min.js"></script>
@endsection