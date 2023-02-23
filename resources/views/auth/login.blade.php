@extends('auth.layout')

@section('title')
  <title>Login sistema | {{ $empresa->nome_fantasia }}</title>
@endsection

@section('content')
  <div class="login-box">
    <!-- /.login-logo -->
    <div class="login-box-body">
      <div class="login-logo">
        <a href="@if($empresa->site) {{ $empresa->site }} @else javascript:; @endif">
          <img style="max-width: 100%" src="@if(strlen(trim($empresa->logo)) > 0){{ route('imagecache', ['logo', 'empresa/'.$empresa->logo]) }} @else {{ route('imagecache', ['logo', 'logo-aqui.jpg']) }} @endif" alt="Logo">
        </a>
      </div>
      <form action="{{ route('auth.login') }}" method="post">
        {!! csrf_field() !!}

        @include('default.errors.errors')

        <div class="form-group has-feedback">
          <input type="text" name="username" value="{{ old('username') }}" class="form-control" placeholder="Conta, CPF ou E-mail">
          <span class="icon-mastermdr-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback" style="margin-bottom: 20px;">
          <input type="password" name="password" class="form-control" placeholder="Senha">
          <span class="icon-mastermdr-pass form-control-feedback"></span>
        </div>

        <div class="row">

          <div class="col-xs-12" style="margin-bottom: 5px;">
            <button type="submit" class="btn btn-mastermdr btn-block pull-left btn-flat">entrar</button>
          </div>

          <div class="col-xs-12 txt-alg-center" style="margin-bottom: 10px;">
            <a  href="/password/email" class="esqueci-mastermdr">esqueci minha senha</a>
          </div>
          @if($sistema->habilita_registro_usuario_sem_indicacao)
            <div class="col-xs-12" style="margin-bottom: 5px;">
              <a  href="{{ route('auth.register') }}" class="btn btn-mastermdr btn-mastermdr-cadastro btn-block pull-left btn-flat">abrir minha conta</a>
            </div>

            <div class="col-xs-12 txt-alg-center">
              <a  href="{{ route('auth.register') }}" class="esqueci-mastermdr">ainda não tenho conta</a>
            </div>
          @endif
        </div>
      </form>
    </div>
    <!-- /.login-box-body -->
  </div>
@endsection

@section('script')
  <!-- iCheck -->
  <script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
  <script>
    $(function () {
      $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
      });
    });
  </script>
  @endsection