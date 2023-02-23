<div style="border: 1px solid #000000; padding: 10px; margin:auto 0;">
    <div style="margin: auto 0;">
        <a href="http://www.galaxyclube.com.br/">
            <img style="max-width: 50%" src="http://localhost:8000/logo-galaxy.png" alt="Logo">
        </a>
    </div>

    <br>
    <strong>Parabéns</strong>, você acaba de realizar um reserva para: <i>
        {{ $reserva->getRelation('pacote')->chamada }}
    </i>
    <br>
    <br>
    <div>
        Data viagem: {{ $reserva->data_ida }}
        <br>
        Data Volta: {{ $reserva->data_volta }}
        <br>
        Tipo de acomodação: {{ $reserva->getRelation('acomodacao')->name }}
        <br>
        Custo em GMilhas: {{ mascaraMoeda($sistema->moeda, $reserva->valor_milhas_dia_compra, 0, true) }}
    </div>
    <strong>Voucher do sistema Galaxy:</strong> <span style="color:#0000ff; font-size:24px;">{{ $reserva->voucher }}</span>

    <br>
    <br>

    Nossa equipe entrará em contato com você para prosseguir com a reserva.
    <br>
    <br>
    Por favor responda este e-mail com os dados das pessoas que utilizarão o pacote para podermos agilizar o processo. <br>

    Nome ,Data nascimento, RG e CPF .


    <br>
    <br>

    Entre contato conosco para qualquer dúvida.

    <br>
    <br>
    Equipe Galaxy Clube <br>
    <a href="mailto:contato@galaxyclube.com.br">contato@galaxyclube.com.br</a>
</div>