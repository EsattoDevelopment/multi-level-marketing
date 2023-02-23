@extends('default.layout.main')

@section('content')
    <section class="content-header">
        <h1>
            Cadastro de vídeo
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('videos.index') }}"><i class="fa fa-play-circle"></i> Vídeos</a></li>
            <li class="active"><i class="fa fa-play"></i> Cadastro</li>
        </ol>
    </section>
    <section class="content">

        @include('default.errors.errors')

        <div class="row">
            <div class="col-md-12">
                <!-- form start -->
                <form role="form" action="{{ route('videos.store') }}" method="post" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Vídeo</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="form-group col-xs-6 col-lg-6">
                                <div class="form-group">
                                    <label>Nome</label>
                                    <input type="text" name="nome" tabIndex="1" class="form-control" placeholder="Digite aqui um nome para o vídeo ..." value="{{old('nome')}}" required>
                                </div>
                                <div class="form-group">
                                    <label>Vídeo</label>
                                    <div class="form-group input-group">
                                        <div class="input-group-btn">
                                            <button type="button" tabIndex="-1" class="btn btn-default" id="div_tipo_video">URL</button>
                                        </div>
                                        <input type="hidden" name="tipo" id="tipo">
                                        <input type="text" tabIndex="2" class="form-control" placeholder="Cole aqui a url do vídeo ..." name="codigo" id="codigo" onfocusout="verificarTipo()" value="{{old('codigo')}}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="categoria">Categorias</label>
                                    <select name="categoria" tabIndex="3" class="form-control select2 select2-hidden-accessible" style="width: 100%;" data-select2-id="9" tabindex="-1" aria-hidden="true">
                                        @foreach(config('constants.videos_categorias') as $categoriaId => $categoria)
                                            <option value="{{$categoriaId}}" {{ old('categoria', 1) == $categoriaId ? 'selected="selected"' : '' }} data-select2-id="{{$categoriaId}}">{{$categoria}}</option>
                                        @endforeach
                                       {{-- <option value="1" selected="selected" data-select2-id="1">Rendimento diario</option>
                                        @foreach($categorias as $categoria => $categoriaId)
                                            <option value="{{$categoriaId}}" data-select2-id="{{$categoriaId}}">{{$categoria}}</option>
                                        @endforeach--}}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="descricao">Descrição</label>
                                    <textarea name="descricao" tabIndex="4" class="form-control" rows="3" placeholder="Digite aqui uma descrição para o vídeo ...">{{old('descricao')}}</textarea>
                                </div>
                                <div class="form-group col-xs-6 col-lg-6">
                                    <label for="status">Ativo</label> <br>
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="iradio_flat-red">
                                            <input type="radio" value="1" name="status" checked="true">
                                            Sim
                                        </label>
                                        <label class="iradio_flat-red">
                                            <input type="radio" value="0" name="status">
                                            Não
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div id="player" class="form-group col-xs-6 col-lg-6">

                            </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Permissão de visualização</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            {{--Pagamento de rentabilidade--}}
                            <div class="form-group col-xs-12 col-lg-12">
                                @foreach($titulos as $titulo)
                                    <div class="form-group col-xs-12 col-lg-3 text-center">
                                        <div class="text-center">
                                            {{$titulo->name}}
                                        </div>
                                        <div class="text-center">
                                            <input type="checkbox" @if(old("titulosPermissoes[{$titulo->id}][exibir]") == 1) checked @endif  value="1" name="titulosPermissoes[{{$titulo->id}}][exibir]" tabIndex="5" class="icheckbox_flat-red" >
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-group col-xs-12 col-lg-12">
                                <button type="button" tabIndex="6" class="btn btn-mastermdr" onclick="selecionarTodos()">Todos</button>
                                <button type="button" tabIndex="7" class="btn btn-mastermdr" onclick="selecionarInverter()">Inverter</button>
                            </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                    <div class="box box-warning">
                        <div class="box-footer">
                            <button type="submit" tabIndex="8" class="btn btn-primary">Salvar</button>
                            <a href="{{ route('videos.index') }}" tabIndex="9" class="btn btn-default pull-right">Voltar</a>
                        </div>
                    </div>
                </form>
            </div><!--/.col (left) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
@endsection

@section('script')


    //Api YouTube
    <script>
        var done = false;
        var player;
        var tag;
        var firstScriptTag;

        function youtubeInicio() {
            // 2. This code loads the IFrame Player API code asynchronously.
            tag = document.createElement('script');
            tag.src = "https://www.youtube.com/iframe_api";
            firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

            // 3. This function creates an <iframe> (and YouTube player)
            //    after the API code downloads.
            done = false;
        }

        function onYouTubeIframeAPIReady() {
            player = new YT.Player('player', {
                height: 'auto',
                width: '100%',
                videoId: getVideoCode(),
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                }
            });
        }

        // 4. The API will call this function when the video player is ready.
        function onPlayerReady(event) {
            event.target.playVideo();
        }

        // 5. The API calls this function when the player's state changes.
        //    The function indicates that when playing a video (state=1),
        //    the player should play for six seconds and then stop.
        //var done = false;
        function onPlayerStateChange(event) {
            if (event.data == YT.PlayerState.PLAYING && !done) {
                setTimeout(stopVideo, 10000);
                done = true;
            }
        }
        function stopVideo() {
            player.stopVideo();
        }
    </script>

    <script src="https://player.vimeo.com/api/player.js"></script>

    <script type="text/javascript">
        function verificarTipo() {
            tipoVideo = window.document.getElementById('div_tipo_video');
            codigo = window.document.getElementById('codigo');
            tipo = window.document.getElementById('tipo');
            htmlVisualizador = '';
            if(tipoVideo != null) {
                tipoVideo.classList.remove("btn-google");
                tipoVideo.classList.remove("btn-vimeo");
                tipoVideo.classList.remove("btn-default");


                if ((/youtube|youtu/gi).test(codigo.value)) {
                    tipoVideo.innerText = 'YouTube';
                    tipoVideo.classList.add('btn-google');
                    tipo.value = 1;
                    youtubeInicio();
                    player.loadVideoById(getVideoCode());
                } else if ((/vimeo/gi).test(codigo.value)) {
                    tipoVideo.innerText = 'Vimeo';
                    tipoVideo.classList.add('btn-vimeo');
                    tipo.value = 2;
                    htmlVisualizador = '<iframe src="https://player.vimeo.com/video/' + getVideoCode() + '" width="100%" height="auto" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>'
                    window.document.getElementById('player').innerHTML = htmlVisualizador;
                }
                else {
                    tipoVideo.innerText = 'URL';
                    tipoVideo.classList.add('btn-default');
                    tipo.value = 0;
                    htmlVisualizador = '<video width="100%" height="auto"> <source src="' + codigo.value + '" type="video/mp4"> </video>';
                    window.document.getElementById('player').innerHTML = htmlVisualizador;
                }
            }
        };

        function getVideoCode(){
            codigo = window.document.getElementById('codigo');

            retorno = '';

            if((/^.*?v=(.*)$/gi).test(codigo.value)){
                retorno = (/^.*?v=(.*)$/gi).exec(codigo.value)[1];
            }else if((/^.*[/](.*)$/gi).test(codigo.value)){
                retorno = (/^.*[/](.*)$/gi).exec(codigo.value)[1];
            }

            return retorno;
        }


    </script>

    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script src="/plugins/iCheck/icheck.min.js"></script>
    <link rel="stylesheet" href="/plugins/iCheck/square/red.css">

    <script>
        function atualizarClasseCheckbox() {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red',
                increaseArea: '20%' // optional
            });
        }

        $(function () {
            atualizarClasseCheckbox();
            verificarTipo();
        });
    </script>

    <script type="text/javascript">
        var selecionarTodasStatus = false;
        function selecionarTodos(){
            permissoes = $('input[type="checkbox"]');
            selecionarTodasStatus = !selecionarTodasStatus;
            for(marcador = 0;marcador < permissoes.length; marcador++){
                permissoes[marcador].checked = selecionarTodasStatus;
            }
            atualizarClasseCheckbox();
        }
        function selecionarInverter(){
            permissoes = $('input[type="checkbox"]');
            for(marcador = 0;marcador < permissoes.length; marcador++){
                permissoes[marcador].checked = !permissoes[marcador].checked;
            }
            atualizarClasseCheckbox();
        }
    </script>
@endsection
