# Tera Atacado

Sistema de gerenciamento de estoque desenvolvido como projeto acadêmico para a **Bartotec**, feira de tecnologia da ETEC Bartolomeu Bueno da Silva.

O sistema foi criado para facilitar o controle de estoque de estabelecimentos comerciais, centralizando o gerenciamento de produtos, fornecedores, categorias, localizações e movimentações.

## Funcionalidades

- **Login:** acesso com CPF e senha e controle de sessão.
- **Dashboard:** gráficos de entradas e saídas em diferentes períodos.
- **Produtos:** cadastro, consulta, edição e exclusão.
- **Categorias:** gerenciamento das categorias dos produtos.
- **Fornecedores:** cadastro e gerenciamento de fornecedores.
- **Estoque por localização:** consulta das quantidades por setor, corredor, prateleira e andar.
- **Entradas e saídas:** registro de movimentações, com verificação do saldo disponível.
- **Histórico:** consulta das movimentações realizadas, com data e usuário responsável.
- **Relatórios:** consulta de produtos, entradas, saídas, estoque baixo e movimentações, com filtros e exportação em CSV ou PDF.

## Demonstração

As imagens abaixo apresentam algumas telas do sistema.









## Tecnologias utilizadas

- HTML, CSS e JavaScript
- PHP 8.4 com Apache
- MySQL 8.4
- Docker Compose

## Melhorias futuras

- Controle de validade dos produtos.
- Pedidos de reposição para fornecedores.
- Notificações de estoque baixo.

## Como executar

### Pré-requisitos

- Docker Desktop.
- Git (opcional, para clonar o repositório).

### 1. Clone o repositório

```bash
git clone URL_DO_REPOSITORIO
```

Entre na pasta do projeto.

### 2. Configure as variáveis de ambiente

Crie o arquivo `.env` utilizando o arquivo de exemplo:

**PowerShell:**

```powershell
Copy-Item .env.example .env
```

Abra o arquivo `.env` e substitua os valores de exemplo pelas suas próprias configurações do MySQL.

**Atenção:** configure principalmente o usuário e a senha do banco de dados. Não deixe valores de exemplo, como `MYSQL_PASSWORD=senha`. Defina uma senha de sua preferência e também configure `MYSQL_ROOT_PASSWORD`.

Confira também se o banco está definido corretamente:

```env
MYSQL_DATABASE=tera_atacado
```

### 3. Inicie os serviços

Na pasta principal do projeto, execute:

```bash
docker compose up -d --build
```

### 4. Importe o banco de dados

Importe o arquivo:

```text
Database/schemas/schemas.sql
```

Para utilizar os dados de exemplo, importe também:

```text
Database/seeds/seeds.sql
```

O arquivo de dados de exemplo deve ser importado depois do schema.

### 5. Acesse o sistema

Abra o navegador e acesse:

[http://localhost:8080/Front_end/index.php](http://localhost:8080/Front_end/index.php)

### Comandos úteis

Verificar os serviços:

```bash
docker compose ps
```

Parar os serviços:

```bash
docker compose down
```

**Observação:** os dados do MySQL são mantidos no volume `mysql_data`. O comando `docker compose down` não apaga esse volume.

## Estrutura do banco de dados

O banco de dados possui tabelas para:

- Usuários
- Fornecedores
- Categorias
- Produtos
- Localizações
- Produtos por localização
- Movimentações

## Equipe

- **Lucas Piancó:** [GitHub](https://github.com/LucasPiancoSouza)
- **Carlos Eduardo:** [GitHub](https://github.com/Cadu782)
- **Luis Gabriel:** [GitHub](https://github.com/louisEmbassado)

## Status

**Projeto acadêmico finalizado.**

As melhorias futuras estão listadas separadamente e não fazem parte da versão atual.
