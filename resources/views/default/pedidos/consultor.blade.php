@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Credenciamento {{ env('COMPANY_NAME', 'Nome empresa') }}
        </h1>
        <small>Escolha o credenciamento que melhor se adequa ao seu perfil.</small>
        <h2>Saldo: {{ Auth::user()->ultimoMovimento() ? mascaraMoeda($sistema->moeda, Auth::user()->ultimoMovimento()->saldo, 2, true) : 0 }}</h2>
        <ol class="breadcrumb hidden-xs">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Seja Agente Credenciado</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div id="formulario" class="box-body">
            @foreach($itens as $item)
                <div class="col-lg-4 col-sm-12 col-md-6 col-xs-12">
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
                            <button type="button" :disabled="input_item_{{ $item->id }}" class="btn btn-default" data-toggle="modal" data-target="#modal-item-{{ $item->id }}" {!! ($item->cor_item ? 'style="background-color: '.$item->cor_item.'; border-color: '.$item->cor_item.'; color: #FFF;"' : '') !!}>
                                Contratar
                            </button>
            {{--            @else
                            <span class="label label-default bg-black">Saldo Insuficiente</span><br><br>
                                <a href="{{ route('deposito.depositar') }}" class="btn btn-success btn-sm text-black text-bold"><i class="fa fa-plus"></i> Adicionar cr??dito</a>
                        @endif--}}
                        <br><br>
                    </div>
                </div>

                <div class="modal fade" id="modal-item-{{ $item->id }}" data-id="{{ $item->id }}" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">??</span></button>
                                <h4 class="modal-title">Leia antes de confirmar</h4>
                            </div>
                            <form action="{{ route('portfolio.contratar') }}" id="form-pedido-{{ $item->id }}" class="form-contratacao"  method="post">
                                <input type="hidden" name="code">
                                <div class="modal-body">
                                    @if($item->descricao2)
                                        {!! $item->descricao2 !!}
                                        <hr style="border: 1px solid #000">
                                    @endif
                                    <strong>Parab??ns</strong> <br>
                                    <p>Voc?? est?? adquirindo: <strong>{{ $item->name }}</strong> no valor de <strong>{{ mascaraMoeda($sistema->moeda, $item->valor, 2, true) }}</strong>, assina-le o campo abaixo para concordar e clique no bot??o "CONFIRMAR" para continuar.</p>
                                    <input type="checkbox" name="aceite" @click="aceite = !aceite" v-model="aceite_{{ $item->id }}"> Li as informa????es e estou de acordo
                                    @if($item->habilita_recontratacao_automatica)
                                        <div class="form-group">
                                            <label>O que fazer na finaliza????o do contrato ?</label>
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
                //var valor = $("#valor_item_" + id);

                @if($sistema->habilita_autenticacao_contratacao && (Auth::user()->google2fa_secret == null || strlen(Auth::user()->google2fa_secret) == 0))
                swal({
                    title: "Aten????o",
                    text: 'Esta opera????o necessita que a verifica????o de 2 fatores esteja ativa',
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

                @else

                // if (valor.val() == "" || (parseInt(valor.val()) < parseInt(valor.attr('min')) || parseInt(valor.val()) > parseInt(valor.attr('max')))) {
                //
                //     swal({
                //         title: "",
                //         text: "O <b>valor</b> deve respeitar a <b>Faixa de Dep??sito</b>!",
                //         type: "warning",
                //         html: true,
                //     });
                //
                //     this.modal('hide');
                //     return false;
                // }


                @endif

                //$("#modal-item-" + id).find("input[name='qtd_itens']").val(valor.val());

            });


            /*$('input').iCheck({
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
                    title: "Aten????o!",
                    text: "Informe o c??digo gerado no aplicativo<br/> <b>Google Authenticator</b>.",
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
                        swal.showInputError("Voc?? precisa informar o c??digo para continuar.");
                        return false
                    }

                    $.ajax({
                        url: "{{ route('contratacao.validate.2fa') }}",
                        type: 'POST',
                        data: {code: inputValue},
                        success: function(){
                            $("input[name='code']").val(inputValue);
                            formulario.unbind('submit').submit();
                            swal("Enviando...", "Modo de recontrata????o autom??tica atualizado com sucesso!", "success");
                        },
                        error: function(){
                            swal.showInputError("C??digo inv??lido, tente novamente.");
                            return false;
                        }
                    });
                });
                @else
                formulario.unbind('submit').submit();
                swal("Enviando...", "Modo de recontrata????o autom??tica atualizado com sucesso!", "success");
                @endif
            });
        });

        /*
        var app = new Vue({
            el: "#formulario",
            data: {
                saldo: {{ Auth::user()->ultimoMovimento() ? Auth::user()->ultimoMovimento()->saldo : 0 }},
                @forelse($itens as $item)
                valor_item_{{ $item->id }}: null,
                valor_item_{{ $item->id }}_calc: 0,
                valor_item_{{ $item->id }}_calc2: 0,
                valor_item_{{ $item->id }}_mensal: 0,
                input_item_{{ $item->id }}: false,
                @empty
                @endforelse
            },
            watch: {
                @forelse($itens as $item)
                valor_item_{{ $item->id }}: function(val){
                    val = val == "" || val < 0 ? 0 : val;
                    mensal = {{ $item->potencial_mensal_teto }} / 100;
                    cap = val * ({{ $item->meses }} * mensal);
                    this.valor_item_{{ $item->id }}_calc = parseFloat(val) + cap;
                    this.valor_item_{{ $item->id }}_calc2 = cap;
                    this.valor_item_{{ $item->id }}_mensal = val * mensal;
                    this.input_item_{{ $item->id }} = val > this.saldo;
                },
                @empty
                @endforelse
            },
            filters: {
                price: function (value) {
                    let val = (value/1).toFixed(2).replace('.', ',')
                    return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                }
            }
        });
        */
    </script>
@endsection
