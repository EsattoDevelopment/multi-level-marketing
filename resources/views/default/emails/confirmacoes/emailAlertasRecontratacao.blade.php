@extends('default.emails.confirmacoes.layout')

@section('preheader')
<!-- start preheader -->
<div class="preheader" style="display: none; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;">
        @if($dados->diasParaRecontratacao == 1)
            Olá {{ $dados->nomeUsuario }}! Parabéns! amanhã o seu contrato {{ $dados->nomeContrato }} Nº {{ $dados->idContrato }} será quitado e finalizado!
        @else
            Olá {{ $dados->nomeUsuario }}! Parabéns! em {{ $dados->diasParaRecontratacao }} dias o seu contrato {{ $dados->nomeContrato }} Nº {{ $dados->idContrato }} será quitado e finalizado!
        @endif
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
                    <h1 style="margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -1px; line-height: 48px;">Olá {{ $dados->nomeUsuario }},</h1>
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

                    @if($dados->diasParaRecontratacao == 1)
                        <p style="margin: 0;">Parabéns! Amanhã o seu contrato <b>{{ $dados->nomeContrato }}</b> Nº <b>{{ $dados->idContrato }}</b> será quitado e finalizado!</p>
                        <br/>
                        {{--<p style="margin: 0;font-size: 11px">Você ativou a opção de GERAR NOVO CONTRATO AUTOMATICAMENTE e com isto, amanhã nós iremos gerar um novo contrato pra você, no mesmo valor @if($dados->modoRecontratacaoAutomatica == config('constants.modo_recontratacao_automatica')['saldo_final_contrato']) total final @endif deste anterior! (condicionado à sua carteira ter saldo disponível para isto)</p>--}}
                        <p style="margin: 0;font-size: 11px">Você ativou a opção de NOVO CONTRATO AUTOMÁTICO e com isto, <b>amanhã</b> um novo contrato no valor de <b>{{$dados->valorRecontratacao}}</b> será criado pra você! (condicionado à sua carteira ter disponibilidade de saldo)</p>
                    @else
                        <p style="margin: 0;">Parabéns! Em <b>{{ $dados->diasParaRecontratacao }}</b> dias o seu contrato <b>{{ $dados->nomeContrato }}</b> Nº <b>{{ $dados->idContrato }}</b> será quitado e finalizado!</p>
                        <br/>
                        {{--<p style="margin: 0;font-size: 11px">Você ativou a opção de GERAR NOVO CONTRATO AUTOMATICAMENTE e com isto, em {{ $dados->diasParaRecontratacao }} dias nós iremos gerar um novo contrato pra você, no mesmo valor @if($dados->modoRecontratacaoAutomatica == config('constants.modo_recontratacao_automatica')['saldo_final_contrato']) total final @endif  deste anterior! (condicionado à sua carteira ter saldo disponível para isto)</p>--}}
                        <p style="margin: 0;font-size: 11px">Você ativou a opção de NOVO CONTRATO AUTOMÁTICO e com isto, em <b>{{ $dados->diasParaRecontratacao }}</b> dias um novo contrato no valor de <b>{{ $dados->valorRecontratacao }}</b> será criado pra você! (condicionado à sua carteira ter disponibilidade de saldo)</p>
                    @endif



                    <p style="width: 50%; border-radius: 6px; background: #ae903e">
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
