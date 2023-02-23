# Change Log
Todas as mudanças notaveis do projeto serão documentadas neste documento.

O formato é baseado em [Keep a Changelog](http://keepachangelog.com/). <br>
Versionamento baseado em [Versionamento Semântico 2.0.0](http://semver.org/lang/pt-BR/)

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/158c9f0296ac457786ea4c31cd235405)](https://www.codacy.com?utm_source=bitbucket.org&amp;utm_medium=referral&amp;utm_content=mastermundi-team/master_mdr&amp;utm_campaign=Badge_Grade)

## [1.0.0] migração a partir da Liberty
- Trocados imagens/ logos
- Mudanças no manifest
- mudanças no bitbucket-pipelines

## [2.9.5]
### Adicionado
- add recontratação automática de contratos
- mudança de layout home admin (lista de transferências, depósitos)
- add variaveis de ambiente **COMPANY_NAME_SHORT**, **COMPANY_NAME_FOOTER**, **APP_URL**
- add **logo e-mail**, **link de rede sociais** dinâmicos
- add cadastro de termo inicial

## [2.9.4]
### Adicionado
- metodo de pagamento na lista de pedidos/depositos
- botão para gerar boleto no admin
- numero de telefone na lista de usuários

### Mudanças
- texto dos emails
- retirado contratos fechados do extrato
- lista de contratos concluidos com status 6
- somente master poder efetivar deposito pelo sistema

## [2.9.3]
### Adicionado
- add valor da rentabilidade no email
- add aviso sobre transferência apenas pela mesma titularidade

## [2.9.2]
### Adicionado
- add visualização comprovantes deposito em pdf

## [2.9.1]
### Mudanças
- middlaware de documentos, retirado validação dos usuários *admin* e *master*

### Adicionado
- aviso de documentação na home
- add horarios nas listas de depositos
- envio de email para transferencias
- inserido mais informações na lista de transferencias
- serviço de finalização de contrato automático
- serviço de resgate minimo automático do capital corrigido
- adicionado aviso sobre digito no cadastro de conta bancaria

### Correções
- solicitação de 2 fatores nas transferências

## [2.8.1]
### Mudanças
- adicionado mais detalhes na lista de transferencia
- descrições da tela de envio de documento
- legendas nas telas de transferencia 
- avisos de documentação com modal
- modificado coluna **quantidade** da tabela *itens_pedido* de **int** para **decimal(10,2)**

### Adicionado
- Verificação de validação de documento nas transações do sistema [2019-09-05]

### Correções
- erro ao acessar a home

## [2.8.0]
### Mudanças
- Cadastro de itens: valores minimos de **taxa de resgate**, **contrato**, **carencia minima** e **potencial mensal** agora são igual a 0 (zero)
- Nomenclaturas
- Visualização do comprovante de depósito

### Adicionado
- adicionado simulação de correção mensal na aquisição de itens
- Adicionado data de cadastro na lista de usuários
- Transferencia
- Transferência entre contas liberty
- verificação de documentos para administrador
- cadastro de CNPJ
- Novos contratos para CAP

### Correções
- Valor do total de ganhos exibido na index
- Direcionamento de rota após termino do cadastro

## [] - 2019-06-29
### Changed
- adicionados novos campos no cadastro de itens

## [] - 2019-06-28
### Added
- adicionado *pontos binarios na iten_pedido*
- Adicionado evento para qualificar usuario

## [] - 2019-06-27
### Changed
- adicionado favicon no cadastro de empresa
- adicionado link simbolico para *storage/app/public*
- Adicionado visualização da rede
- Retirado obrigatoriedade do verificação de dependentes no pedido
- Add visualização de pontos binários na home

- Refatorado posicionamento bináro

### Fixed
- visualização da rede binaria vazia

## [2.6.4] - 2018-11-16
### Changed
- Adicionado numero do contrato da SMM na lista de Contratos


## [2.6.3] - 2018-11-16
### Changed
- adicionado opção de soma total no relatório de faturamento

## [2.6.2] - 2018-11-12
### Fixed
- Incluindo boletos de empresas na remessa

### Changed
- Local do botão gerar remessas

## [2.6.1] - 2018-11-01
### Changed
- Adicionado Vouche rno meio de pagamento de adesão
- Lista de contratos aberto e atrasado agora utilizam datatables

## [2.6.0] - 2018-11-01
### Added
- Adicionado metodo para confirmar o envio da remessa (efetivo)

### Fixed
- Verificação de datas nas remessas

## [2.5.0] - 2018-10-22
### Added
- Adicionado gerador de remessas

## [2.4.0] - 2018-10-15
### Added
- Edição de pedidos pagos
- Add Composer lock

### Fixed
- Edição de mensalidades

## [2.3.0] - 2018-08-20
### Added
- Adicionado novos tipos de guias
- Lista de guias autorizadas e aguardando
- possibilidade de solicitar retorno desde a consulta
- Solicitação de retorno após 21 dias somente por Admin e Master
- Campos de valor de fisioterapia para os planos (itens)

### Fixed
- Acesso a api medicos

## [2.2.2] - 2018-07-13
### Changed
- Adicionado campos de endereço na impressão de guia
- Botão cancelar contrato nos **contratos atrasados**


## [2.2.1] - 2018-07-13
### Fixed
- Acionamento do botão cancelar do contrato
- Relatorio de recebimento diario ambiguo 

### Added
- Filtro de tipo de pagamento no relatório de recebimentos

### Changed
- Busca de exames em contratos de empresas

## [2.2.0] - 2018-07-05
### Added
- Cancelamento de contrato

## [2.1.3] - 2018-06-28
### Changed
- Mudança de status *finalizado* para *ativo*
- Adicionado botão de cancelar guia para usuário *master*
- Mudança de nomenclatura de *campo cadastro de guias*

## [2.1.2] - 2018-06-26
### Added
- Adicionado especialidades e clinicas na lista de medicos
- Adicionado menu medicos para usuarios clinicas e callcenter

## [2.1.1] - 2018-06-18
### Added
- Adicionado cadastro de **Especialidade** e relacionamento com **Medicos**

### Changed
- Lista nome das clinicas para **call center**


## [2.1.0] - 2018-06-18
### Added
- Adicionado usuario **Call Center**

### Changed
- Adicionado mais campos e filtros no relatório de usuários

### Fixed
- Carregamento de medicos no *create* de guias

## [2.0.9] - 2018-06-18
### Added 
- adicionado autorização nas guias
- Autorização automatica após inclusão (guias)
- Adicionado campo clinica para inclusao e edição pelo admin 
- Edição de guia

### Changed
- Gerar guias para contratos com restrição
- nomenclatura do nome do campo do titular (cadastro guias)

### Fixed
- Fuso horario de campo grande (guias)
- nome da clinica na guia


## [2.0.8] - 2018-08-06
### Added
- Cadastro de exames, rodar migrations do mesmo com o **path** *app/SaudeMuitosMais/database/migrations/*
- Cadastro de medicos, rodar migrations do mesmo com o **path** *app/SaudeMuitosMais/database/migrations/*
- Inserido guias

### Fixed
- campos faltantes na impressão de contrato pelo sistema

### Changed
- arquivo de sidebar para master e admin é o mesmo agora

## [2.0.7] - 2018-06-06
### Added
- Pedidos para acrescentar dependentes e pagamento de migração de planos

## [2.0.6] - 2018-23-05
### Changed
- Adicionado filtro de consultor e quantidade de mensalidades no relatório de inadimplentes

## [2.0.5.1] - 2018-22-05
### Fixed
- Pagamento via retorno de boleto

## [2.0.5] - 2018-08-05
### Added
- impressão de contrato de consultor
- Lista de usuários consultores

## [2.0.4] - 2018-07-05
### Added
- Relatório de inadimplentes
- ao rodar o sistema é verificado todas as mensalidades em atraso do contrato
- Lista de contratos em atraso

### Changed
- Verificação de inadimplentes ao rodar sistema
- Estado civil do cadastro de usuarios é obrigatório

### Fixed
- Ao setar mensalidade como *não paga bonus* ele estava pagando do mesmo jeito

## [2.0.3] - 2018-02-05
### Added
- Log agora é guardado por 90 dias

### Fixed
- tipo de movimentação salvo na adesão
- Mudança de status de mensalidade

## [2.0.2] - 2018-19-04
### Changed
- abas de bonus mostrado no extrato

### Added
- Campos **mensalidade_id** e  **pedido_id** na tabela mensalidades, use migrations
- inserido renovação dos relatórios

## [2.0.1] - 2018-19-04
### Changed
- adicionado lista de bonus separada por tioo (adesão, mensalidades D e E)
- Ao fazer renovação é cobrado a carteirinha do titular também 

## [2.0.0.0] - 2018-19-04
### Fixed
- dia de pagamento de cada parcela, contrato

### Added
- Adicionado contato do consultor na home
- adicionado menu para consultar contratos inadimplentes para consultor

### Changed
- Retirado: extrato, minha rede e titulos do menu do Admin

## [1.9.9.2] - 2018-17-04
### Fixed
- permissão de acesso a *permission* e *roles*

## [1.9.9.1] - 2018-17-04
### Added
- adicionado migration, add collumn **estado_civil** on *users*
- add campo estado_civil ao cadastro de usuário

### Fixed
- route de contrato na lista de contratos

## [1.9.9] - 2018-17-04
### Fixed
- Preenchimento de dados do contrato corretamente
- Fix acesso a mensalidade avulsa
- Adicionado modulo de NumetoPorExtenso

## [1.9.8] - 2018-16-04
### Added
- Acesso a outros usuarios sem necessidade de senha

## [1.9.7] - 2018-13-04
### Fixed
- corridigos erros apos colocar o metodos de imprimir contrato
- Chamada de impressão por javascript após abrir contrato
- chama de *dtInicioImpressao* corrigido

### Added
- alterado **manipularOutro** para que ninguem tenha acesso a contratos de outros fora o admin
- condições para quando não houver contrato

### Changed
- Mudanças de nomenclatura solicitados, Plano => Adesão

## [1.9.6] - 2018-13-04
### Added
- Adicionado impressão de contratos, necessida alimentar as novas variaveis **descricao_impressao** e **tipo_plano**
- Add novas migrations

### Changed
- Mudanças no modulo Saude e no namespaces

## [1.9.5] - 2018-11-04
### Fixed
- Pedido calculando dependente desativado erroneamente
- Link de visualização de dependente desativado incorreto

## [1.9.4] - 2018-09-04
### Added
- Adicionado docker-compose.yml

### Fixed
- Verificação de flag paga_bonus erronea

## [1.9.3] - 2018-19-03
### Added
- Verificação de contas elegiveis para uso de boleto, antes de gerar boletos

### Changed
- reativa usuário finalizado se fizer renovação
- DebbugerBar somente para meu IP e Local

## [1.9.2] - 2018-15-03
### Added
- Add data de pagamento no pagamento de adesão pelo sistema
- Boleto gerado pelo admin pode ter data de vencimento alterada

## [1.9.1] - 2018-15-03
### Fixed
- Não esta gerando valor das carteirinhas na renovação

### Changed
- retorno boleto, dt_baixa alterado de *dtCorrencia* para *dtCredito* 

## [1.9.0] - 2018-15-03
### Added
- Adicionado trigger na edição de mensalidade para pagar sem bonus
- Tabela para visualização e procura de boletos pelo *nosso numero*
- Pagamento de adesões e mensalidades pelo retorno do banco **arquivo CRT**

### Changed
- Atualizado dependencia laravel-boletos

## [1.8.2] - 2018-07-03
### Fixed
- Manipulação de usuários desativados
- Inserção dos dados dos usuários desativados nos relatórios

## [1.8.1] - 2018-05-03
### Added
- Adicionado botão para eventos de verificação de contratos e mensalidade manualmente

## [1.8.0] - 2018-27-02
### Added
- Evento para verificação de contratos e mensalidade
- Lista de contratos *em finalização* e *finalizados*
- Lista de usuários **inadimplentes** e **com contrato finalizado**

## [1.7.2] - 2018-20-02
### Changed
- Nomenclatura dos bonus no relatório

### Fixed
- Bonus de equiparação não estava aparecendo no relatório

## [1.7.1] - 2018-16-02
### Added
- Adicionado lista de contratos finalizados no painel

### Fixed
- A ultima mensalidade do contrato é paga corretamente e a ação da mesma finaliza o contrato

## [1.7.0] - 2018-16-02
### changed
- Mensalidades pagas agora podem ser editadas pelos *Admin* e **master**

## [1.6.3] - 2018-06-02
### Fixed
- **MensalidadePaga.php** erro na linha 42, ao verificar status do indicador. Add validação no final do laço *while*


## [1.6.2] - 2018-03-02
### Fixed
- Pagamento de equiparação 


## [1.6.1] - 2018-02-02
### Fixed
- Pagamento de bonus $ da mensalidade 


## [1.6.0] - 2018-17-01
### Changed
- Trocado nomes do relatório de **recebimento** para *pagamento*, no corpo do relatório e sidebar
- modificação do arquivo **general-pdf-template** do Jimmy-JS/laravel-report-generator, fazer pullrequest
- Trocado url da rota de *relatorio/pagamentos-diarios* para *relatorio/recebimentos*

### Fixed
- RelatórioController, function *relatorioPagamentosDiarios* alteração na soma total que não somava os centavos devido a virgula 

## [1.5.0] - 2017-01-12
### Add
- Modulo tratar texto

### Changed
- **RelatorioController** remodelado Relatorio de consultor, deixado parecido com o relatório de pagamento diário

## [1.4.0] - 2017-11-25
### Fixed
- **PagamentoController** retirado *dd()*  esquecido
- **DadosUsuarios** update de profissão e e telefone

### Changed
- Update do pacote de consulta do correio
- Retirado validation **interger** do cadastro do link de indicação
- Mudanças do *edit* de **mensalidade**, add campos 

## [1.3.3] - 2017-10-19
### Add
- Tipo de usuário

## [1.3.2] - 2017-10-09
### Add
- Verificação de dependentes antes de aderir ao plano

## [1.3.1] - 2017-09-05
### Fixed
- Erros no carregamento de dados do *PedidoController* com ***user** desabilitado

## [1.3.0] - 2017-04-14
### added
- Adicionado cadastro de configuracões (modulos)
- Background e logo dinamicos
- Add Modulo singelo do pagseguro

## [1.2.0] - 2017-02-14
### added
- adicionado recuperação de senha

## [1.1.1] - 2017-02-07
### Fixed
- alteração no posicionamento do hotel **Models\Hospedes** em *encontraPosicaoVazioHotelQuartos* linha 284

## [1.1.0] - 2017-02-04
### Added
- Adicionado evento **AcoesSistema** e Listening **BonusMilhasCadastro**

### Changed
- adicionado campo patrocinador na lista de usuarios


## [1.0.4] - 2017-01-28
### Added
- acesso externo aos donwloads

### Fixed
- posicionamento de rede binaria setado no automatico

### Changed
- trocado fundo do clube

## [1.0.3] - 2017-01-24
### Fixed
- Rodar binario, extrato binario ordenado pelo ID

## [1.0.2] - 2017-01-19
### Fixed
- Posicionamento direta e esquerda no model **RedeBinaria**, agora retorna a profundidade também
- Adequado posicionamento binário automatico, só tranborda para as estremidades

### Changed
- Mudança na chamada de *diretosAprovados* no model **Users**. Retirado os *get()* das chamadas destes metodo nos arquivos **QualificaUsuarioPosicionaHotel**, **PosicionaRede** e **home.blade**
- **PosicionaRede** verifica em qual *lado* esta os diretos antes de alocar utilizando posicionamento automatico

## [1.0.1] - 2017-01-17

### Changed
- Adicionado validação no campo __avanca_titulo__, agora obrigatorio no *cadastro de itens* 
- Adicionado opção de operação __taxa de retirada__ ao cadastro de *movimentos*

### Fixed
- Hospedes, procura de quarto vazio com erros
- Criado funções posicaoVaziaHotel e encontraPosicaoVazioHotelQuartos
- Relatório para pagamentos buscando saldo acima de 0 (zero), mudado para >= 100