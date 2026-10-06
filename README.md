# Tera Atacado

Sistema de gerenciamento de estoque desenvolvido como projeto acadêmico para um atacadão fictício.

## Sobre o projeto

O Tera Atacado está sendo desenvolvido para apoiar o controle de produtos, fornecedores, categorias e movimentações de estoque. O sistema ainda está em desenvolvimento: algumas telas e estruturas de banco já existem, mas os fluxos de gestão ainda não estão completos.

## Estado atual

### Implementado ou estruturado

- Tela de acesso em PHP/HTML com estilos responsivos e opção para mostrar ou ocultar a senha.
- Backend PHP com conexão ao MySQL por variáveis de ambiente.
- Estrutura de banco de dados para usuários, fornecedores, categorias, produtos, localizações e movimentações.
- Modelo de estoque por localização, com quantidade de cada produto em cada local.
- Campos para código de barras e registro de entradas e saídas, incluindo usuário, data e observação.
- Ambiente Docker Compose com MySQL e PHP 8.4/Apache.

## Tecnologias

- **Interface:** HTML, CSS, JavaScript e PHP
- **Backend e servidor web:** PHP 8.4 com Apache
- **Banco de dados:** MySQL 8.4
- **Ambiente de desenvolvimento:** Docker Compose

## Executar com Docker

1. Crie o arquivo `.env` a partir do exemplo e ajuste as credenciais conforme necessário:

   ```bash
   cp .env.example .env
   ```

2. Inicie os serviços:

   ```bash
   docker compose up -d --build
   ```

3. Confira os containers:

   ```bash
   docker compose ps
   ```

4. Acesse a tela de entrada em <http://localhost:8080/Front_end/>. O endpoint de verificação do backend está em <http://localhost:8080/Back_end/public/>.

Para parar os serviços:

```bash
docker compose down
```

Os dados do MySQL são mantidos no volume `mysql_data`. O arquivo `Database/schemas/schemas.sql` não é importado automaticamente pelo Compose; antes de usá-lo, confira se o nome do banco definido no script corresponde exatamente ao valor de `MYSQL_DATABASE` no `.env`.

## Banco de dados

As configurações do banco são lidas das variáveis abaixo:

| Variável | Exemplo no `.env.example` | Uso |
| --- | --- | --- |
| `MYSQL_DATABASE` | `Tera_atacado` | Banco utilizado pela aplicação |
| `MYSQL_USER` | `usuario` | Usuário da aplicação |
| `MYSQL_PASSWORD` | `senha` | Senha do usuário da aplicação |
| `MYSQL_ROOT_PASSWORD` | `senha` | Senha administrativa do MySQL |
| Porta no host | `3307` | Acesso externo ao MySQL |
| Porta no container | `3306` | Porta interna do MySQL |

O schema em `Database/schemas/schemas.sql` define as tabelas `usuarios`, `fornecedores`, `categorias`, `produtos`, `localizacao`, `localizacao_produto` e `movimentacoes`.

## Equipe

- Lucas Piancó
- Carlos Eduardo
- Luis Gabriel

## Status

Em desenvolvimento.
