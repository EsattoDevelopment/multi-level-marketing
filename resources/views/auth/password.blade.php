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

      @if (Session::has('status'))
        <div class="alert alert-success">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          {{ Session::get('status') }}
        </div>
      @endif

      <form action="/password/email" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        @include('default.errors.errors')

        <div class="form-group has-feedback">
          <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="E-mail">
          <span class="icon-mastermdr-email form-control-feedback"></span>
        </div>

        <div class="row">
          <div class="col-xs-12" style="margin-bottom: 5px;">
            <button type="submit" class="btn btn-mastermdr btn-mastermdr-cadastro btn-block btn-flat">recuperar</button>
          </div>
          <div class="col-xs-12 txt-alg-center">
            <a  href="{{ route('auth.login') }}" class="esqueci-mastermdr">voltar</a>
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