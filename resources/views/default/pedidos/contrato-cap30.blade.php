<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CONTRATO DE MÚTUO FINANCEIRO - Nº {{ $pedido->id }} - {{ $pedido->itens()->first()->name_item }}</title>
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
    <b style="width: 100%; text-align: center; float:left;">CONTRATO DE MÚTUO FINANCEIRO - N° {{ $pedido->id }}</b> <br><br>
    <p><b>MUTUANTE: {{ mb_strtoupper(strlen($user->cpf) == 18 ? $user->empresa : ($user->idade >= 18 ? $user->name : $responsavel->nome)) }}</b>
        @if(strlen($user->cpf) == 18)
            , pessoa jurídica inscrita no CNPJ sob o nº {{ $user->cpf }}, com sede
        @else
            , brasileira(o), {{ $user->idade >= 18 ? ($user->profissao ? $user->profissao . ', ' : '') : '' }} @if($user->estado_civil > 0){{ mb_strtoupper(config('constants.estado_civil.' . ($user->idade >= 18 ? $user->estado_civil : $responsavel->estado_civil))) . ',' }} @endif portador do CPF n. {{ $user->idade >= 18 ? $user->cpf : $responsavel->cpf }} e do RG n° {{ $user->idade >= 18 ? $user->rg : $responsavel->rg }}, residente e domiciliada(o)
        @endif
        na {{ $endereco->get("logradouro") }}{{ $endereco->get("complemento") ? ', ' . $endereco->get("complemento") : '' }}, n° {{ $endereco->get("numero") }}, {{ $endereco->get("bairro") }}, em {{ $endereco->get("cidade") }}, {{ $endereco->get("estado") }}, CEP {{ $endereco->get("cep") }}
        @if($user->idade >= 18)
            , doravante denominada <b>MUTUANTE</b>.
        @else
            , doravante denominada <b>MUTUANTE</b>, que de acordo com os documentos e leis brasileiras é o REPRESENTANTE LEGAL DO BENEFICIÁRIO IDENTIFICADO A SEGUIR.
        @endif
        </p>
    @if($user->idade < 18)
        <p style="margin-top: -15px;"><b>BENEFICIÁRIO: {{ mb_strtoupper(strlen($user->cpf) == 18 ? $user->empresa : $user->name) }}</b>, MENOR, brasileira(o), @if($user->estado_civil > 0){{ mb_strtoupper(config('constants.estado_civil.' . $user->estado_civil)) . ',' }} @endif portador do CPF n. {{ $user->cpf }} e do RG n° {{ $user->rg }}, residente e domiciliada(o) na {{ $endereco->get("logradouro") }}{{ $endereco->get("complemento") ? ', ' . $endereco->get("complemento") : '' }}, n° {{ $endereco->get("numero") }}, {{ $endereco->get("bairro") }}, em {{ $endereco->get("cidade") }}, {{ $endereco->get("estado") }}, CEP {{ $endereco->get("cep") }}, doravante denominada(o) <b>BENEFICIÁRIO</b>.</p>
    @endif
    <p><b>MUTUÁRIO: {{ env('COMPANY_NAME') }}</b>, pessoa jurídica de direito privado, estabelecida na Avenida Doutor José Bonifácio Coutinho Nogueira 214 - sala 232 - Vila Madalena - Campinas SP - CEP 13091-611, doravante denominado <b>MUTUÁRIO</b>.</p>
    <p>As partes acima identificadas têm, entre si, justo e acertado o presente <b>CONTRATO DE MÚTUO FINANCEIRO INDIVIDUAL</b>, que se regerá pelas cláusulas seguintes e pelas condições descritas no presente, conforme o Art. 586 do Código Civil - Lei 10406 de 2002</p>
    <br>
    <b style="">DO OBJETO DO CONTRATO</b> <br>
    <p><b>Cláusula 1ª.</b> O presente tem como OBJETO, o empréstimo da importância de {{ $sistema->moeda }} {{ mascaraMoeda($sistema->moeda, $pedido->valor_total, 2, false) }} (@numToTxt(number_format($pedido->valor_total, 2, ',', '.'))), que o <b>MUTUANTE</b> faz para a <b>MUTUÁRIA</b>. Referida quantia deverá ser entregue por transferência entre contas, TED, DOC ou pagamento de boleto bancário emitido pela <b>MUTUÁRIA</b>, cujo comprovante deverá ser enviado eletronicamente para conferência e arquivo.</p>
    <br><br>
    <b style="">DEVERES DO MUTUÁRIO</b> <br>
    <p><b>Cláusula 2ª.</b> O <b>MUTUÁRIO</b> obriga-se a pagar o valor tomado por mútuo nas condições descritas nesse contrato.</p>
    <br><br>
    <b style="">DO PAGAMENTO</b> <br>
    <p><b>Cláusula 3ª.</b> O pagamento da quantia tomada em mútuo será efetivado no prazo de <b>{{ $mesesContrato * 30 }} (@numToTxt($mesesContrato * 30, false)) {{ $mesesContrato * 30 > 1 ? 'dias' : 'dia' }}</b>, contados da data de início do contrato. O pagamento da correção (juros), infra descrita, será efetuado diariamente (em dias úteis) na conta do <b>{{ $user->idade > 18 ? 'MUTUANTE,' : 'BENEFICIÁRIO'}}</b> durante o período do contrato e será devida até que, findo o presente Instrumento, o valor seja efetivamente entregue ao <b>{{ $user->idade > 18 ? 'MUTUANTE,' : 'BENEFICIÁRIO'}}</b>, inclusive durante o período de liquidação, prazo este que poderá levar até 7 (sete) dias úteis para a efetiva transferência.</p>
    <br><br>
    <b style="">DA CORREÇÃO</b> <br>
    <p><b>Cláusula 4ª.</b> Fica estabelecida a taxa de {{ number_format($teto, (substr($teto_porcentagem[1], -1) > 0 ? 2 : 1), ',', '.') }}% (@numToTxt($teto_porcentagem[0], false)@if($teto_porcentagem[1] > 0) vírgula @numToTxt(substr($teto_porcentagem[1], 0, (substr($teto_porcentagem[1], -1) > 0 ? 2 : 1)), false)@endif por cento) ao mês, incidente sobre o valor original do contrato e seus eventuais aditivos e anexos.</p>
</div>

<div style="width: 100%; margin: 50px 10px 50px 0px; display: block; page-break-before: always;">
    @if($user->idade >= 18)
        <p><b>Cláusula 5ª.</b> Os valores das correções poderão ser solicitados a qualquer momento ao <b>MUTUÁRIO</b> pelo <b>MUTUANTE</b>, após o encerramento do contrato, tendo o <b>MUTUÁRIO</b> o prazo de 7 (sete) dias úteis para efetuar o pagamento em conta indicada pelo <b>MUTUANTE</b>, sendo necessário que o <b>MUTUANTE</b> seja o titular da conta.</p>
        @else
        <p><b>Cláusula 5ª.</b> Os valores das correções poderão ser solicitados a qualquer momento ao <b>MUTUÁRIO</b> pelo <b>MUTUANTE</b>, após o encerramento do contrato, tendo o <b>MUTUÁRIO</b> o prazo de 7 (sete) dias úteis para efetuar o pagamento em conta indicada do <b>BENEFICIÁRIO</b> pelo <b>MUTUANTE</b>, sendo necessário que o <b>BENEFICIÁRIO</b> seja o titular da conta.</p>
    @endif
        <br><br>
    <b style="">DO ATRASO</b> <br>
    <p><b>Cláusula 6ª.</b> Havendo atraso de pagamento superior a 30 (trinta) dias dos prazos indicados neste Instrumento, inclusive em seus Anexos, incidirá multa de 2% (dois por cento) sobre o valor vencido, juros de 1% ao mês e correção monetária pelo INPC, pro rata, calculados até a data do efetivo pagamento.</p>
    <p>6.1. A eventual tolerância em qualquer atraso ou demora no cumprimento da obrigação em hipótese alguma poderá ser considerada como modificação das condições deste termo, que permanecerão em vigor para todos os efeitos.</p>
    <br><br>
    <b style="">DA RESCISÃO</b> <br>
    <p><b>Cláusula 7ª.</b> Em caso de rescisão por parte do <b>MUTUANTE</b> antes do termino do contrato, será aplicada de 20% (vinte por cento) sobre o valor do contrato, a ser pago para o <b>MUTUÁRIO</b>.</p>
    <br><br>
    <b style="">CONDIÇÕES GERAIS</b> <br>
    <p><b>Cláusula 8ª.</b> Ressalta-se a tolerância do <b>MUTUANTE</b> que facultará ao <b>MUTUANTE</b>, tomar todas as medidas, sejam judiciais ou extrajudiciais para satisfazer o crédito, sendo que todas as despesas, incluindo honorários advocatícios serão de responsabilidade da <b>MUTUANTE</b>.</p>
        <br>
    <p><b>Cláusula 9ª.</b> O presente contrato terá vigência a partir da sua assinatura, de forma eletrônica ou presencial e comprovação da entrega do valor estipulado na Cláusula 1ª., o que será ratificado pelo <b>MUTUÁRIO</b>.</p>
        <br>
    <p><b>Cláusula 10ª.</b> Os herdeiros e sucessores das partes contratantes se obrigam desde já, ao inteiro teor deste contrato.</p>
        <br>
    <p><b>Cláusula 11ª.</b> Os atos provenientes deste Instrumento serão, em sua totalidade, eletrônicos. Portanto, documentos eletrônicos, e-mails, “print” de telas de site, aplicativos e conversas por meio de telefone celular poderão servir como prova de atos efetivamente praticados em caso de uma demanda judicial ou extra, desde que devidamente periciados por profissional técnico credenciado junto aos órgãos judiciários.</p>
</div>

<div style="width: 100%; margin: 50px 10px 50px 0px; display: block; page-break-before: always;">
    <b style="">DO FORO</b> <br>

    <p><b>Cláusula 12ª.</b> Para dirimir quaisquer controvérsias oriundas do <b>CONTRATO</b>, as partes elegem o foro da comarca de CAMPINAS, estado de São Paulo;</p>
    <p>Por estarem assim justos e contratados, firmam o presente instrumento, em duas vias de igual teor.</p>
    <br><br>
    <p style="text-align: right;">Campinas, SP, {{ Date::parse($pedido->dadosPagamento->getOriginal('data_pagamento'))->format('d \d\e F \d\e Y') }}.</p>
    <br><br><br>
    <div style="float: left; width: 50%; text-align: left;">
        <div style="text-align: center; float: left;">
            ____________________________________<br>
            @if($user->idade >= 18)
                <p style="text-align: center; margin-top: 2px; margin-bottom: 0;"><b>{{ mb_strtoupper(strlen($user->cpf) == 18 ? $user->empresa : $user->name) }}</b></p>
                <p style="text-align: center; margin-top: 0;">MUTUANTE</p>
            @else
                <p style="text-align: center; margin-top: 2px; margin-bottom: 0;"><b>{{ mb_strtoupper($responsavel->nome) }}</b></p>
                <p style="text-align: center; margin-top: 0; margin-bottom: 2px;">MUTUANTE e Responsável Legal</p>
                <p style="text-align: center; margin-top: 0;"><b>Do BENEFICIÁRIO menor: <br>{{ mb_strtoupper($user->name) }}</b></p>
            @endif
        </div>
    </div>

    <div style="float: left; width: 50%; text-align: right;">
        <div style="text-align: center; float: right;">
            ____________________________________<br>
            <p style="text-align: center; margin-top: 2px; margin-bottom: 0;"><b>{{ env('COMPANY_NAME') }}</b></p>
            <p style="text-align: center; margin-top: 0;">MUTUÁRIO</p>
        </div>
    </div>
</div>
<script>window.onload = function () {
        window.print();
    }
</script>
</body>
</html>