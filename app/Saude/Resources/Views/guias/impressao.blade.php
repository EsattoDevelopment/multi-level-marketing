<html>
<head>
    <title>Impressão guia de {{ config('constants.tipo_atendimento')[$guia->tipo_atendimento] }}: {{ $guia->id }}
        - {{ $guia->tipo == 1 ? $guia->usuario->name : $guia->dependente->name }}</title>
</head>
<body {{--onload="window.print()"--}} style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif">
<div>
    <div style="float:left; margin-right: 25px;">
        <img width="250" src="{{ asset('logos/logo-saude.png') }}" alt="">
    </div>
    <div style="float:left;">
        <br>
        <b>{{ env('COMPANY_NAME') }}</b> <br><br>
        {{ $empresa->logradouro }}, {{ $empresa->numero }}, {{ $empresa->bairro }} <br>
        {{ $empresa->cidade }}, {{ $empresa->uf }} <br>
        Tel: {{ $empresa->telefone_contato }} <br><br>
        Cód. Guia: {{ $guia->id }}
    </div>
    <div style="clear:both;"></div>
    <hr style="border: 2px solid #000000;">
    <div style="text-align: center;">
        <b>GUIA DE AUTORIZAÇÃO - {{ mb_strtoupper(config('constants.tipo_atendimento')[$guia->tipo_atendimento]) }}</b>
    </div>
</div>

<div style="padding: 10px;">
    <div>
        CONTRATO : {{ $guia->usuario->codigo }} <br>
        ASSOCIADO : {{ $guia->tipo == 1 ? $guia->usuario->name : $guia->dependente->name }} <br>
        DATA NASC : {{ $guia->tipo == 1 ? $guia->usuario->data_nasc : $guia->dependente->dt_nasc }} <br>
        CPF : {{ $guia->tipo == 1 ? $guia->usuario->cpf : $guia->dependente->cpf }} <br>
        PLANO : {{ $guia->plano->name }}
    </div>
    <br>
    <br>
    <div style="text-align: center;">
        <b><u>{{ mb_strtoupper($guia->clinica->name) }}</u></b>
    </div>
    <br>
    <br>
    GUIA GERADA EM {{ $guia->created_at->timezone('America/Campo_Grande')->format('d/m/Y H:i:s') }} <br><br>
    PROCEDIMENTOS AUTORIZADOS EM {{ $guia->dt_autorizado->timezone('America/Campo_Grande')->format('d/m/Y H:i:s') }} <br><br>

    @if(in_array($guia->tipo_atendimento, [2,3,4]))
        MEDICO: {{ $guia->medico->name }} <br><br>
        @if(in_array($guia->tipo_atendimento, [2,4]))
            CONSULTA MÉDICA - Valor: {{ $sistema->moeda }}: {{ mascaraMoeda($sistema->moeda, $guia->valor_consulta, 2, ',', '.') }} (@numToTxt(number_format($guia->valor_consulta, 2, true))) <br><br>
            Valor Total Procedimento: {{ $sistema->moeda }}: {{ mascaraMoeda($sistema->moeda, $guia->valor_consulta, 2, ',', '.') }} (@numToTxt(number_format($guia->valor_consulta, 2, true)))
        @else
            <b>RETORNO</b>
        @endif
    @elseif($guia->tipo_atendimento == 5)
        FISIOTERAPEUTA: {{ $guia->medico->name }} <br><br>
        CONSULTA - Valor: {{ $sistema->moeda }}: {{ mascaraMoeda($sistema->moeda, $guia->valor_fisioterapia, 2, ',', '.') }} (@numToTxt(number_format($guia->valor_fisioterapia, 2, true))) <br><br>
        Valor Total Procedimento: {{ $sistema->moeda }}: {{ mascaraMoeda($sistema->moeda, $guia->valor_fisioterapia, 2, ',', '.') }} (@numToTxt(number_format($guia->valor_fisioterapia, 2, true)))
    @elseif($guia->tipo_atendimento == 6)
        PROCEDIMENTOS: <br><br>
        @foreach($guia->procedimentos as $p)
            {{ mb_strtoupper($p->name) }} - Valor: {{ $sistema->moeda }}  {{ mascaraMoeda($sistema->moeda, $p->co, 2, ',', '.') }} (@numToTxt(number_format($p->co, 2, true))) <br>
        @endforeach
    @else
        @if($guia->exames->count() > 0)
            EXAMES COBERTOS PELO PLANO: <br><br>
            @foreach($guia->exames as $exame)
                - {{ $exame->nome }} <br>
            @endforeach
        @endif
    @endif
    <br><br><br><br>

    @if($guia->observacao)
        Observação: <br><br>
        {{ $guia->observacao }}
    @endif

    <br><br><br><br>

    <div style="clear:both;"></div>
    <b style="float: right;">DECLARO TER CONFERIDO TODOS OS VALORES</b>
    <div style="clear:both;"></div>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <div style="float: right; width: 400px; text-align: right;">
        ________________________________________ <br>
        <em>Assinatura do associado</em>
    </div>
</div>

</body>
</html>