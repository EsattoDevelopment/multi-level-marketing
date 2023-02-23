<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

return [
    'status'          => [
        'ativacao_pendente' => 0,
        'ativo'             => 1,
        'inativo'           => 2,
    ],
    'tipo_pacote'     => [
        '1' => 'Link Setup',
        '2' => 'Credencial Agente',
        '3' => 'Credencial Agência',
        '4' => 'Combo Cred.Agente+Link',
        '5' => 'Itens Diversos',
    ],
    'meses'           => [
        '01' => 'Janeiro',
        '02' => 'Fevereiro',
        '03' => 'Março',
        '04' => 'Abril',
        '05' => 'Maio',
        '06' => 'Junho',
        '07' => 'Julho',
        '08' => 'Agosto',
        '09' => 'Setembro',
        '10' => 'Outubro',
        '11' => 'Novembro',
        '12' => 'Dezembro',
    ],
    'estado_civil'    => [
        '0' => 'Nada consta',
        '1' => 'Solteiro',
        '2' => 'Casado',
        '3' => 'União estável',
        '4' => 'Divorciado(a)',
        '5' => 'Viuvo(a)',
    ],
    'contrato_status' => [
        '1' => 'Aguardando liberação',
        '2' => 'Em aberto',
        '3' => 'Pausado (verificar motivo)',
        '4' => 'Cancelado fora do prazo',
        '5' => 'Aguardando Finalização',
        '6' => 'Finalizado',
        '7' => 'Cancelado dentro do prazo',
    ],
    'tipo_atendimento' => [
        1 => 'Exames',
        2 => 'Consulta',
        3 => 'Retorno',
        4 => 'Sessão',
        5 => 'Fisioterapia',
        6 => 'Procedimento',
    ],
    'mes_remessa' => [
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 'O',
        11 => 'N',
        12 => 'D',
    ],
    'videos_categorias' =>[
       // 1 => 'Rentabilidade',
        2 => 'Institucional',
    ],
    'videos_tipos' =>[
        0 => 'URL',
        1 => 'YouTube',
        2 => 'vimeo',
    ],
    'status_transferencia' => [
        1 => 'Em liquidação',
        2 => 'Efetivada',
        3 => 'Cancelada',
    ],
    'modo_recontratacao_automatica' => [
        'abortada_sem_saldo' => -1, //Recontratacao abortada por falta de saldo
        'desativado' => 0, //Desativada
        'valor_contrato' => 1, //Valor do contrato
        'saldo_final_contrato' => 2, //Valor do saldo final do contrato
    ],
    'modo_recontratacao_automatica_exibicao' => [
        'Recontratacao abortada por falta de saldo' => -1, //Recontratacao abortada por falta de saldo
        'Apenas finaliza o contrato' => 0, //Desativado
        'Será gerado um novo contrato com o mesmo valor' => 1, //Valor do contrato
        'Será gerado um novo contrato com o saldo final' => 2, //Valor do saldo final do contrato
    ],
    'pagamento_bonus_tipo' =>[
        1 => 'Agente',
        2 => 'Setup',
        3 => 'Rendimentos',
    ],
];
