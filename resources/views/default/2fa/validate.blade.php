<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login sistema | Autenticação 2 fatores</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
  <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.css') }}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition lockscreen">
<!-- Automatic element centering -->
<div class="lockscreen-wrapper">
  <div class="lockscreen-logo">
    @if(session('2fa:user:titulo') instanceof \App\Models\Titulos and session('2fa:user:titulo')->id == 1)
      <a href="{{ route('2fa.validate') }}">Minha<b>Conta</b></a>
    @else
      <a href="{{ route('2fa.validate') }}"><b>Escritório</b>Virtual</a>
    @endif
  </div>
  <!-- User name -->
  <div class="lockscreen-name">{{ session('2fa:user:name') }}</div>

  <!-- START LOCK SCREEN ITEM -->
  <div class="lockscreen-item">
    <!-- lockscreen image -->
    <div class="lockscreen-image">
      <img src="@if(session('2fa:user:image')){{ route('imagecache', ['user', 'user/'.session('2fa:user:image')]) }}@else{{ route('imagecache', ['user', 'user-img.jpg']) }}@endif" alt="{{ session('2fa:user:name') }}">
    </div>
    <!-- /.lockscreen-image -->

    <!-- lockscreen credentials (contains the form) -->
    <form class="lockscreen-credentials" role="form" method="POST" action="{{ route('2fa.validate') }}">
      {!! csrf_field() !!}
      <div class="input-group{{ $errors->has('totp') ? ' has-error' : '' }}">
        <input type="number" class="form-control" name="totp">
        <div class="input-group-btn">
          <button type="submit" class="btn"><i class="fa fa-arrow-right text-muted"></i></button>
        </div>
      </div>
    </form>
    <!-- /.lockscreen credentials -->

  </div>
  <!-- /.lockscreen-item -->

  @if ($errors->has('totp'))
    <div class="help-block text-center text-red">
      {!! $errors->first('totp') !!}
    </div>
  @endif

  <div class="help-block text-center">
    Informe o código gerado no aplicativo Google Autenticador.
  </div>
  <div class="text-center">
    <a href="{{ route('auth.logout') }}">Ou entrar com outro usuário.</a>
  </div>
  <div class="lockscreen-footer text-center">
    <small><strong>Copyright &copy; {{ date('Y') }} MasterMundi <i class="fa fa-copyright"></i> .</strong> Todos os direitos reservados.</small>
  </div>
</div>
<!-- /.center -->
</body>
</html>