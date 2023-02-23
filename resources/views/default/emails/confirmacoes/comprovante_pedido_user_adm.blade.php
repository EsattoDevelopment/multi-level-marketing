@extends('default.emails.confirmacoes.layout')

@section('preheader')
<!-- start preheader -->
<div class="preheader" style="display: none; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;">
    Nova transferência bancária, verifique o comprovante no sistema para aprovar a transferência.
</div>
<!-- end preheader -->
@endsection

@section('content')
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
                    <p style="margin: 0;">Nova transferência bancária - Pedido <b>#{{ $pedido }}</b>, <b>{{ $nome_usuario }}</b>.</p>
                    <p style="margin: 0;">Verifique o comprovante no sistema para aprovar a transferência.</p>
                    <p style="width: 50%; border-radius: 6px; background: #ae903e">
                        <a href="{{ route('pedido.show', [$pedido]) }}" target="_blank" style="display: inline-block; padding: 16px 36px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; color: #ffffff; text-decoration: none; border-radius: 6px;">Verificar</a>
                    </p>
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