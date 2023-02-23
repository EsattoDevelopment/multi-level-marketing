<html>
<head>
    <title>Impressão de Contrato de Agente - {{ env('COMPANY_NAME')}}</title>
</head>
<body onload="window.print()" style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;">
<div style="margin-bottom: 30px;">
    <div style="float: left; width: 63%">
        <b style="font-size: 1.15em;">	FORMULÀRIO E CONTRATO DE AGENTE INDEPENDENTE</b> <br>
        <small style="font-size: 0.65em;">
            Atenção: Após o preenchimento, enviar em no máximo 15 dias o contrato original com 1 cópia do RG, CPF e comprovante de residência para:
            <b>	{{ env('COMPANY_NAME') }}, na Rua Padre João Crippa, 894 Centro - Campo Grande / MS</b>
        </small>
    </div>

    <div style="float: right; width: 25%; border: 1px solid #000000; padding: 5px;">#ID: <b>{{ $user->username }}</b></div>
    <div style="clear:both;"></div>
</div>

<div id="pessoal" style="border: 1px solid #000000; padding: 10px; line-height: 42px; font-size: 0.75em; margin-bottom: 20px;">
    NOME: <b>{{ $user->name }}</b> <br>
    RG: <b>{{ $user->rg ? $user->rg : '__________________________'}}</b>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  CPF: <b>{{ $user->cpf }}</b>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  DATA DE NASCIMENTO: <b>{{ $user->data_nasc }}</b>
</div>

<div id="pessoal2" style="padding: 10px; padding-left: 100px; line-height: 42px; font-size: 0.75em; margin-bottom: 20px;">
    Estado Civil: <b>{{ $user->estado_civil_extenso }}</b>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Filhos: <b>{{ $filhos->where('parentesco', 'Filhos')->count()}}</b>
    <br>

    @if($filhos->where('parentesco', 'Conjugê')->count() > 0)
        Nome Cônjuge: <b>{{ $filhos->where('parentesco', 'Conjugê')->first()->name }}</b>
    @else
        Nome Cônjuge:________________________________________________________________________________
    @endif
        <br>
    Nome Pai:____________________________________________________________________________________
    <br>
    Nome Mãe:___________________________________________________________________________________
</div>

<div id="pessoal3" style="border: 1px solid #000000; padding: 10px; line-height: 42px; font-size: 0.75em; margin-bottom: 20px;">
    CEP: <b>{{ $endereco->cep }}</b>
    <br>
    ENDEREÇO: <b>{{ $endereco->logradouro }}</b>, <b>{{ $endereco->numero }}</b>
    <br>
    BAIRRO: <b>{{ $endereco->bairro }}</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CIDADE: <b>{{ $endereco->cidade }}</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ESTADO: <b>{{ $endereco->estado }}</b>
    <br>
    TEL COMERCIAL: <b>{{ $endereco->telefone2 ?  $endereco->telefone2 : '__________________________' }}</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  TEL RESIDENCIAL: <b>{{ $endereco->telefone1 ?  $endereco->telefone1 : '__________________________' }}</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CELULAR: <b>{{ $endereco->celular ?  $endereco->celular : '__________________________' }}</b>
    <br>
    EMAIL: {{ $user->email }}
</div>

<div id="pessoal4" style="padding: 10px; padding-left: 100px; line-height: 42px; font-size: 0.75em; margin-bottom: 20px;">
    <strong>SE PESSOA JURIDÍCA:</strong> <br>
    RAZÃO SOCIAL: _________________________________________________________________________________
    <br>
    CNPJ: {{ strlen(trim($user->cnpj)) > 0 ? $user->cnpj :  '________________________________________________________________________________________' }}
</div>

<div id="pessoal" style="border: 1px solid #000000; padding: 10px; line-height: 42px; font-size: 0.75em; margin-bottom: 20px;">
    <strong>CO-APLICATE/SOCIO</strong> <br>
    Nome: _________________________________________________________________________________
    <br>
    RG: _________________________  CPF: __________________________  DATA DE NASCIMENTO: __________________________
</div>

<div id="pessoal" style="border: 1px solid #000000; padding: 10px; line-height: 42px; font-size: 0.75em; margin-bottom: 20px;">
    <strong>PATROCINADOR PESSOAL</strong> <br>
    NOME: <b>{{ $indicador->name }}</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TEL: <b>{{ $indicador->telefone1 ? $indicador->telefone1 : '__________________________' }}</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  ID#: <b>{{ $indicador->username }}</b>
</div>

<div id="obsservacao">
    <div style="width: 50%; float: left;">
        NIT/PIS/PASEP: ____________________________________________
    </div>
    <div style="width: 50%; float: left; font-size: 0.8em;">
        Para receber seus bônus, é necessário informar o NIT, PIS ou Pasep, anexando à sua documentação a cópia ou o comprovante de seu  NIT ou PIS/PASEP.
        Você pode  enviar uma cópia do seu cartão-cidadão. Caso não tenha esse cartão,
        acesse o site <a href="http://www.mps.gov.br/">www.mps.gov.br</a>, e nos envie a cópia da tela final em que o número é confirmado ou gerado
    </div>
</div>

<br>
<br>
<br>
<br>
<br>
<br>
<br>

<div id="assinaturas">
    <div style="float: left; width: 50%; text-align: left;">
        <div style="text-align: center; width: 100%;">
            ____________________________________________ <br>
            <small>Assinatura Titular</small>
        </div>
    </div>

    <div style="float: left; width: 50%; text-align: right;">
        <div style="text-align: center; width: 100%;">
            ____________________________________________ <br>
            <small>Assinatura Co-aplicate</small>
        </div>
    </div>
</div>

<br>
<br>
<br>
<br>
<br>
<br>
<br>

<b style="font-size: 1.1em;">TERMOS E CONDIÇÕES PARA AGENTE INDEPENDENTE</b>
<br>
<br>
<div style="width: 49%; float: left; text-align: justify; padding-right: 5px; font-size: 0.78em;">
    1. Na qualidade de Consultor Independente da Associação de Clínicas Solidarias e Sustentáveis {{ env('COMPANY_NAME') }} (“{{ env('COMPANY_NAME') }}”), CNPJ. 26.160.266/0001-95, tenho ciência e aceito as condições abaixo estabelecidas:
    <br> 2. Todo o Consultor Independente tem o direito de oferecer os benefícios da {{ env('COMPANY_NAME') }} a qualquer interessado, para sua comercialização de acordo com os termos e condições contidos no presente.
    <br> 3. Todo o Consultor Independente tem o direito de indicar e/ou custear pessoas que comercializarão os benefícios da {{ env('COMPANY_NAME') }}, desde que dentro dos padrões estabelecidos pelo programa, sem qualquer vínculo com a Associação de Clínicas {{ env('COMPANY_NAME') }}.
    <br> 4. Sendo o Consultor Independente qualificado adequadamente dentro das regras do Programa de Consultor Independente da {{ env('COMPANY_NAME') }}, terá o direito de receber bonificações a título de comissões, de acordo com o Plano de Negócio estabelecido pela {{ env('COMPANY_NAME') }}.
    <br> 5. Todo o Consultor Independente deverá apresentar a seus clientes os benefícios contidos na {{ env('COMPANY_NAME') }}, bem como plano de comercialização e remuneração autorizado pela {{ env('COMPANY_NAME') }}, que estão à disposição em material impresso e digital no site do Programa.
    <br> 6. O Consultor Independente deverá informar a seus clientes de maneira clara e objetiva quais são os benefícios contidos na {{ env('COMPANY_NAME') }}, e seus valores e regras sob pena de exclusão do sistema e do cadastro de Consultor Independente, bem como responder pelos prejuízos causados ao programa e aos clientes.
    <br> 7. Todo Consultor Independente contratado/aderente independente, e sempre será assim considerado para todos os fins, inclusive fiscais, não havendo qualquer relação de subordinação, de emprego, de sociedade, de representação legal ou franqueada da {{ env('COMPANY_NAME') }}.
    <br> 8. Todo o Consultor Independente será exclusiva e unicamente responsável pelo pagamento de todas as despesas relacionadas ao desenvolvimento do trabalho, incluindo, mas não limitado às despesas de viagem, alimentação, acomodação, secretarias, de escritório, telefonemas de longa distância e quaisquer outras despesas.
    <br> 9. Na qualidade de Consultor Independente declaro que, li, compreendi e concordo em cumprir todas as Normas e Procedimentos estabelecidos pelo Plano de Negócio e comercialização da {{ env('COMPANY_NAME') }}, que passarão a integrar os presentes Termos e Condições de Consultor Independente.
    <br>10.  O Consultor Independente deverá estar em situação regular ativa dentro do sistema da {{ env('COMPANY_NAME') }} para ter direito ao recebimento da bonificação estabelecida pelo programa, conforme as Normas e Procedimentos.
    <br>11.  Os presentes Termos e Condições de Consultor Independente, bem como as Normas e Procedimentos e o Plano de Negócio de desenvolvimento da comercialização dos benefícios da {{ env('COMPANY_NAME') }} poderão ser reestruturados e readequados a critério exclusivo da {{ env('COMPANY_NAME') }}, sendo que na qualidade de Consultor Independente concordo com as futuras alterações a serem realizadas. As reestruturações e/ou readequações futuras dos Termos e Condições de Consultor Independente, das Normas e Procedimentos e o Plano de Negócio de desenvolvimento da comercialização dos benefícios da {{ env('COMPANY_NAME') }}.
    <br>12.  Toda e qualquer alteração das Normas e Procedimentos e o Plano de Negócio de desenvolvimento da comercialização dos benefícios da {{ env('COMPANY_NAME') }} será realizada através de notificação eletrônica na pagina da internet {{ env('COMPANY_NAME') }}
    <a href="http://www.dominio-da-empresa.com.br/">www.dominio-da-empresa.com.br</a>, sendo que quaisquer alterações somente passarão a vigorar 30 (trinta) dias após a inclusão na pagina da internet da {{ env('COMPANY_NAME') }}, da referida notificação.
    <br>13. Caso os Consultores Independentes não concordem com qualquer reestruturação ou readequação das Normas e Procedimentos e o Plano de Negócio de desenvolvimento da comercialização dos benefícios da {{ env('COMPANY_NAME') }}, poderá rescindir o presente termo, sujeitando as normas de rescisão aqui estabelecidas.
    <br>14. O prazo de vigência do presente termo é de 12 (doze) meses, sujeito ao cancelamento antecipado conforme estabelecido nas Normas e Procedimentos.
    <br>15. Caso não haja renovação, por parte do Consultor Independente ou da Associação, do presente Termo ao final de 12 (doze) meses ou seu termo for rescindido por qualquer motivo, o Consultor Independente tem plena e total ciência que perderá definitivamente todos e quaisquer direitos decorrentes da qualidade de Consultor Independente, não tendo direito mais a comercialização dos benefícios estabelecidos na {{ env('COMPANY_NAME') }}, bem como a quaisquer bonificações ou benefícios sobre a equipe de vendas formada e organizada.
    <br>16.  Na hipótese de cancelamento, rescisão ou ausência de renovação, o Consultor Independente perderá todos e quaisquer direitos de comissionamento advindos da {{ env('COMPANY_NAME') }}, inclusive, todos e quaisquer direitos de relativos da organização da equipe abaixo anterior e a quaisquer bônus, ou outra bonificação resultantes das revendas e de outras atividades da equipe abaixo anterior.
</div>
<div style="width: 49%; float: left; text-align: justify; padding-left: 5px;font-size: 0.78em;">
    17. O Consultor Independente, bem como todo e qualquer membro de sua equipe deverá zelar pelo bom nome da {{ env('COMPANY_NAME') }}, respondendo por atos de negligência, imprudência e imperícia, bem como por qualquer informação equivocada que prestar em nome da {{ env('COMPANY_NAME') }}.
    <br>18. O Consultor Independente poderá cancelar o presente Contrato a qualquer tempo, e por qualquer motivo, através de uma notificação simples, por escrito, endereçada a {{ env('COMPANY_NAME') }}, no endereço de sua sede social, com antecedência mínima de 15 dias.
    <br>19. As atividades do Consultor Independente deverão ser exercidas preferencialmente pelo associado titular do contrato, todavia, poderá ser substituído por outro associado, mediante anuência da {{ env('COMPANY_NAME') }}.
    <br>20. Quaisquer espécies de manobra jurídica ou comercial que resultar na desqualificação da condição prevista no item 19 rescindirá o presente termo, bem como ternará o contrato estabelecido com terceiro de boa ou má fé nulo de pleno direito.
    <br>21. Todos os Consultores Independentes têm conhecimento que se houver o descumprimento dos Termos e Condições aqui estabelecidas, a {{ env('COMPANY_NAME') }}, poderá a seu único e exclusivo critério, impor uma ação disciplinar conforme previsto no Manual de Normas e Procedimento. Caso haja infringência ou descumprimento de quaisquer normas previstas no presente termo, bem como a qualquer documento oficial da {{ env('COMPANY_NAME') }}, ocorrerá a rescisão automática do presente termo, sem que o Consultor Independente tenha qualquer direito a receber bonificações futuros, tenham ou não as revendas relativas a tais bônus sido concluídos.
    <br>22. A {{ env('COMPANY_NAME') }}, diretores, empregados, cessionários e representantes (qualificados como coligados) não terão quaisquer responsabilidades sobre a atividade desenvolvida pelo Consultor Independente ou as pessoas por ele indicado. Por tais motivos, o Consultor Independente neste ato exime e exonera a {{ env('COMPANY_NAME') }}  bem como os coligados de toda e qualquer responsabilidade por danos imprevistos e multas exemplares, por qualquer reivindicação ou causa de pedir relativa ao presente Termo.
    <br>23. O Consultor Independente ainda, neste ato, exime e exonera a {{ env('COMPANY_NAME') }}  e seus coligados de toda e qualquer responsabilidade resultante ou relativa: (a) a qualquer violação pelo Consultor Independente deste Contrato ou das Normas e Procedimentos; (b) à promoção ou operação dos benefícios da {{ env('COMPANY_NAME') }}  pelo Consultor Independente e quaisquer atividades a ele relacionados, incluindo, mas não limitado à apresentação dos benefícios ou do plano de desenvolvimento de comercialização do {{ env('COMPANY_NAME') }} , à operação de um veículo motorizado, a locação de instalações para uma reunião ou treinamento, etc.), bem como a indenizar o {{ env('COMPANY_NAME') }}   por qualquer responsabilidade, danos, multas, penalidades ou outros; (c) a quaisquer dados ou informações incorretas fornecidos pelo Consultor Independente ou pessoas por ele indicadas, acerca do {{ env('COMPANY_NAME') }} ; (d) a falha pelo Consultor Independente ou pessoas por ele indicadas, em fornecer quaisquer informações ou dados necessários ao {{ env('COMPANY_NAME') }}  para a operação de seus negócios; ou (e) premiações resultantes de qualquer conduta não autorizada que eu assuma na operação dos meus negócios.
    <br>24. O presente Termo, no seu formato atual, bem como seus anexos existentes ou futuros constituem o acordo integral entre a {{ env('COMPANY_NAME') }} e o Consultor Independente signatário do presente. Quaisquer promessas, declarações, ofertas ou outras comunicações que não estejam expressamente previstos no termo não terão qualquer validade jurídica.
    <br>25. Se qualquer disposição do contrato for considerada inválida ou inexequível, a referida disposição deverá ser alternada somente na extensão necessária para torna-la exequível, e o remanescente do termo permanecerá em pleno vigor e efeito.
    <br>26. Se um Consultor Independente desejar ajuizar uma ação contra a {{ env('COMPANY_NAME') }}  por qualquer ato ou omissão relativo ou resultante do Contrato, a referida ação deverá ser ajuizada no prazo de um ano a contar da data da alegada conduta que acarretar a causa de pedir. A falha em ajuizar a mencionada ação no prazo previsto prescreverá todas as reivindicações contra a {{ env('COMPANY_NAME') }} pelo referido ato ou omissão. O Consultor Independente renuncia a todas as reivindicações de quaisquer outras prescrições aplicáveis.
    <br>27. Neste ato o Consultor Independente signatário autoriza a {{ env('COMPANY_NAME') }}  a utilizar nome, fotografia, histórico pessoal e/ou similares em matérias de propaganda ou promocionais e renuncio a todas as reivindicações por remuneração para tal uso.
    <br>28. O referido contrato não gera vínculo trabalhista entre as partes, sendo que qualquer discussão em relação às atividades deverá ser discutida no âmbito cível.
    <br>29. Quaisquer dúvidas ou questões judiciais oriundas do presente contrato deverão ser resolvidas no foro central da Comarca da Capital do Estado de Mato Grosso do Sul, Campo Grande.
    <br>30. O presente termo possui 2 (duas) vias distribuídas por 01 (uma) paginas e 30 (trinta) itens.
</div>

<div style="clear:both;"></div>
<br>
<br>
<p style="font-size: 0.7em;">Uma via original assinada juntamente com uma cópia do RG, CPF e Comprovante de Residência deverá ser enviada no prazo máximo de 15 dias para Associação de Clinicas Solidaria e Sustentáveis {{ env('COMPANY_NAME') }} Ltda. Rua Padre João Crippa, 894 – Centro – Cep. 79.002-172 – Campo Grande / MS.
</p>

</body>
</html>
