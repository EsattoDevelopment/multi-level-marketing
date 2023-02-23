<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ isset($title) ? $title : $empresa->nome_fantasia}}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" href="@if(strlen(trim($empresa->favicon)) > 0){{ asset('storage/images/empresa/' . $empresa->favicon) }} @else {{ asset('images/favicon.png') }} @endif">
    <link rel="icon" href="@if(strlen(trim($empresa->favicon)) > 0){{ asset('storage/images/empresa/' . $empresa->favicon) }} @else {{ asset('images/favicon.png') }} @endif" type="image/x-icon">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#1b75bb">
    <meta name="theme-color" content="#1b75bb">

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
    <link href="{{ asset('images/apple_splash_1242.png') }}" sizes="1242x2208" rel="apple-touch-startup-image" />  {{--Iphone 6, 7, 8 plus --}}
    <link href="{{ asset('images/apple_splash_750.png') }}" sizes="750x1334" rel="apple-touch-startup-image" /> {{--Iphone 6, 7, 8--}}
    <link href="{{ asset('images/apple_splash_640.png') }}" sizes="640x1136" rel="apple-touch-startup-image" /> {{--Iphone SE --}}

    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

    @yield('meta')

    @yield('style')

    <style>
        .tooltip2 {
            position: relative;
        }

        .tooltip2 .tooltiptext {
            visibility: hidden;
            width: 200px;
            background-color: black;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;

            /* Position the tooltip */
            position: absolute;
            z-index: 1;
            bottom: 150%;
            left: 50%;
            margin-left: -60px;
            white-space: normal;
        }

        .tooltip2:hover .tooltiptext {
            visibility: visible;
        }
    </style>

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.min.css?v=280519') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('dist/css/skins/skin-'.$empresa->cor.'.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/pace/pace.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<!-- ADD THE CLASS fixed TO GET A FIXED HEADER AND SIDEBAR LAYOUT -->
<!-- the fixed layout is not compatible with sidebar-mini -->
<body class="hold-transition skin-{{ $empresa->cor }} fixed sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="" class="logo">
            @if(Auth::user()->titulo == null || Auth::user()->titulo->id == 1)
                <span class="logo-mini">MC</span>
                <span class="logo-lg">Minha Conta</span>
            @else
                <span class="logo-mini"><b>E</b>V</span>
                <span class="logo-lg"><b>Escritório </b>Virtual</span>
            @endif
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Messages: style can be found in dropdown.less-->

                    <li><div id="google_translate_element"></div></li>

                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="@if(Auth::user()->image){{ route('imagecache', ['user', 'user/'.Auth::user()->image]) }}@else{{ route('imagecache', ['user', 'user-img.jpg']) }}@endif" class="user-image" alt="User Image">
                            <span class="hidden-xs">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="@if(Auth::user()->image){{ route('imagecache', ['user', 'user/'.Auth::user()->image]) }}@else{{ route('imagecache', ['user', 'user-img.jpg']) }}@endif" class="img-circle" alt="User Image">

                                <p>
                                    {{ Auth::user()->name }} {{--@if(Auth::user()->titulo) - {{ Auth::user()->titulo()->first()->name }} @endif--}}
                                    <small>Membro desde {{ Auth::user()->created_at }}</small>
                                </p>
                            </li>

                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    @if (in_array(Session::get('hasClonedUser'), [1,2]))

                                        <a onclick="event.preventDefault(); document.getElementById('cloneuser-form').submit();"><span>Voltar para Admin</span></a>
                                        <form id="cloneuser-form" action="{{ route('user.logar.back') }}" method="post">
                                            {{ csrf_field() }}
                                        </form>

                                    @endif
                                </div>
                                <div class="pull-right">
                                    <a href="{{ route('auth.logout') }}" class="btn btn-default btn-flat">Sair</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    {{--<li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>--}}
                </ul>
            </div>
        </nav>
        <script type="text/javascript">
            function googleTranslateElementInit() {
                new google.translate.TranslateElement({pageLanguage: 'pt', includedLanguages: 'de,en,es,fr,it,ja,pt,ru,zh-TW'}, 'google_translate_element');
            }
        </script>
        <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    </header>
