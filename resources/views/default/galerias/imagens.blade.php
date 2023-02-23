@extends('default.layout.main')

@section('content')
    <section class="content">
        @include('default.errors.errors')

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cadastro de Imagens da Galeria: {{ $galeria->title }}<br>
                            <small class="text-danger">A legenda não é obrigatoria, todavia se a colocar a imagem sera renomeada com o conteúdo da legenda, afim de ajudar o SEO</small>
                        </h3>

                        <a href="javascript:;" data-url="{{ route('galeria.delete.all', $galeria->id) }}" id="deleteAll" class="btn btn-danger pull-right @if(count($galeria->imagens()->get()) == 0) hidden @endif">
                            <span class="glyphicon glyphicon-remove  text-default" aria-hidden="true"></span>
                            Apagar Todas
                        </a>

                        {{--@role('master')
                            <a href="{{ route('galeria.public', $galeria->id) }}"  class="btn btn-warning pull-right">
                                <span class="glyphicon glyphicon-remove  text-default" aria-hidden="true"></span>
                                Gerar imagens no public
                            </a>
                        @endrole--}}

                    </div><!-- /.box-header -->

                    <div class="row sortable" id="hall-imagens">
                        <div class="col-xs-6 col-sm-3 col-md-4 col-lg-2">
                            <input type="file" name="images[]" id="imagens" multiple>
                            <input type="hidden" name="url-order" value="{{ route('galeria.order') }}">
                            <a href="#" id="input-image" class="thumbnail" data-url="{{ route('galeria.upload', $galeria->id) }}">
                                <img src="{{ route('imagecache', ['thumb', 'button-plus.png']) }}">
                            </a>
                        </div>

                        @foreach($imagens as $imagem)
                            <div class="col-xs-6 col-sm-3 box-img col-md-4 col-lg-2" id="image-{{ $imagem->id }}">
                                <a href="#" class="thumbnail @if($imagem->principal == 1) thumbnail-principal @endif">
                                    <img src="{{ route('imagecache', ['thumb', $imagem->caminho . $imagem->name]) }}" >
                                </a>
                                <nav class="navbar navbar-imagens navbar-default">
                                    <div class="container-fluid">
                                        <div class="navbar-header">
                                            <input type="hidden" name="url-delete" value="{{ route('galeria.deleteImg', $imagem->id) }}">
                                            <input type="hidden" name="url-legenda" value="{{ route('galeria.legenda', $imagem->id) }}">
                                            <input type="hidden" name="url-principal" value="{{ route('galeria.imagem.principal', $imagem->id) }}">
                                            <a href="#" data-id="{{ $imagem->id }}" title="Principal" class="btn btn-default navbar-btn"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>
                                            <a href="#" data-id="{{ $imagem->id }}" data-legenda="{{ empty($imagem->legenda) ? 'Não há legenda!' : $imagem->legenda }}" title="Legenda" class="btn btn-default navbar-btn"><span class="glyphicon glyphicon-text-color text-blue" aria-hidden="true"></span></a>
                                            <a href="#" data-id="{{ $imagem->id }}" title="Apagar" class="btn btn-danger navbar-btn"><span class="glyphicon glyphicon-trash text-default" aria-hidden="true"></span></a>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        @endforeach

                    </div>

                    @if(isset($_GET['back']))
                        <a href="{{ route($_GET['back'].'.index', isset($_GET['ids']) ? $_GET['ids'] : []) }}" class="btn btn-warning btn-block">
                            <span class="fa fa-mail-reply  text-default" aria-hidden="true"></span>
                            Voltar para {{ $_GET['caption'] }}
                        </a>
                    @endif

                    <a href="{{ route('galeria.index') }}" class="btn btn-primary btn-block">
                        <span class="fa fa-mail-reply  text-default" aria-hidden="true"></span>
                        Voltar para lista
                    </a>

                    <br>

                </div><!-- /.box -->
            </div><!--/.col (left) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->

@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('css/imagens/imagens-acoes.css') }}">
    <link rel="stylesheet" href="{{ asset('css/imagens/imagens.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ asset('css/imagens/jquery-ui.min.css') }}">
@endsection

@section('script')
    <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/imagens/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/imagens/filereader.js') }}"></script>
    <script src="{{ asset('js/imagens/upload-imagens.js') }}"></script>
    <script src="{{ asset('js/imagens/acoes-imagens.js') }}"></script>
@endsection