<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LICENÇA - Nº {{ $pedido->id }} - {{ $pedido->itens()->first()->name_item }}</title>
    <style>
        body{font-size: 18px;}
        p{text-align: justify}
        table {
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
    </style>
</head>
<body style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;">
<div style="width: 100%; margin: 50px 10px 50px 0px;">
    <b style="width: 100%; text-align: center; float:left;">LICENÇA - N° {{ $pedido->id }}</b> <br><br>
    <p><b>LOREM IPSUM: {{ mb_strtoupper(strlen($user->cpf) == 18 ? $user->empresa : ($user->idade >= 18 ? $user->name : $responsavel->nome)) }}</b>
        @if(strlen($user->cpf) == 18)
            , pessoa jurídica inscrita no CNPJ sob o nº {{ $user->cpf }}, com sede
        @else
            , brasileira(o), {{ $user->idade >= 18 ? ($user->profissao ? $user->profissao . ', ' : '') : '' }} @if($user->estado_civil > 0){{ mb_strtoupper(config('constants.estado_civil.' . ($user->idade >= 18 ? $user->estado_civil : $responsavel->estado_civil))) . ',' }} @endif portador do CPF n. {{ $user->idade >= 18 ? $user->cpf : $responsavel->cpf }} e do RG n° {{ $user->idade >= 18 ? $user->rg : $responsavel->rg }}, residente e domiciliada(o)
        @endif
        na {{ $endereco->get("logradouro") }}{{ $endereco->get("complemento") ? ', ' . $endereco->get("complemento") : '' }}, n° {{ $endereco->get("numero") }}, {{ $endereco->get("bairro") }}, em {{ $endereco->get("cidade") }}, {{ $endereco->get("estado") }}, CEP {{ $endereco->get("cep") }}
        @if($user->idade >= 18)
            , doravante denominada <b>LOREM IPSUM</b>.
        @else
            , doravante denominada <b>LOREM IPSUM</b>, Lorem Ipsum is simply dummy text of the printing and typesetting industry.
        @endif
    </p>
    @if($user->idade < 18)
        <p style="margin-top: -15px;"><b>LOREM IPSUM: {{ mb_strtoupper(strlen($user->cpf) == 18 ? $user->empresa : $user->name) }}</b>, LOREM IPSUM, brasileira(o), @if($user->estado_civil > 0){{ mb_strtoupper(config('constants.estado_civil.' . $user->estado_civil)) . ',' }} @endif portador do CPF n. {{ $user->cpf }} e do RG n° {{ $user->rg }}, residente e domiciliada(o) na {{ $endereco->get("logradouro") }}{{ $endereco->get("complemento") ? ', ' . $endereco->get("complemento") : '' }}, n° {{ $endereco->get("numero") }}, {{ $endereco->get("bairro") }}, em {{ $endereco->get("cidade") }}, {{ $endereco->get("estado") }}, CEP {{ $endereco->get("cep") }}, doravante denominada(o) <b>LOREM IPSUM</b>.</p>
    @endif
    <p><b>LOREM IPSUM: {{ env('COMPANY_NAME') }}</b>, Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, nº XX.XXX.XXX/XXXX-XX, when an unknown printer took a galley of type and scrambled it to make a type specimen book <b>LOREM IPSUM</b>.</p>
    <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source.</p>
    <br>
    <b style="">Where can I get some?</b> <br>
    <p><b>Curabitur 1ª.</b> There are many variations of passages of Lorem Ipsum available {{ $sistema->moeda }} {{ mascaraMoeda($sistema->moeda, $pedido->valor_total, 2, false) }} (@numToTxt(number_format($pedido->valor_total, 2, ',', '.'))), but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable.</p>
    <br>
    <b style="">Why do we use it?</b> <br>
    <p><b>Curabitur 2ª.</b> Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy.</p>
    <br>
    <b style="">What is Lorem Ipsum?</b> <br>
    <p><b>Curabitur 3ª.</b> Various versions have evolved over the years, <b>{{ $mesesContrato }} (@numToTxt($mesesContrato, false)) {{ $mesesContrato > 1 ? 'meses' : 'mês' }}</b>, sometimes by accident, sometimes on purpose (injected humour and the like) sometimes by accident, sometimes on purpose (injected humour and the like)</p>
    <br>
    <b style="">Vivamus malesuada suscipit tellus?</b> <br>
    <p><b>Curabitur 4ª.</b> The standard chunk of {{ number_format($teto, (substr($teto_porcentagem[1], -1) > 0 ? 2 : 1), ',', '.') }}% (@numToTxt($teto_porcentagem[0], false)@if($teto_porcentagem[1] > 0) vírgula @numToTxt(substr($teto_porcentagem[1], 0, (substr($teto_porcentagem[1], -1) > 0 ? 2 : 1)), false)@endif por cento), Lorem Ipsum used since the 1500s is reproduced below for those interested.</p>
    @if($user->idade > 18)
        <p><b>Curabitur 5ª.</b> All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>
    @else
        <p><b>Curabitur 5ª.</b> All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>
    @endif
</div>

<div style="width: 100%; margin: 50px 10px 50px 0px; display: block; page-break-before: always;">
    <b style="">Class aptent taciti socios</b> <br>
    <p><b>Curabitur 6ª.</b> Morbi at orci ullamcorper, iaculis nulla eget, posuere ligula. Pellentesque semper, sapien eget congue interdum, sapien eros euismod massa, vel laoreet urna risus nec lorem. </p>
    <p>6.1. A eventual tolerância em qualquer atraso ou demora no cumprimento da obrigação em hipótese alguma poderá ser considerada como modificação das condições deste termo, que permanecerão em vigor para todos os efeitos.</p>
    <br>
    <b style="">Praesent sed eros</b> <br>
    <p><b>Curabitur 7ª.</b> Nulla lectus dolor, rutrum at viverra vel, bibendum at neque. Vivamus nec consequat sem, sed malesuada ante. Sed vitae erat arcu. Quisque hendrerit ante sit amet magna rutrum, nec tincidunt sapien pharetra. </p>
    <br>
    <b style="">Morbi at orci ullamcorper,</b> <br>
    <p><b>Curabitur 8ª.</b> Morbi rhoncus quam consequat ullamcorper auctor. Phasellus commodo felis id laoreet ultricies. Morbi bibendum est eget tellus varius iaculis. Curabitur a mollis mi, vitae aliquet justo. Mauris a pellentesque sem.</p>
    <p><b>Curabitur 9ª.</b> Quisque aliquam ut sapien quis porttitor. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;</p>
    <p><b>Curabitur 10ª.</b> Suspendisse eu purus nunc. Curabitur varius metus convallis diam cursus posuere. Sed vehicula pulvinar neque, non bibendum sem ornare id. Donec sit amet velit tempor, venenatis sapien vitae, hendrerit enim.</p>
    <p><b>Curabitur 11ª.</b> Ut sodales massa ac pharetra bibendum. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur eu ipsum nisl. In egestas iaculis blandit. Nulla facilisi. Sed blandit fringilla magna, eu consequat nibh lobortis ullamcorper.</p>
    <br>
    <b style="">Suspendisse vitae malesuada tellus</b> <br>
    <p><b>Curabitur 12ª.</b> Curabitur sit amet quam et lectus fringilla laoreet ut ut arcu. Duis vitae est a nibh pellentesque vehicula eget in erat. Proin vitae dui a neque varius ultrices a eget tortor. Nullam accumsan a purus vel vehicula. Ut sagittis,;</p>
    <p>Donec sit amet dapibus libero. Mauris augue sem, dapibus in tellus ut, sollicitudin finibus ante. Suspendisse at convallis arcu, sed elementum enim.</p>
    <br>
    <p style="text-align: right;">Campinas, SP, {{ Date::parse($pedido->dadosPagamento->getOriginal('data_pagamento'))->format('d \d\e F \d\e Y') }}.</p>
    <br><br><br>
    <div style="float: left; width: 50%; text-align: left;">
        <div style="text-align: center; float: left;">
            ____________________________________<br>
            @if($user->idade >= 18)
                <p style="text-align: center; margin-top: 2px; margin-bottom: 0;"><b>{{ mb_strtoupper(strlen($user->cpf) == 18 ? $user->empresa : $user->name) }}</b></p>
                <p style="text-align: center; margin-top: 0;">LOREM IPSUM</p>
            @else
                <p style="text-align: center; margin-top: 2px; margin-bottom: 0;"><b>{{ mb_strtoupper($responsavel->nome) }}</b></p>
                <p style="text-align: center; margin-top: 0; margin-bottom: 2px;">LOREM IPSUM e LOREM IPSUM</p>
                <p style="text-align: center; margin-top: 0;"><b>LOREM IPSUM: <br>{{ mb_strtoupper($user->name) }}</b></p>
            @endif
        </div>
    </div>

    <div style="float: left; width: 50%; text-align: right;">
        <div style="text-align: center; float: right;">
            ____________________________________<br>
            <p style="text-align: center; margin-top: 2px; margin-bottom: 0;"><b>{{ env('COMPANY_NAME') }}</b></p>
            <p style="text-align: center; margin-top: 0;">LOREM IPSUM</p>
        </div>
    </div>
</div>
<script>window.onload = function () {
        window.print();
    }
</script>
</body>
</html>