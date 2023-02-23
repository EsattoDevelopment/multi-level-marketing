<html>
<head>
    <title>Impressão de Contrato - {{ env('COMPANY_NAME') }}</title>
</head>
<body onload="window.print()" style="font-size: 0.81em;">
<div style="width: 100%; text-align: center;">
    <strong>CONTRATO DE ASSOCIADO CONTRIBUINTE</strong>
</div>

<br>

<p>
    <b>CONTRATADA: {{ env('COMPANY_NAME') }}, PREVENÇÃO EM SAÚDE</b> – Campo Grande –MS, inscrita no CNPJ sob. nº
    26.160.266/0001-95 com sede a rua Padre João Crippa, nº 894, neste ato denominada <b>CONTRATADA</b>
</p>

<p>
    <b>CONTRATANTE: {{ mb_strtoupper($user->name) }}</b>, estado civil {{ mb_strtoupper(config('constants.estado_civil')[$user->estado_civil]) }}, portador do RG Nº {{ $user->rg }}, e inscrito no CPF nº
    {{ $user->cpf }}, residente e domiciliado na {{ $endereco->logradouro }}, nº. {{ $endereco->numero }}, CEP {{ $endereco->cep }} em {{ $endereco->cidade }}-{{ $endereco->estado }}, neste ato
    definido como Associado Aderente, denominado ASSOCIADO.
</p>

<br>

<div style="float: left; width: 340px; margin-right: 15px; text-align: justify;">
    <div style="width: 100%; text-align: center;">
        <strong>DO OBJETO</strong>
    </div>

    <p>
        <b>CLÁUSULA 1ª A CONTRADA</b> oferecerá ao <b>ASSOCIADO</b> benefícios contidos nos <b>{{ $item->name }}</b> conforme descrito no <b>ANEXO I</b>, também oferecerá consultas, exames e serviços na área da
        saúde em rede própria e/ou rede credenciada com diferenciais conforme tabelas pré-definidas para o
        <b>ASSOCIADO</b>.
    </p>

    <p>
        <b>PARAGRAFO PRIMEIRO: A CONTRATADA</b> disponibilizará sua rede própria primeiramente para atender seus
        associados onde serão realizadas as consultas, exames e demais serviços para os titulares e dependentes.
    </p>

    <p>
        <b>PARAGRAFO SEGUNDO:</b> Os demais procedimentos fora da rede própria da CONTRATADA serão negociados via tabela
        individual com cada profissional, clínica e hospital, devendo o <b>ASSOCIADO</b> pagar diretamente no local do
        atendimento.
    </p>

    <p>
        <b>PARAGRAFO TERCEIRO:</b> Carências para atendimentos dos pacotes de prevenção nas consultas e exames estão
        descritos no <b>ANEXO I</b>.
    </p>

    <p>
        <b>PARÁGRAFO QUARTO:</b> Das consultas e exames e demais serviços disponibilizados ao <b>ASSOCIADO</b> estes
        serão agendados pelo <b>CALL CENTER</b>.
    </p>

    <p>
        <b>PARÁGRAFO QUINTO:</b> Pode ser incluído como dependente do <b>ASSOCIADO</b>, exclusivamente, cônjuge, e
        descendentes diretos solteiros de ate 21 (vinte um) anos de idade.
    </p>

    <div style="width: 100%; text-align: center;">
        <strong>DO VALOR E FORMA DE PAGAMENTO</strong>
    </div>

    <p>
        <b>CLÁUSULA 2ª O ASSOCIADO</b> pagará para <b>CONTRATADA</b> a adesão e a contribuição associativa referente a
        {{ $item->qtd_parcelas }} meses de associação no valor de {{ $sistema->moeda }} {{ mascaraMoeda($sistema->moeda, $item->qtd_parcelas * $item->vl_parcelas, 2, true) }}
        (@numToTxt(mascaraMoeda($sistema->moeda, $item->qtd_parcelas * $item->vl_parcelas, 2, true), true)),
        podendo ser paga à vista e/ou em parcelas.
    </p>

    <p>
        <b>PARÁGRAFO PRIMEIRO:</b> O valor da adesão deste contrato é de {{ $sistema->moeda }} {{ mascaraMoeda($sistema->moeda, $item->valor, 2, ',', '.') }} (@numToTxt(number_format($item->valor, 2, true), true)), devendo ser paga no ato de sua assinatura.
    </p>

    <p>
        <b>PARÁGRAFO SEGUNDO:</b> No caso de pagamento mensal <b>ASSOCIADO</b> pagará o valor de {{ $sistema->moeda }} {{ mascaraMoeda($sistema->moeda, $item->vl_parcelas, 2, ',', '.') }} (@numToTxt(number_format($item->vl_parcelas, 2, true), true)),
            com o vencimento no dia {{ explode('/', $contrato->dt_parcela)[0] }} (@numToTxt(explode('/', $contrato->dt_parcela)[0], false)) de cada mês.
    </p>

    <p>
        <b>PARÁGRAFO TERCEIRO:</b> O pagamento poderá ser realizado por meio de cartão de credito, débito e/ou fatura
        bancária.
    </p>

    <p>
        <b>PARÁGRAFO QUARTO:</b> As contribuições associativas serão reajustadas na renovação do contrato.
    </p>
</div>
<div style="float: left; width: 340px; text-align: justify;">
    <p>
        <b>PARÁGRAFO QUINTO:</b> Havendo pagamento do valor em parcela única ou em doze vezes no cartão de crédito ou
        ainda com inserção em débito automático o <b>ASSOCIADO</b> terá 5% de desconto.
    </p>

    <p><b>PARÁGRAFO SEXTO:</b> Havendo inclusão de dependente será cobrado um taxa adicional para emissão do cartão de
        identificação do mesmo.
    </p>
    <p>
        <b>PARÁGRAFO SÉTIMO:</b> na ausência do pagamento por mais de 30 (trinta) dias será suspenso o atendimento.
        Sendo atingido mais de 60 (sessenta) dias, o <b>ASSOCIADO</b> e eventual dependente terão o contrato
        automaticamente rescindido, com o envio dos dados cadastrais aos órgãos de proteção ao crédito.

    </p>

    <div style="width: 100%; text-align: center;">
        <strong>DAS OBRIGAÇÕES DA CONTRATADA</strong>
    </div>

    <p>
        <b>CLÁUSULA 3ª A CONTRATADA</b> disponibilizará rede credenciada de assistência médica e serviços
        correlacionados
        para o <b>ASSOCIADO</b>, de modo participativo.
    </p>
    <p>
        <b>PARÁGRAFO ÚNICO:</b> Os valores dos atendimentos serão disponibilizados pela própria rede credenciada, sendo
        o
        pagamento realizado no momento do atendimento.
    </p>
    <p>
        <b>CLÁUSULA 4ª A CONTRATADA</b> fornecerá cartão de identificação ao <b>ASSOCIADO</b>, bem como o CALL CENTER
        para o agendamento das consultas, exames e outros.
    </p>
    <p>
        <b>PARÁGRAFO ÚNICO:</b> o cartão de identificação do <b>ASSOCIADO</b> deverá ser retirado na sede da <b>CONTRATADA</b>.
    </p>
    <br>
    <div style="width: 100%; text-align: center;">
        <strong>OBRIGAÇÕES DO ASSOCIADO</strong>
    </div>
    <br>
    <p>
        <b>CLÁUSULA 5ª</b> Compete ao <b>ASSOCIADO</b>, o pagamento das contribuições associativas na data do
        vencimento.
    </p>
    <p>
        <b>PARÁGRAFO ÚNICO:</b> O atraso no pagamento de qualquer obrigação pecuniária prevista neste instrumento
        acarretará a imposição de multa moratória de 2% (dois por cento) do valor devido, atualizado pela variação
        acumulada do IGP-M, acrescido de juros de mora à razão de 1% ao mês.
    </p>
    <p>
        <b>CLÁUSULA 6ª</b> Compete ao <b>ASSOCIADO</b>, apresentar à rede credenciada o <b>CARTÃO DE IDENTIFICAÇÃO</b>
        no
        prazo de validade bem como a guia de <b>AUTORIZAÇÃO</b> para o atendimento.
    </p>
    <p>
        Parágrafo primeiro: Havendo perda ou extravio do <b>CARTÃO DE IDENTIFICAÇÃO</b>, o <b>ASSOCIADO</b>, deverá
        comunicar
    </p>
</div>
<div style="clear: both;"></div>
<br>
<div style="float: left; width: 340px; margin-right: 15px; text-align: justify;">
    <p>
        imediatamente a central de atendimento, para ser efetivado o cancelamento e substituição.
    </p>
    <p>
        <b>Paragrafo segundo</b>: a utilização indevida do CARTÃO DE IDENTIFICAÇÃO é de inteira responsabilidade do <b>ASSOCIADO</b>.
    </p>

    <div style="width: 100%; text-align: center;">
        <strong>DISPOSIÇÕES GERAIS</strong>
    </div>

    <p>
        <b>CLÁUSULA 7ª</b> O ASSOCIADO, terá liberdade na escolha dos profissionais para atendimento, desde que dispostos na rede
        credenciada disponibilizada pela <b>CONTRATADA</b>.
    </p>

    <p>
        <b>CLÁUSULA 8ª</b> Com exceção aos procedimentos contidos no <b>ANEXO I</b>, não existe atendimentos gratuitos ou cobertos pela
        anuidade ou contribuição associativa paga, sendo que em rede credenciadas as despesas do <b>ASSOCIADO</b>, com uso do
        sistema
        será de sua exclusiva responsabilidade, devendo o pagamento ser efetivado diretamente ao prestador de serviço.
    </p>

<p>
    <b>CLÁUSULA 9ª</b> Os preços a serem pagos pelo <b>ASSOCIADO</b> à rede credenciada, serão negociados pela <b>CONTRATADA</b>.
</p>
    <p>
        <b>Parágrafo único:</b> A tabela de valores estará à disposição do <b>ASSOCIADO</b>, no momento do agendamento, e poderá sofrer
        modificação sem prévio aviso.
    </p>

    <p>
        <b>CLÁUSULA 10ª</b> Os benefícios assegurados neste contrato serão automaticamente suspensos, sem incidências de
        mensalidades,
        nos casos de calamidades públicas, catástrofes, epidemias, guerras, forças maior, por norma legal, greve de
        profissionais de saúde e desconstituição da rede credenciada.
    </p>

    <p>
        PARAGRAFO ÚNICO: Cessado a disponibilização do sistema, por qualquer motivo o ASSOCIADO ficará isento de pagar as
        mensalidades.
    </p>

    <p>
        <b>CLÁUSULA 11ª O ASSOCIADO</b> e seus beneficiários serão atendidos diretamente pelos credenciados da rede da <b>CONTRATADA</b>,
        e
        não haverá reembolso de qualquer despesa
    </p>
</div>
<div style="float: left; width: 340px; text-align: justify;">
    <p>
        pagas pelo <b>ASSOCIADO</b>, salvo se previa e expressamente autorizado pela
        <b>CONTRATADA</b>.
    </p>

    <p>
        <b>CLÁUSULA 12ª</b> O <b>ASSOCIADO</b> declara que assinando o presente contrato, passará a ser associado da associação
        <b>CONTRATADA</b>,
        devendo respeitar as disposições estatutárias.
    </p>

    <div style="width: 100%; text-align: center;">
        <strong>DA RESCISÃO</strong>
    </div>

    <p>
        <b>CLÁUSULA 13ª</b> O presente contrato poderá ser rescindido pela <b>CONTRATADA</b> e pelo <b>ASSOCIADO</b> a qualquer momento, desde
        que
        seja efetuado com prévia notificação de 30 (trinta) dias.
    </p>

    <p>
        <b>CLÁUSULA 14ª</b> O contrato será rescindido automaticamente caso o <b>ASSOCIADO</b> deixe de pagar as mensalidades pelo período
        superior a 60 (sessenta) dias.
    </p>

<p>
    <b>CLÁUSULA 15ª</b> Rescindido o contrato, o <b>ASSOCIADO</b> perderá o direito de utilizar o sistema.
</p>
    <p>
        <b>CLÁUSULA 16ª</b> No cancelamento ou rescisão do contrato, não haverá reembolso de valores pagos ao <b>ASSOCIADO</b>.
    </p>
    <p>
        <b>PARAFRAGO ÚNICO:</b> A não utilização dos serviços contidos no benefício contratado pelo <b>ASSOCIADO</b>, não dará em hipótese
        alguma direito a reembolso ou devolução em caso de não utilização, considerando que o pagamento corresponde pela
        disponibilidade da rede própria e credenciada da <b>CONTRATADA</b>.
    </p>

    <div style="width: 100%; text-align: center;">
        <strong>FORO DE ELEIÇÃO</strong>
    </div>

    <p>
        <b>CLÁUSULA 16ª</b> As partes elegem, neste ato, o foro da Comarca de Campo Grande/MS, para dirimir os conflitos de
        direitos,
        renunciando a qualquer outro por mais privilegiado que seja.
    </p>

    <p>
        E, por assim estarem justos e acertados, firmam o presente contrato em duas vias de igual forma e teor, e na
        presença de
        testemunhas abaixo arroladas.
    </p>
</div>

<div style="clear: both;"></div>
<br>
<div>
    <p style="text-align: right;">Campo Grande/MS, {{ $contrato->dt_inicio_impressao }}.</p>

    <style>
        #assinatura{
            position: relative;
            font-size: 0.9em;
        }

        #assinatura img{
            position: absolute;
        }

        #assinatura #primeiro{
            top: -12px;
            clip: rect(0px,191px,41px,0px);
        }

        #assinatura #segundo{
            clip: rect(0px,191px,34px,0px);
            top: -4px;
            left: 235px;
        }

    </style>
    <div id="assinatura">
        <img id="primeiro" src="{{ asset('images/contrato/image002.png') }}" alt="">
        <img id="segundo" src="{{ asset('images/contrato/image001.png') }}" alt="">
        <br>
        <br>
        <b>______________________________________________________________________________</b> <br>
        <b>CONTRATADA: {{ env('COMPANY_NAME') }}, PREVENÇÃO EM SAÚDE</b>
    </div>

    <br><br>
    <br><br>

    <b>______________________________________________________________________</b> <br>
    <b>CONTRATANTE: {{ $user->name }}</b>
    <br>
    <br>
    <b>Testemunhas:</b>
    <br>
    <br>
    <br>

    <style>
        #nome-cpf #primeiro, #nome-cpf #segundo{
            width: 50%;
            float: left;
        }
    </style>
    <div id="nome-cpf">
        <div id="primeiro">
            Nome: <br>
            CPF:
        </div>

        <div id="segundo">
            Nome: <br>
            CPF:
        </div>
    </div>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <div style="width: 100%; text-align: center;">
        <strong>ANEXO I</strong>
    </div>
    <br>
    <br>
    <div style="padding-left: 30px;">
        <b><u>Serviços oferecidos para o associado pelos parceiros:</u></b>

        <ul style="margin-left: 0px;">
            <li>Consulta;</li>
            <li>Exames Clínicos;</li>
            <li>Exames Laboratoriais;</li>
            <li>Atendimento Odontológico;</li>
            <li>Desconto Medicamentos;</li>
        </ul>
        <br>
        <br>
        <b>
            Todo serviços oferecido para o associado da {{ env('COMPANY_NAME') }}, devem ser pagos diretamente para o parceiro conforme
            tabela pré-determinada junto aos mesmos.
        </b>
    </div>

    <br>
    <br>


    Pacotes preventivos subsidiados 100% anualmente conforme classificação:
    <br>
    <br>
    {!! $item->descricao_impressao !!}
    {{--<b><u>ASSOCIADO PLATINUM:</u></b>
    <br>
    <br>

    <p>
        <b>Exames Laboratoriais e clínicos:</b> hemograma, glicose, ácido úrico, colesterol total, triglicerídeos, tsh, urina tipo i,
        fezes, ureia, creatinina, potássio, sódio, cálcio Iônico, magnésio, tap, coagulograma, TGO, TGP, ASLO, VHS, PSA Total,
        Citologia e Bioimpedanciometria.
    </p>

    <br>

    <b>Assistência funeral</b>: 1 (um) atendimento anual para o titular não acumulativo no valor de {{ $sistema->moeda }} 3.000,00.
--}}
    <br>
    <br>
    <br>
    <br>

    <div style="width: 100%; font-size: 8em; padding: 20% 0; border: dashed 2px #000000; border-radius: 20px; text-align: center;">
        EM BRANCO
    </div>
</div>
<script>

</script>
</body>
</html>