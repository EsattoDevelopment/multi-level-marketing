@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            {{-- {{ $textos['todos'] }} {{ $textos['titulo'] }}--}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Usar GMilhas</li>
            <li class="active">Pacotes</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Widget: user widget style 1 -->
                <div class="box box-widget">
                    <div class="box-header with-border">
                        <h3 class="box-title">Pacotes</h3>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- Custom Tabs -->
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#nacional" data-toggle="tab">Nacionais</a></li>
                                        <li><a href="#internacional" data-toggle="tab">Internacionais</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="nacional">
                                            @if($dados_promocao->count() > 0)
                                                <div class="row">
                                                    <div class="box box-warning bg-gray">
                                                        <div class="box-header">
                                                            <h2>Promoção</h2>
                                                        </div>
                                                        <div class="box-body">
                                                            @foreach($dados_promocao as $dd)
                                                                @if($dd->getRelation('galeria') != null)
                                                                    @if($dd->getRelation('galeria')->getRelation('imagens') != null)
                                                                        @if($dd->getRelation('galeria')->getRelation('imagens')->count() > 0)
                                                                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                                                                <!-- Box Comment -->
                                                                                <div class="box box-widget bg-green-gradient">
                                                                                    <div class="box-body">
                                                                                        <img class="img-responsive pad"
                                                                                             src="{{ route('imagecache', ['lista-pacotes', $dd->getRelation('galeria')->getRelation('imagens')->sortByDesc('ordem')->sortByDesc('principal')->first()->imagem]) }}"
                                                                                             alt="{{ $dd->getRelation('galeria')->getRelation('imagens')->sortByDesc('ordem')->sortByDesc('principal')->first()->legenda }}">

                                                                                        <p>{{ $dd->chamada }}</p>
                                                                                    </div>
                                                                                    <!-- /.box-body -->
                                                                                    <div class="box-footer bg-green-active">
                                                                                        <a href="{{ route('usar-gmilhas.pacote.interna', $dd->id) }}"
                                                                                           class="btn btn-warning btn-block">Visualizar</a>
                                                                                    </div>
                                                                                    <!-- /.box-footer -->
                                                                                </div>
                                                                                <!-- /.box -->
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="row">
                                                @forelse($dados as $dd)
                                                    @if($dd->getRelation('galeria') != null)
                                                        @if($dd->getRelation('galeria')->getRelation('imagens') != null)
                                                            @if($dd->getRelation('galeria')->getRelation('imagens')->count() > 0)
                                                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                                                    <!-- Box Comment -->
                                                                    <div class="box box-widget bg-yellow-gradient">
                                                                        <div class="box-body">
                                                                            <img class="img-responsive pad"
                                                                                 src="{{ route('imagecache', ['lista-pacotes', $dd->getRelation('galeria')->getRelation('imagens')->sortByDesc('ordem')->sortByDesc('principal')->first()->imagem]) }}"
                                                                                 alt="{{ $dd->getRelation('galeria')->getRelation('imagens')->sortByDesc('ordem')->sortByDesc('principal')->first()->legenda }}">

                                                                            <p>{{ $dd->chamada }}</p>
                                                                        </div>
                                                                        <!-- /.box-body -->
                                                                        <div class="box-footer bg-yellow-active">
                                                                            <a href="{{ route('usar-gmilhas.pacote.interna', $dd->id) }}" class="btn btn-primary btn-block">Visualizar</a>
                                                                        </div>
                                                                        <!-- /.box-footer -->
                                                                    </div>
                                                                    <!-- /.box -->
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @empty
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            Não há pacotes disponíveis no momento
                                                        </div>
                                                    </div>
                                                @endforelse

                                            </div>
                                        </div>
                                        <!-- /.tab-pane -->
                                        <div class="tab-pane" id="internacional">
                                            @if($dados_promocao_internacional->count() > 0)
                                                <div class="row">
                                                    <div class="box box-warning bg-gray">
                                                        <div class="box-header">
                                                            <h2>Promoção</h2>
                                                        </div>
                                                        <div class="box-body">
                                                            @foreach($dados_promocao_internacional as $dd)
                                                                @if($dd->getRelation('galeria') != null)
                                                                    @if($dd->getRelation('galeria')->getRelation('imagens') != null)
                                                                        @if($dd->getRelation('galeria')->getRelation('imagens')->count() > 0)
                                                                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                                                                <!-- Box Comment -->
                                                                                <div class="box box-widget bg-green-gradient">
                                                                                    <div class="box-body">
                                                                                        <img class="img-responsive pad"
                                                                                             src="{{ route('imagecache', ['lista-pacotes', $dd->getRelation('galeria')->getRelation('imagens')->sortByDesc('ordem')->sortByDesc('principal')->first()->imagem]) }}"
                                                                                             alt="{{ $dd->getRelation('galeria')->getRelation('imagens')->sortByDesc('ordem')->sortByDesc('principal')->first()->legenda }}">

                                                                                        <p>{{ $dd->chamada }}</p>
                                                                                    </div>
                                                                                    <!-- /.box-body -->
                                                                                    <div class="box-footer bg-green-active">
                                                                                        <a href="{{ route('usar-gmilhas.pacote.interna', $dd->id) }}"
                                                                                           class="btn btn-warning btn-block">Visualizar</a>
                                                                                    </div>
                                                                                    <!-- /.box-footer -->
                                                                                </div>
                                                                                <!-- /.box -->
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="row">
                                                @forelse($dados_internacional as $dd)
                                                    @if($dd->getRelation('galeria') != null)
                                                        @if($dd->getRelation('galeria')->getRelation('imagens') != null)
                                                            @if($dd->getRelation('galeria')->getRelation('imagens')->count() > 0)
                                                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                                                    <!-- Box Comment -->
                                                                    <div class="box box-widget bg-yellow-gradient">
                                                                        <div class="box-body">
                                                                            <img class="img-responsive pad"
                                                                                 src="{{ route('imagecache', ['lista-pacotes', $dd->getRelation('galeria')->getRelation('imagens')->sortByDesc('ordem')->sortByDesc('principal')->first()->imagem]) }}"
                                                                                 alt="{{ $dd->getRelation('galeria')->getRelation('imagens')->sortByDesc('ordem')->sortByDesc('principal')->first()->legenda }}">

                                                                            <p>{{ $dd->chamada }}</p>
                                                                        </div>
                                                                        <!-- /.box-body -->
                                                                        <div class="box-footer bg-yellow-active">
                                                                            <a href="{{ route('usar-gmilhas.pacote.interna', $dd->id) }}" class="btn btn-primary btn-block">Visualizar</a>
                                                                        </div>
                                                                        <!-- /.box-footer -->
                                                                    </div>
                                                                    <!-- /.box -->
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @empty
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            Não há pacotes disponíveis no momento
                                                        </div>
                                                    </div>
                                                @endforelse

                                            </div>
                                        </div>
                                        <!-- /.tab-pane -->
                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                                <!-- nav-tabs-custom -->
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">

                    </div>
                </div>
                <!-- /.widget-user -->
            </div>
            <!-- /.col -->
        </div>
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
@endsection