# Agenda

## Sobre

- Projeto simples para testar e aplicar conhecimentos de docker, teste e padrões
- O projeto foi feito apenas com o dominio da agenda, utilizando de forma interna um repositório de usuario para facilitar a manipulação da agenda
- A documentação fica disponivel na [url](http://localhost:8080/api/documentation)
- usuario padrão de testes: "test@example.com" e senha 'password'


## Stack

- PHP 8.3
- Laravel 11
- mysql 8

## Testando o projeto

1. docker-compose up --build -d

## Para rodar os testes

1. docker-compose exec php php artisan test

## gerar documentação

1. composer generate-doc

## Possíveis melhorias para o projeto

- Criação do repositório para agendamentos para garantir o padrão no projeto todo;
- Adição de notificação de criação/edição e exclusão de agendamentos via eventos no dominio (email, sms) via fila;
- Registro de alterações de agenda;
- Alterar o método de token para sempre retornar o token ativo;
- Relacionamento entre eventos e outros usuarios;
