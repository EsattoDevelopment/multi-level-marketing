<!DOCTYPE html>
<html>
<head>
    <title>{{ env('COMPANY_NAME') }}, volta já.</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.min.css?v=280519') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/skins/skin-black.min.css') }}">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        @media screen and (max-width: 990px) {
            p{ text-align: center; }
        }
        @if($empresa->background_manutencao)
        html, body {
            height: 100%;
        }

        html{
            background: url('{{ asset('storage/images/empresa/'.$empresa->background_manutencao) }}');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            overflow: hidden;
        }

        body{
            background: none;
        }
        @endif
    </style>
</head>

<body class="skin-black sidebar-collapse">
@if(!$empresa->background_manutencao)
<div class="content-wrapper">
    <section class="content">
        <img style="max-width: 200px; margin: 0 auto; display: inherit;" src="{{ asset('images/mastermunid.png') }}" alt="MasterMundi">
        <div class="error-page">

            <h2 class="headline text-black" style="margin-top: -15px;">503</h2>

            <div class="error-content">
                <h3><i class="fa fa-warning text-black"></i> Desculpe o transtorno,</h3>

                <p>
                    Não é possível atender sua solicitação no momento. <br>
                    O sistema está sendo atualizado para melhor atendê-lo.
                </p>
            </div>
        </div>
    </section>
</div>
@endif
</body>

<script src="{{ asset('plugins/jQuery/jQuery-2.2.0.min.js') }}"></script>
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('dist/js/app.min.js') }}"></script>
</html>
