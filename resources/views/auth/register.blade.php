@extends('auth.layout')

@section('title')
    <title>Novo registro | {{ $empresa->nome_fantasia }}</title>
@endsection

@section('content')
    <div class="register-box">
        <div class="register-box-body">
            <div class="register-logo">
                <a href="@if($empresa->site) {{ $empresa->site }} @else javascript:; @endif">
                    <img style="max-width: 100%"
                         src="@if(strlen(trim($empresa->logo)) > 0){{ route('imagecache', ['logo', 'empresa/'.$empresa->logo]) }} @else {{ route('imagecache', ['logo', 'logo-aqui.jpg']) }} @endif"
                         alt="Logo">
                </a>
            </div>
            @include('default.errors.errors')

            <form action="{{ route('auth.register') }}" method="post">
                <div id="aviso">
                    <small id="nao-encontrado" class="pull-left text-red hidden">Não encontrado</small>
                    @if($sistema->habilita_registro_usuario_troca_indicador)
                        <small id="trocar-indicador" style="cursor: pointer;"
                               class="pull-right text-white @if(!old('indicadorID', isset($indicador)? true : false)) hidden @endif">
                            Trocar
                        </small>
                    @endif
                </div>
                <span class="clearfix"></span>
                <div class="form-group has-feedback input-group">
                    <input type="text" required @if(old('indicadorID', isset($indicador)? true : false)) readonly @endif name="indicador"
                           value="{{ old('indicador', isset($indicador)? $indicador->name : false) }}" class="form-control"
                           placeholder="Conta do Agente">
                    <span class="icon-mastermdr-conta form-control-feedback" style="z-index: 3;"></span>
                    <span class="input-group-btn">
                        <button id="buscar-indicador" @if(old('indicadorID', isset($indicador)? true : false)) disabled="disabled" @endif class="btn btn-default" type="button">
                        <span id="icon-indicador"
                              class="glyphicon glyphicon-search"><img
                                    class="hidden" style="max-width:17px;" src="{{asset('images/loading.gif')}}">
                        </span>
                        </button>
                    </span>
                </div>
                <div class="form-group has-feedback" @if($sistema->habilita_estrangeiro)style="margin-bottom: 0;"@endif>
                    <input type="text" required name="name" value="{{ old('name') }}" class="form-control"
                           placeholder="Seu nome completo">
                    <span class="icon-mastermdr-username form-control-feedback"></span>
                </div>
                @if($sistema->habilita_estrangeiro)
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="col-xs-12">
                                <div class="checkbox">
                                    <label style="text-align: justify">
                                        <input type="checkbox" v-model="estrangeiro" name="estrangeiro" value="1" @if(old('estrangeiro')) checked @endif> Sou estrangeiro
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                {{--            <div class="form-group has-feedback">--}}
                {{--                <input type="text" required name="username" value="{{ old('username') }}" class="form-control"--}}
                {{--                       placeholder="usuário">--}}
                {{--                <span class="icon-mastermdr-user form-control-feedback"></span>--}}
                {{--            </div>--}}
                {{-- <div class="form-group has-feedback">
                    <input type="text" name="codigo" value="{{ old('codigo') }}" class="form-control"
                           placeholder="número do contrato">
                    <span class="icon-mastermdr-contract form-control-feedback"></span>
                </div> --}}
                @if($sistema->campo_cpf)
                    <div class="form-group has-feedback" v-if="!estrangeiro">
                        <input type="text" required name="cpf" value="{{ old('cpf') }}" class="form-control" placeholder="CPF/CNPJ">
                        <span class="icon-mastermdr-cpf form-control-feedback"></span>
                    </div>
                @endif

                @if($sistema->campo_rg)
                    <div class="form-group has-feedback" v-if="!estrangeiro">
                        <input type="text" name="rg" value="{{ old('rg') }}" class="form-control" placeholder="RG">
                        <span class="icon-mastermdr-rg form-control-feedback"></span>
                    </div>
                @endif

                @if($sistema->campo_dtnasc)
                    <div class="form-group has-feedback">
                        <input type="text" name="data_nasc" value="{{ old('data_nasc') }}"
                               class="form-control datepicker" placeholder="Data de nascimento">
                        <span class="icon-mastermdr-date form-control-feedback"></span>
                    </div>
                @endif

                <div class="form-group has-feedback">
                    <input type="email" required name="email" value="{{ old('email') }}" class="form-control"
                           placeholder="E-mail">
                    <span class="icon-mastermdr-email form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" required name="password" class="form-control" placeholder="Senha (no minimo 6 caracteres)">
                    <span class="icon-mastermdr-pass form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback" style="margin-bottom: 0;">
                    <input type="password" required name="password_confirmation" class="form-control"
                           placeholder="Confirme a senha">
                    <span class="icon-mastermdr-pass form-control-feedback"></span>
                </div>
                @if($empresa->termo_inicial)
                    <div class="col-xs-12">
                        <div class="checkbox">
                            <label style="text-align: justify">
                                <input required name="termo" value="1" type="checkbox"> Li e aceito todas as cláusulas dos contratos de <a href="{{ route('termo.download') }}" target="_blank" class="esqueci-mastermdr" style="font-size: 15px; text-decoration: underline;">"{{ $empresa->nome_termo_inicial }}"</a>.
                            </label>
                        </div>
                    </div>
                @endif
                <div class="row" style="margin-top: 16px">
                    <input type="hidden" name="indicadorID"
                           value="{{ old('indicadorID', isset($indicador)? $indicador->id : '') }}">
                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                    <div class="col-xs-12" style="margin-bottom: 5px;">
                        <button type="submit" class="btn btn-mastermdr btn-mastermdr-cadastro btn-block btn-flat">registrar</button>
                    </div>
                    @if($sistema->habilita_registro_usuario_sem_indicacao)
                        <div class="col-xs-12 txt-alg-center">
                            <a  href="{{ route('auth.login') }}" class="esqueci-mastermdr">voltar</a>
                        </div>
                @endif
                <!-- /.col -->
                </div>
            </form>

        </div>
        <!-- /.form-box -->
    </div>
@endsection

@section('script')
    <!-- InputMask -->
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js"></script>
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.js?v=50')}}"></script>
    <script src="{{asset('js/CPF.js')}}"></script>

    <script>
        @if($sistema->habilita_estrangeiro)
        new Vue({
            el: ".register-box",
            data: {
                estrangeiro: {{ old('estrangeiro') ? 'true' : 'false' }},
            }
        });
        @endif

        $(function () {
            // $('input').iCheck({
            //     checkboxClass: 'icheckbox_square-yellow',
            //     radioClass: 'iradio_square-yellow',
            //     increaseArea: '20%' // optional
            // });

            $("input[name='cpf']").inputmask({
                mask: ['999.999.999-99', '99.999.999/9999-99'],
                showTooltip: true,
                showMaskOnHover: true,
                clearIncomplete: true
            });

            $("input[name='data_nasc']").inputmask({
                mask: '99/99/9999',
                showTooltip: true,
                showMaskOnHover: true,
                clearIncomplete: true
            });

            @if(old('indicador', isset($indicador)) && !$errors->has('indicadorID')) $("input[name='indicador']").val('{{ isset($indicador) ? $indicador->name : old('indicador') }}'); @endif

            $("#trocar-indicador").click(function () {
                $('input[name="indicador"]').val('');
                $('input[name="indicadorID"]').val('');
                $('input[name="indicador"]').attr('readonly', false);
                $('#buscar-indicador').attr('disabled', false);

                if (!$("#trocar-indicador").hasClass('hidden'))
                    $("#trocar-indicador").addClass('hidden');

                $('#icon-indicador').removeClass('glyphicon-check');
                $('#icon-indicador').addClass('glyphicon-search');

                if ($('input[name="indicador"]').parent().hasClass('has-success')) {
                    $('input[name="indicador"]').parent().removeClass('has-success');
                }

               /* $("input[name='indicador']").inputmask({
                    mask: ['999.999.999-99', '99.999.999/9999-99'],
                    showTooltip: true,
                    showMaskOnHover: true,
                    clearIncomplete: true
                });*/

                //retira aviso não encontrado
                if (!$("#nao-encontrado").hasClass('hidden'))
                    $("#nao-encontrado").addClass('hidden');
            });

            //TODO busca patrocinador
            $('#buscar-indicador').click(function () {
                if($('input[name="indicadorID"]').val() == '') {
                    buscaIndicador();
                }
            });

            $('input[name="indicador"]').focusout(function () {
                if($('input[name="indicadorID"]').val() == '') {
                    buscaIndicador();
                }
            });

            function buscaIndicador() {
                var indicador = $('input[name="indicador"]').val();
                $('#icon-indicador img').removeClass('hidden');
                $('#icon-indicador').removeClass('glyphicon-search');

                busca = $.post('{{ route('user.indicador') }}', {indicador: indicador});

                busca.done(function (data) {
                    data = $.parseJSON(data);
                    /*$('input[name="indicador"]').inputmask('remove');*/
                    $('input[name="indicador"]').val(data.nome);
                    $('input[name="indicadorID"]').val(data.indicador);
                    $('input[name="indicador"]').attr('readonly', true);
                    $('#buscar-indicador').attr('disabled', 'disabled');

                    if ($("#trocar-indicador").hasClass('hidden'))
                        $("#trocar-indicador").removeClass('hidden');

                    $('#icon-indicador img').addClass('hidden');
                    $('#icon-indicador').addClass('glyphicon-check');

                    //retira sinalização não encontrado
                    if ($('input[name="indicador"]').parent().hasClass('has-error')) {
                        $('input[name="indicador"]').parent().removeClass('has-error');
                        $('input[name="indicador"]').parent().addClass('has-success');
                    }

                    if (!$('input[name="indicador"]').parent().hasClass('has-success')) {
                        $('input[name="indicador"]').parent().addClass('has-success');
                    }

                    //retira aviso não encontrado
                    if (!$("#nao-encontrado").hasClass('hidden'))
                        $("#nao-encontrado").addClass('hidden');

                });

                busca.fail(function (data) {

                    //aviso não encontrado
                    if ($("#nao-encontrado").hasClass('hidden'))
                        $("#nao-encontrado").removeClass('hidden');

                    //sinaliza não encontrado
                    if (!$('input[name="indicador"]').parent().hasClass('has-error')) {
                        $('input[name="indicador"]').parent().addClass('has-error');
                        $('input[name="indicador"]').parent().removeClass('has-success');
                    }

                    //loading
                    $('#icon-indicador img').addClass('hidden');
                    $('#icon-indicador').addClass('glyphicon-search');
                });
            }

            verificaCPF();

            function verificaCPF() {
                var cpfInput = $("input[name='cpf']");
                var cpf = cpfInput.val();
                if (cpf.length == 14) {
                    if (CPF.validate(cpf) === false) {
                        cpfInput.parent().addClass('has-error');

                        if (!$("#data_nasc").hasClass('hidden')) {
                            $("#data_nasc").hasClass('hidden');
                        }
                    } else {
                        if (cpfInput.parent().hasClass('has-error')) {
                            cpfInput.parent().removeClass('has-error');
                        }

                        if ($("#data_nasc").hasClass('hidden')) {
                            $("#data_nasc").removeClass('hidden');
                        }
                    }
                } else if (cpf.length > 14) {
                    if (cpfInput.parent().hasClass('has-error')) {
                        cpfInput.parent().removeClass('has-error');
                    }
                    $("#data_nasc").addClass('hidden');
                }
            }

            $("input[name='cpf']").focusout(function () {
                verificaCPF();
            });

        });
    </script>
@endsection
