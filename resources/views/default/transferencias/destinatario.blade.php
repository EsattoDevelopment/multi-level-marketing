@extends('default.layout.main')

@section('content')
    <section class="content-header">
        <h1>
            Transferência entre contas {{ ucfirst(env('COMPANY_NAME_SHORT', 'empresa')) }}
        </h1>
        <ol class="breadcrumb hidden-xs">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Transferências</li>
            <li class="active">Nova</li>
        </ol>
    </section>

    <section class="content" id="content">
        @include('default.errors.errors')

        <div class="col-lg-6" style="float: none; margin: 0 auto;">
            <form action="{{ route('transferencia.destinatario') }}" method="get" id="transferencia">
                {{ csrf_field() }}
                <div class="box box-solid">
                    <div class="box-body" style="text-align: center;">
                        <h2>Dados destinatário</h2>
                        <section style="overflow: hidden;" id="destinatario">
                            <div class="form-group pull-left col-sm-12 col-xs-12 col-lg-6">
                                <label for="agencia">Agência</label> <br>
                                <input v-mask="['####']" type="text" required name="agencia" v-model="agencia" placeholder="Agência" value="{{ old('agencia') }}">
                            </div>
                            <div class="form-group pull-left col-sm-12 col-xs-12 col-lg-6">
                                <label for="conta">Conta com dígito</label> <br>
                                <input v-mask="['###-#', '####-#', '#####-#', '######-#', '#######-#', '########-#', '#########-#', '##########-#', '##########-#']" type="text" required name="conta" v-model="conta" placeholder="Conta" value="{{ old('conta') }}">
                            </div>
                        </section>
                        <button type="submit" class="btn btn-primary btn-block">Avançar</button>
                        {{--<br>
                        <small>Tarifa: transferências gratuitas entre contas {{ mb_strtolower(env('COMPANY_NAME_SHORT', 'empresa')) }}.</small>--}}
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('style')
    <style>
        #transferencia h2{margin-top: 5px;}
        #transferencia input{ text-align: center; background: none; border: none; padding-bottom: 5px; border-bottom: 1px solid #000; font-size: 30px; font-family: 'Source Sans Pro',sans-serif; margin: 10px 0px 20px 0; max-width: 230px; outline: none; }
        #destinatario input{ max-width: 186px; }
    </style>
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert/sweetalert.css') }}">
@endsection

@section('script')
    <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js"></script>
    <script src="{{ asset('plugins/vue/components/vue-the-mask-master/dist/vue-the-mask.js') }}"></script>
    <script>
        new Vue({
            el: "#destinatario",
            data: {
                agencia: '',
                conta: ''
            }
        });

        $(function(){
            @if(!Auth::user()->validado)
            swal({
                    title: "Aviso",
                    html: true,
                    text: "<p class='text-red'>Para você fazer transferências, seus documentos precisam estar aprovados!</p>\n" +
                        "Se você ainda não os enviou, <a href=\"{{ route('dados-usuario.identificacao') }}\">aqui</a> para enviar agora.\n" +
                        "Caso já os tenha enviado, aguarde a aprovação.",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: "#00ff00",
                    confirmButtonText: "OK",
                    cancelButtonText: "Cancelar",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }
                    @if(!Auth::user()->google2fa_secret)
                ,
                function (isConfirm) {
                    if (isConfirm) {
                        swal({
                            title: "Atenção",
                            html: true,
                            text: "Para sua segurança, esta operação requer que você ative a \"Autenticação de 2 fatores\", <a href=\"{{ route('dados-usuario.seguranca') }}\">clique aqui</a>" +
                                " para ativar.",
                            type: "error",
                            showCancelButton: false,
                            confirmButtonColor: "#00ff5a",
                            confirmButtonText: "OK",
                            cancelButtonText: "Cancelar",
                            closeOnConfirm: false,
                            closeOnCancel: false
                        });
                    }
                }
            @endif
            );
            @endif
        });
    </script>
@endsection
