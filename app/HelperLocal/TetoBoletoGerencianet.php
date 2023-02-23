<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Carbon\Carbon;
use App\Models\DadosPagamento;
use App\Models\MetodoPagamento;

function limitesBoleto($metododoPagamento, $valorBoleto = 0)
{
    $limites = [];
    $dadosBoleto = MetodoPagamento::where('id', $metododoPagamento)->first();
    $limiteDiario = $dadosBoleto->configuracao['limite_diario'] ?? 0;
    $limiteMensal = $dadosBoleto->configuracao['limite_mensal'] ?? 0;

    $limiteDiarioUsado = DadosPagamento::whereDate('data_geracao_boleto', '=', $data = Carbon::now()->format('Y-m-d'))
        ->where('metodo_pagamento_id', $metododoPagamento)
        ->whereIn('status', [2, 4])
        ->sum('valor');
    $limiteMensalUsado = DadosPagamento::whereDate('data_geracao_boleto', '>=', Carbon::now()->firstOfMonth()->format('Y-m-d'))
        ->whereDate('data_geracao_boleto', '<=', Carbon::now()->lastOfMonth()->format('Y-m-d'))
        ->where('metodo_pagamento_id', $metododoPagamento)
        ->whereIn('status', [2, 4])
        ->sum('valor');

    $limiteDiarioUsado = $limiteDiarioUsado == null ? 0 : $limiteDiarioUsado;
    $limiteMensalUsado = $limiteMensalUsado == null ? 0 : $limiteMensalUsado;

    $limiteDiarioDisponivel = 0;
    $limiteMensalDisponivel = 0;

    if ($limiteMensal >= $limiteMensalUsado) {
        $limiteMensalDisponivel = $limiteMensal - $limiteMensalUsado;
    }

    if ($limiteDiario >= $limiteDiarioUsado) {
        $limiteDiarioDisponivel = $limiteDiario - $limiteDiarioUsado;
    }

    $tarifaBoleto = $dadosBoleto->configuracao['tarifa_boleto'] ?? 0;
    $valorMaximoBoleto = $dadosBoleto->configuracao['limite_geracao_boleto'] ?? 0;
    $limites['limiteboleto'] = $valorMaximoBoleto;
    $valorMaximoBoleto = $valorMaximoBoleto + $tarifaBoleto; //somo com a taxa, pois ela compoe o valor final do boleto

    $valorBoleto = $valorBoleto + $tarifaBoleto;
    if ($valorBoleto > $valorMaximoBoleto) {
        $limites['emitirboleto'] = false;
    } else {
        if (($valorBoleto <= $limiteDiarioDisponivel) && ($valorBoleto <= $limiteMensalDisponivel)) {
            $limites['emitirboleto'] = true;
        } else {
            $limites['emitirboleto'] = false;
        }
    }

    $limites['limiteMensalDisponivel'] = $limiteMensalDisponivel;
    $limites['limiteMensalUsado'] = $limiteMensalUsado;
    $limites['limiteDiarioDisponivel'] = $limiteDiarioDisponivel;
    $limites['limiteDiarioUsado'] = $limiteDiarioUsado;

    return $limites;
}
