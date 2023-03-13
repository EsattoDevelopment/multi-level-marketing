<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sistema extends Model
{
    protected $table = 'configuracao_sistema';

    protected $fillable = [
        'sistema_viagens', //sim = 1, não = 0
        'bonus_milha_cadastro',
        'bonus_ciclo_hotel',
        'milhas_ciclo_hotel',
        'validade_milhas_ciclo_hotel',

        'diretos_qualificacao', //sem uso
        'profundidade_unilevel', //sem uso

        'sistema_saude', //sim = 1, não = 0

        'paga_bonus_diario_titulo', //sim = 1, não = 0
        'paga_bonus_diario_item', //sim = 1, não = 0

        'matriz_unilevel', //sim = 1, não = 0
        'matriz_fechada', //sim = 1, não = 0
        'matriz_fechada_tamanho', // Int
        'profundidade_pagamento_matriz', // Int

        'item_direcionado', //sim = 1, não = 0

        'update_titulo', //sim = 1, não = 0
        'update_titulo_automatico', //sim = 1, não = 0

        'moeda', //$, R$, £

        'rede_binaria', //sim = 1, não = 0
        'valor_ponto_binario', // double

        'bonificacao_diaria', // sim = 1, não = 0
        'bonificacao_diaria_recorrente', // sim = 1, não = 0

        //'tipo_teto_pagamento', //valor fixo = 1, percentual = 2, sem uso
        'tipo_bonus_indicador', //valor fixo = 1, percentual = 2,
        //'opcao_teto_pagamento', //1 = item, 2 = titulo OBSOLETO
        'tipo_bonus_equiparacao',

        'campo_cpf',
        'campo_rg',
        'campo_dtnasc',
        'endereco',
        'endereco_obrigatorio',
        'dados_bancarios',
        'dados_bancarios_obrigatorio',

        'rendimento_titulo', //true or false
        'rendimento_item', //true or false

        'min_deposito', //valor minimo para deposito
        'min_transferencia', //valor minimo para transferencia
        'deposito_is_active',

        'habilita_autenticacao_contratacao',
        'habilita_autenticacao_recontratacao',
        'habilita_autenticacao_transferencias',
        'alertas_recontratacao_range_dias',
        'habilita_estrangeiro',

        'emails_dados_bancarios',
        'emails_documentacao',
        'emails_comprovante_pagamento',

        'pontos_pessoais_calculo_exibicao',
        'pontos_equipe_calculo_exibicao',
        'extrato_capitalizacao_exibicao',
        'extrato_bonus_equipe_exibicao',
        'pagar_bonus_equiparacao',
        'royalties_porcentagem',
        'royalties_valor_minimo_bonus',
        'royalties_porcentagem_distribuir',

        'dias_para_transferencia', // prazo em dias úteis para efetivação da transferência
        'transferencia_interna_valor_minimo',
        'transferencia_interna_valor_minimo_gratis',
        'transferencia_interna_valor_taxa',
        'transferencia_interna_qtde_gratis',
        'transferencia_externa_valor_minimo',
        'transferencia_externa_valor_minimo_gratis',
        'transferencia_externa_valor_taxa',
        'transferencia_externa_qtde_gratis',
        'transferencia_interna_estornar_taxa',
        'transferencia_externa_estornar_taxa',
        'transferencia_externa_exige_upload_nota_fiscal',
        'restringir_dias_para_saques',
        'dia_permitido_para_saques',

        'habilita_registro_usuario_sem_indicacao',
        'habilita_registro_usuario_troca_indicador',
    ];

    public function getEmailsDadosBancariosAttribute(string $value): array
    {
        return array_filter(explode(', ', $value));
    }

    public function getEmailsDocumentacaoAttribute(string $value): array
    {
        return array_filter(explode(', ', $value));
    }

    public function getEmailsComprovantePagamentoAttribute(string $value): array
    {
        return array_filter(explode(', ', $value));
    }
}
