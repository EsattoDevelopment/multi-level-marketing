@extends('default.emails.confirmacoes.layout')

@section('preheader')
<!-- start preheader -->
<div class="preheader" style="display: none; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;">
  Esta é uma notificação de solicitação de transferência realizado em sua conta {{ env('COMPANY_NAME', 'Nome empresa') }}. Você está recebendo este e-mail para garantir que a operação foi feito por você.
</div>
<!-- end preheader -->
@endsection

@section('content')
<!-- start hero -->
<tr>
    <td align="center" bgcolor="#14284b">
        <!--[if (gte mso 9)|(IE)]>
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
            <tr>
                <td align="center" valign="top" width="600">
        <![endif]-->
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
            <tr>
                <td align="center" bgcolor="#ffffff" style="padding: 36px 24px 0; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; border-top: 3px solid #ae903e;">
                    <h1 style="margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -1px; line-height: 48px;">Olá {{ ucfirst(explode(' ', $user->name)[0]) }},</h1>
                </td>
            </tr>
        </table>
        <!--[if (gte mso 9)|(IE)]>
        </td>
        </tr>
        </table>
        <![endif]-->
    </td>
</tr>
<!-- end hero -->

<!-- start copy block -->
<tr>
    <td align="center" bgcolor="#14284b">
        <!--[if (gte mso 9)|(IE)]>
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
            <tr>
                <td align="center" valign="top" width="600">
        <![endif]-->
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">

            <!-- start copy -->
            <tr>
                <td align="center" bgcolor="#ffffff" style="padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">

                    @if($transferencia->destinatario_user_id)
                        <p style="margin: 0;"><b>Solicitação de transferência entre contas {{ ucfirst(env('COMPANY_NAME_SHORT', 'empresa')) }}:</b></p>

                        <p>
                            <b>Valor:</b> {{ mascaraMoeda('R$', $transferencia->valor, 2, true) }}
                        </p>
                        <p>
                            <b>Nome</b>: {{ $transferencia->destinatario->name }} <br>
                            <b>CPF/CNPJ</b>: {{ $transferencia->destinatario->cpf }}
                            <b>Agência</b>: 0001 <br>
                            <b>Conta</b>: {{ $transferencia->destinatario->conta }}
                        </p>
                    @else
                        <p style="margin: 0;"><b>Solicitação de transferência para outros bancos de</b></p> <br>
                        <h1>{{ mascaraMoeda('R$', $transferencia->valor, 2, true) }}</h1>
                        <p>
                            para <br>

                            {!! $transferencia->conta->dados !!}
                        </p>
                    @endif
                    <p>Endereço de IP: {{ $ip }} <b></b></p>
                    <p>{{ Date::now()->format('l j F Y H:i:s') }}</p>
                    <p>Se não foi você que solicitou a transferência, altere sua senha imediatamente e fale com nossa equipe pelo link: <a href="{{ $empresa->site }}" target="">{{ $empresa->site }}</a></p>
                    <p style="margin-bottom: 0;">Atenciosamente,</p>
                    <p style="margin: 0;"><b>{{ env('COMPANY_NAME') }}</b></p>
                </td>
            </tr>
            <!-- end copy -->

        </table>
        <!--[if (gte mso 9)|(IE)]>
        </td>
        </tr>
        </table>
        <![endif]-->
    </td>
</tr>
<!-- end copy block -->
@endsection

@section('prefooter')
<!-- start permission -->
<tr>
    <td align="center" bgcolor="#14284b" style="padding: 12px 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 12px; line-height: 20px; color: #FFF;">
        <p style="margin: 0;"><b>Atenção:</b> Este é um e-mail automático, favor não respondê-lo.</p>
    </td>
</tr>
<!-- end permission -->
@endsection
