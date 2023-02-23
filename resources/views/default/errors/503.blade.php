<!DOCTYPE html>
<html>
    <head>
        <title>{{ env('COMPANY_NAME') }}, volta já.</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            html{
                background: url('{{asset("images")}}/manutencao.jpg');
                background-repeat: no-repeat;
                background-size: cover;
                background-position: center;
                overflow: hidden;
            }

            body{
                background: none;
            }
        </style>
    </head>
    <body style="text-align: center;">
    {{--<img style="margin-top: 10%; width: 400px;" src="{{ asset('images/mastermunid.png') }}" alt="">
    <h1>Desculpe o transtorno, sistema em atualização para melhor atende-lo.</h1>--}}
    </body>
</html>
