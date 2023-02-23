## Sistema para MDR

Sistema desenvolvido em laravel 5.1

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/a7f5a6aa5e464b7a8a09dd4c65a15fdc)](https://www.codacy.com?utm_source=bitbucket.org&amp;utm_medium=referral&amp;utm_content=mastermundi-team/liberty-capital-sistema&amp;utm_campaign=Badge_Grade)

### Montando ambiente Dev com docker e docker-compose
- docker-compose up -d
- docker-compose exec app composer update
- cp .env.example .env
- docker-compose exec app php artisan key:generate

- Atualize as variaveis de .env conforme os dados do docker-composer.yml
- em DB_HOST coloque o nome do container, nesse casso **mysql**
- em CACHE_DRIVER coloque **array**

- docker-compose exec app php artisan migrate --seed
- ln -sr storage/app/public public/storage

### Status do contrato (Saúde)
- 1 Aguardando liberação
- 2 Em aberto
- 3 Pausado (verificar motivo)
- 4 Cancelado fora do prazo
- 5 Aguardando Finalização
- 6 Finalizado
- 7 Cancelado dentro do prazo

### Status do Usuário
- 0 Desativado
- 1 Ativo
- 2 Mensalidade em atraso
- 3 Contrato Finalizado
- 4 Contrato Cancelado
- 5 Teto de ganho do item atingido
- 6 Teto de ganho do titulo atingido

### Status do pedido
- 1 Aguardando pagamento
- 2 Pagos
- 3 Cancelados
- 4 Aguardando confirmação pagamento
- 5 Aguardando renovação
- 6 Finalizado
- 7 Rentabilidade Total Atingida
- 8 Sacado para C/C