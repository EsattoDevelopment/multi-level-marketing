<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    @yield('title')
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- favicon -->
    <link rel="shortcut icon" href="@if(strlen(trim($empresa->favicon)) > 0){{ asset('storage/images/empresa/' . $empresa->favicon) }} @else {{ asset('images/favicon.png') }} @endif">
    <link rel="icon" href="@if(strlen(trim($empresa->favicon)) > 0){{ asset('storage/images/empresa/' . $empresa->favicon) }} @else {{ asset('images/favicon.png') }} @endif" type="image/x-icon">
    <meta name="msapplication-TileColor" content="#1b75bb">
    <meta name="theme-color" content="#1b75bb">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- Icon Iphone -->
    <link rel="apple-touch-icon" sizes="96x96"  href="{{ asset('images/icon-96x96.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('images/icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/icon-180x180.png') }}">
    <link rel="apple-touch-icon" sizes="167x167" href="{{ asset('images/icon-167x167.png') }}">

    <!-- Tela inicial Iphone -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <link href="{{ asset('images/fundo-iphone-x.png') }}" sizes="2048x2732" rel="apple-touch-startup-image" /> {{--Ipad Pro 12.9 --}}
    <link href="{{ asset('images/fundo-iphone-x.png') }}" sizes="1668x2224" rel="apple-touch-startup-image" /> {{--Ipad Pro 10.5 --}}
    <link href="{{ asset('images/fundo-iphone-x.png') }}" sizes="1536x2048" rel="apple-touch-startup-image" /> {{--Ipad mini 4 - 7.9 | Ipad 9.7  --}}
    <link href="{{ asset('images/fundo-iphone-x.png') }}" sizes="1125x2436" rel="apple-touch-startup-image" />  {{--Iphone X --}}
    <link href="{{ asset('images/apple_splash_750.png') }}" sizes="1242x2208" rel="apple-touch-startup-image" />  {{--Iphone 6, 7, 8 plus --}}
    <link href="{{ asset('images/apple_splash_750.png') }}" sizes="750x1334" rel="apple-touch-startup-image" /> {{--Iphone 6, 7, 8--}}
    <link href="{{ asset('images/apple_splash_750.png') }}" sizes="640x1136" rel="apple-touch-startup-image" /> {{--Iphone SE --}}

<!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/mastermdr.min.css?v=220719') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/square/yellow.css') }}">
    <!-- Font -->
    <link rel="stylesheet" href="{{ asset('dist/fonts/foco/light.css') }}">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        html{
            background: url('@if(strlen(trim($empresa->background)) > 0){{ route('imagecache', ['background', 'empresa/'.$empresa->background]) }} @else {{ route('imagecache', ['background', 'bg_default.jpg']) }} @endif');
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
        }

        .liberty-logo {
            background: url('{{ $empresa->logo_flutuante ? route('imagecache', ['logo-flutuante', 'empresa/'.$empresa->logo_flutuante]) : '' }}') center center no-repeat;
        }
    </style>
</head>
<body class="hold-transition fixed sidebar-mini sidebar-collapse">

    @yield('content')

<!-- /.login-box -->
<div class="liberty-logo"></div>
<!-- jQuery 2.2.0 -->
<script src="{{ asset('plugins/jQuery/jQuery-2.2.0.min.js') }}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>

@yield('script')
</body>
</html>
