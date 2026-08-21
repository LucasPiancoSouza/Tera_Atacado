# Tera Atacado

Sistema de gerenciamento de estoque desenvolvido para o Tera Atacado, um atacadão fictício criado como projeto acadêmico.

## Sobre o projeto

O Tera Atacado tem como objetivo facilitar o controle e o gerenciamento do estoque do estabelecimento, permitindo acompanhar produtos, entradas, saídas e informações importantes por meio de uma interface simples e intuitiva.

## Objetivo

Desenvolver um sistema de gerenciamento de estoque que auxilie no controle dos produtos e permita visualizar informações importantes sobre o estoque de forma rápida e organizada.

## Funcionalidades

- Login de usuários
- Dashboard com informações do estoque
- Cadastro e exclusão de produtos
- Controle de entradas e saídas
- Visualização da quantidade de produtos
- Identificação dos produtos com maior e menor quantidade em estoque
- Gráficos informativos
- Geração de relatórios
- Perfil do usuário
- Controle das informações do administrador

## Integrantes

- Lucas
- Cadu
- Luiz

## Tecnologias

- **Front-end:** HTML, CSS, JavaScript e Nginx
- **Back-end:** PHP e Apache
- **Banco de dados:** MySQL
- **Análise e relatórios:** Python
- **Infraestrutura:** Docker e Docker Compose

## Docker

O projeto utiliza Docker para criar um ambiente de desenvolvimento separado para cada serviço.

### Serviços atuais

- MySQL
- Nginx

Os demais serviços serão adicionados durante o desenvolvimento.

### Comandos

```bash
# Iniciar os containers
docker compose up -d

# Verificar os containers
docker ps

# Parar os containers
docker compose down
```

## Acesso ao front-end

Com o Docker em execução, acesse:

<http://localhost:8080>

## Banco de dados

O MySQL é executado dentro de um container Docker.

| Configuração | Valor |
| --- | --- |
| Banco | `Tera_atacado` |
| Usuário | `` |
| Porta no host | `3307` |
| Porta no container | `3306` |

A porta `3307` do computador é direcionada para a porta `3306` do MySQL dentro do container.

## Status do projeto

Em desenvolvimento. A estrutura inicial do Docker e do banco de dados está sendo configurada.

