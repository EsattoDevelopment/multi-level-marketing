@extends('default.layout.main')

@section('content')

    <section class="content-header">
        <h1>
            Segurança
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Segurança</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        @include('default.errors.errors')
        <div class="row">
            <div>
                <form role="form"
                      action="{{ route('dados-usuario.seguranca.update') }}"
                      method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                    <input type="hidden" name="user_id" value="{{ $usuario->id }}">
                    <input type="hidden" name="_method" value="PUT">

                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-danger">
                            <div class="box-header with-border">
                                <h3 class="box-title">Utilizamos a autenticação de 2 fatores da <b>Google</b></h3>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <div class="box-body">
                                <div class="form-group col-xs-12" style="margin-bottom: 0;">
                                    @if (Auth::user()->google2fa_secret)
                                        <a href="{{ url('2fa/disable') }}" class="btn btn-warning">Desativar</a>
                                        <br><br>
                                        <label>
                                            <input type="checkbox" name="google2fa_login" value="1"
                                                    {{ old('google2fa_login', $usuario->google2fa_login) == 1 ? 'checked' : ''  }}>
                                            Solicitar código ao realizar login no sistema?
                                        </label>
                                    @else
                                        <a href="{{ url('2fa/enable') }}" class="btn btn-primary">Ativar</a>
                                    @endif
                                </div>
                            </div>
                            @if (!Auth::user()->google2fa_secret)
                                <div class="box-footer">
                                    <small class="title">Para baixar o aplicativo <b>Google Authenticator</b> em seu celular, utilize os links abaixo </small><br><br>
                                    <a class="pull-left" style="margin-right: 20px;" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank">
                                        <img src="{{ asset('images/badge-google-play-badge-p.png') }}" alt="Play Store">
                                    </a>
                                    <a class="pull-left" href="https://apps.apple.com/br/app/google-authenticator/id388497605" target="_blank">
                                        <img src="{{ asset('images/disponivel-na-app-store-botao-p.png') }}" alt="App Store">
                                    </a>
                                </div>
                        @endif
                        <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>

                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-danger">
                            <div class="box-header with-border">
                                <h3 class="box-title">Sua SENHA de acesso <b>pessoal</b></h3><br>
                                <small>Para sua segurança sugerimos que troque sua senha mensalmente.</small>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <div class="box-body">
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">Senha atual</label>
                                    <input type="password" name="passwordatual" class="form-control" placeholder="Senha atual">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">Nova Senha</label>
                                    <input type="password" name="password" class="form-control" placeholder="Nova Senha">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">Redigite a nova senha</label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                           placeholder="Redigite a nova senha">
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>
                    <div class="col-xs-12">
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success btn-block btn-lg pull-left">Salvar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/square/red.css') }}">
@endsection

@section('script')
    <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function(){
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red',
                increaseArea: '20%' // optional
            });
        });
    </script>
@endsection

