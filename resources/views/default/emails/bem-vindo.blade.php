@extends('default.emails.layout')

@section('preheader')
<!-- start preheader -->
<div class="preheader" style="display: none; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;">
    Bem vindo(a) {{ ucfirst(explode(' ', $dados->name)[0]) }}! Estamos muito felizes que você faça parte da {{ env('COMPANY_NAME', 'empresa') }} Capital.
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
                    <h1 style="margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -1px; line-height: 48px;">Bem vindo(a) {{ ucfirst(explode(' ', $dados->name)[0]) }},</h1>
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
                    <p style="margin: 0;">Estamos muito felizes em tê-lo(a) conosco na <b>{{ env('COMPANY_NAME', 'empresa') }}</b>, falta apenas alguns passos para você concluir seu cadastro para poder utilizar plenamente a sua carteira.</p>
                    <p>
                        <b>após a aprovação completa de seus documentos, os dados de sua carteira serão</b>:
                        <br>
                        <b>Agência</b>: 0001 <br>
                        <b>Conta</b> : {{ $dados->conta }}
                        <br>
                        <br>
                    <b>Proxímos passos:</b>
                    <ul style="text-align: left;">
                        <li>1. Termine o cadastro dos seus dados pessoais</li>
                        <li>2. Preencha os seus dados de endereço</li>
                        <li>3. Envie seus documentos para confirmação em <br> <b>Menu > Dados Cadastrais > Enviar documentos</b></li>
                        <li>4. Habilite a autenticação de 2 fatores para sua segurança <br> <b>Menu > Dados Cadastrais > Segurança</b></li>
                    </ul>
                    <br>
                    </p>
                    <p style="width: 50%; border-radius: 6px; background: #ae903e">
                        <a href="{{ route('home') }}" target="_blank" style="display: inline-block; padding: 16px 36px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; color: #ffffff; text-decoration: none; border-radius: 6px;">Acessar</a>
                    </p>
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