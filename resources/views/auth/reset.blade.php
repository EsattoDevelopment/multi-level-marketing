@extends('auth.layout')

@section('title')
  <title>Recuperação de Senha | {{ $empresa->nome_fantasia }}</title>
@endsection

@section('content')
  <div class="login-box">
    <!-- /.login-logo -->
    <div class="login-box-body">
      <div class="login-logo">
        <a href="@if($empresa->site) {{ $empresa->site }} @else javascript:; @endif">
          <img style="max-width: 100%" src="@if(strlen(trim($empresa->background)) > 0){{ route('imagecache', ['logo', 'empresa/'.$empresa->logo]) }} @else {{ route('imagecache', ['logo', 'logo-aqui.jpg']) }} @endif" alt="Logo">
        </a>
      </div>

      <form action="/password/reset" method="post">
        {!! csrf_field() !!}
        <input type="hidden" name="token" value="{{ $token }}">

        @include('default.errors.errors')

        <div class="form-group has-feedback">
          <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="E-mail">
          <span class="icon-mastermdr-email form-control-feedback"></span>
        </div>

        <div class="form-group has-feedback">
          <input type="password" required name="password" class="form-control" placeholder="Senha">
          <span class="icon-mastermdr-pass form-control-feedback"></span>
        </div>

        <div class="form-group has-feedback">
          <input type="password" required name="password_confirmation" class="form-control" placeholder="Confirme a senha">
          <span class="icon-mastermdr-pass form-control-feedback"></span>
        </div>

        <div class="row">
          <div class="col-xs-12">
            <button type="submit" class="btn btn-mastermdr btn-mastermdr-cadastro btn-block btn-flat">Salvar</button>
          </div>
          <!-- /.col -->
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