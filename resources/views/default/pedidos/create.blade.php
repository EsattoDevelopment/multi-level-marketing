@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Portfólio {{ env('COMPANY_NAME', 'Nome empresa') }}
        </h1>
        <small>Escolha o plano que melhor se adequa ao seu perfil.</small>
        <h2>Saldo: {{ Auth::user()->ultimoMovimento() ? mascaraMoeda($sistema->moeda, Auth::user()->ultimoMovimento()->saldo, 2, true) : 0 }}</h2>
        <ol class="breadcrumb hidden-xs">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Portfólio {{ env('COMPANY_NAME', 'Nome empresa') }}</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div id="formulario" class="box-body">
            @foreach($itens->chunk(2) as $itenss)
                <div class="row">
                    @foreach($itenss as $item)
                        <div class="col-lg-6 col-sm-12 col-md-6 col-xs-12">
                            <div class="text-center box bg-gray-light" {!! ($item->cor_item ? 'style="border-top: 3px solid '.$item->cor_item.';"' : '') !!}>
                                @if ($item->imagem)
                                    <figure class="image">
                                        <img class="img-rounded img" src="{{ route('imagecache', ['pacotes', 'itens/'. $item->id . '/' .  $item->imagem]) }}" alt="{{ $item->name }}" style="max-width: 100%;">
                                    </figure>
                                @endif
                                <h3 {!! ($item->cor_item ? 'style="color: '.$item->cor_item.';"' : '') !!}>{{ $item->name }}</h3>
                                <h4>Valor: {{ mascaraMoeda($sistema->moeda, $item->valor, 2, true) }}</h4>
                                <br>
                                <br>
                                {!! $item->descricao !!}

                                <hr style="margin: 5px 0;">

                                {{--@if(Auth::user()->ultimoMovimento() ? Auth::user()->ultimoMovimento()->saldo : 0 >= $item->valor)--}}
                                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-item-{{ $item->id }}" {!! ($item->cor_item ? 'style="background-color: '.$item->cor_item.'; border-color: '.$item->cor_item.'; color: #FFF;"' : '') !!}>
                                    Contratar
                                </button>
                                {{--            @else
                                                <span class="label label-default bg-black">Saldo Insuficiente</span><br><br>
                                                    <a href="{{ route('deposito.depositar') }}" class="btn btn-success btn-sm text-black text-bold"><i class="fa fa-plus"></i> Adicionar crédito</a>
                                            @endif--}}
                                <br><br>
                            </div>
                        </div>

                        <div class="modal fade" id="modal-item-{{ $item->id }}" data-id="{{ $item->id }}" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span></button>
                                        <h4 class="modal-title">Leia antes de confirmar</h4>
                                    </div>
                                    <form action="{{ route('portfolio.contratar') }}" id="form-pedido-{{ $item->id }}" class="form-contratacao"  method="post">
                                        <input type="hidden" name="code">
                                        <div class="modal-body">
                                            @if($item->descricao2)
                                                {!! $item->descricao2 !!}
                                                <hr style="border: 1px solid #000">
                                            @endif
                                                <strong>Parabéns</strong> <br>
                                            <p>Você está adquirindo: <strong>{{ $item->name }}</strong> no valor de <strong>{{ mascaraMoeda($sistema->moeda, $item->valor, 2, true) }}</strong>, assina-le o campo abaixo para concordar e clique no botão "CONFIRMAR" para continuar.</p>

                                            <input type="checkbox" name="aceite" @click="aceite = !aceite" v-model="aceite_{{ $item->id }}"> <strong>Li as informações e estou de acordo</strong>
                                            @if($item->habilita_recontratacao_automatica)
                                                <div class="form-group">
                                                    <label>O que fazer na finalização do contrato ?</label>
                                                    <br>
                                                    <label>Ao final do contrato:</label>
                                                    <br>
                                                    <div class="form-group">
                                                        <label>
                                                            <input type="radio" value="0" name="modo_recontratacao_automatica_{{ $item->id }}" class="flat-red" {{ old("modo_recontratacao_automatica_$item->id", $item->modo_recontratacao_automatica)  == 0 ? 'checked' : '' }}>
                                                            {{ array_search(0, config('constants.modo_recontratacao_automatica_exibicao')) }}.
                                                        </label>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>
                                                            <input type="radio" value="1" name="modo_recontratacao_automatica_{{ $item->id }}" class="flat-red" {{ old("modo_recontratacao_automatica_$item->id", $item->modo_recontratacao_automatica)  == 1 ? 'checked' : '' }}>
                                                            {{ array_search(1, config('constants.modo_recontratacao_automatica_exibicao')) }}. {{ $sistema->moeda }}{{ "{{ valor_item_$item->id" . " | price}"."}" }}
                                                        </label>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>
                                                            <input type="radio" value="2" name="modo_recontratacao_automatica_{{ $item->id }}" class="flat-red" {{ old("modo_recontratacao_automatica_$item->id", $item->modo_recontratacao_automatica)  == 2 ? 'checked' : '' }}>
                                                            {{ array_search(2, config('constants.modo_recontratacao_automatica_exibicao')) }}. {{ $sistema->moeda }}{{ "{{ valor_item_$item->id" . "_calc | price}"."}" }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                                            {{ csrf_field() }}
                                            <input type="hidden" name="item" value="{{ $item->id }}">
                                            <input type="hidden" name="qtd_itens" value="1">
                                            <input type="hidden" name="modo_recontratacao_automatica_original" value="{{ $item->modo_recontratacao_automatica }}">
                                            <button type="submit" :disabled="!aceite_{{ $item->id }}" class="btn btn-primary">Confirmar</button>

                                        </div>
                                    </form>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert/sweetalert.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/square/red.css') }}">
    <style>
        .small-box-body, .check-item{
            background-color: #FFF;
            color: #000;
        }

        .modal-body{
            max-height: 400px;
            overflow: auto;
        }

        @media (min-width: 768px) {
            .modal-body{
                max-height: 200px;
            }
        }
    </style>

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
@endsection

@section('script')
    <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>

    <script>
        var app = new Vue({
                el: "#formulario",
                data: {
                    @forelse($itens as $key => $item)
                    aceite_{{ $item->id }}: false,
                    @empty
                    @endforelse
                }
            }
        );
    </script>

    <script>
        $(function () {
            $("div[id*='modal-item-']").on('show.bs.modal', function (e) {
                var id = $(this).data('id');

                @if($sistema->habilita_autenticacao_contratacao && (Auth::user()->google2fa_secret == null || strlen(Auth::user()->google2fa_secret) == 0))
                swal({
                    title: "Atenção",
                    text: 'Esta operação necessita que a verificação de 2 fatores esteja ativa',
                    type: "info",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true,
                    confirmButtonText: "Ativar agora",
                    cancelButtonText: "Cancelar",
                }, function () {
                    window.location = "{{ route('dados-usuario.seguranca') }}";
                });

                this.modal('hide');
                return false;

                @endif

            });


/*            $('input').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red',
                increaseArea: '20%' // optional
            });*/

            $('.modal').on('hidden.bs.modal', function () {

                formulario = $(this);

                itemId = formulario.find('input[name="item"]').val();
                modoRecontratacaoAutomaticaOriginal = formulario.find('input[name="modo_recontratacao_automatica_original"]').val();
                $('input').filter('[name="modo_recontratacao_automatica_' + itemId + '"]').filter('[value="' + modoRecontratacaoAutomaticaOriginal + '"]').iCheck('check');
            });

            $('.form-contratacao').submit(function(event){
                event.preventDefault();

                formulario = $(this);
                itemId = formulario.find('input[name="item"]').val();

                @if($sistema->habilita_autenticacao_contratacao)
                swal({
                    title: "Atenção!",
                    text: "Informe o código gerado no aplicativo<br/> <b>Google Authenticator</b>.",
                    type: "input",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true,
                    inputPlaceholder: "",
                    confirmButtonText: "Validar",
                    cancelButtonText: "Cancelar",
                    html: true,
                }, function (inputValue) {
                    if (inputValue === false) return false;
                    if (inputValue === "") {
                        swal.showInputError("Você precisa informar o código para continuar.");
                        return false
                    }

                    $.ajax({
                        url: "{{ route('contratacao.validate.2fa') }}",
                        type: 'POST',
                        data: {code: inputValue},
                        success: function(){
                            $("input[name='code']").val(inputValue);
                            formulario.unbind('submit').submit();
                            swal("Enviando...", "Modo de recontratação automática atualizado com sucesso!", "success");
                        },
                        error: function(){
                            swal.showInputError("Código inválido, tente novamente.");
                            return false;
                        }
                    });
                });
                @else
                formulario.unbind('submit').submit();
                swal("Enviando...", "Modo de recontratação automática atualizado com sucesso!", "success");
                @endif
            });
        });
    </script>
@endsection
