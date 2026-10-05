CREATE DATABASE tera_atacado;
USE tera_atacado;

CREATE TABLE usuarios (
    id_usuario INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome_usuario VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(60) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    tipo_usuario ENUM('0','1') NOT NULL
);

CREATE TABLE fornecedores (
    id_fornecedor INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome_fornecedor VARCHAR(100) NOT NULL,
    empresa VARCHAR(100) NOT NULL,
    cnpj VARCHAR(18) NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL
);

CREATE TABLE categorias (
    id_categoria INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome_categoria VARCHAR(100) NOT NULL,
    quantidade_categoria INT NOT NULL,
    data_criacao DATE NOT NULL
);

CREATE TABLE localizacao (
    id_localizacao INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    setor VARCHAR(50) NOT NULL,
    corredor INT NOT NULL,
    andar INT NOT NULL
);

CREATE TABLE produtos (
    id_produto INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome_produto VARCHAR(100) NOT NULL,
    codigo_produto VARCHAR(13) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    id_fornecedor INT NOT NULL,
    id_categoria INT NOT NULL,
    codigo_de_barras VARCHAR(128) NOT NULL UNIQUE,

    FOREIGN KEY (id_fornecedor)
        REFERENCES fornecedores(id_fornecedor),

    FOREIGN KEY (id_categoria)
        REFERENCES categorias(id_categoria)
);

CREATE TABLE localizacao_produto (
    id_localizacao_produto INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_localizacao INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade_produto INT NOT NULL,

    FOREIGN KEY (id_produto)
        REFERENCES produtos(id_produto),

    FOREIGN KEY (id_localizacao)
        REFERENCES localizacao(id_localizacao)
);

CREATE TABLE movimentacoes (
    id_movimentacao INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    tipo_modificacao INT NOT NULL,
    quantidade INT NOT NULL,
    id_produto INT NOT NULL,
    id_usuario INT NOT NULL,
    data_hora_movimentacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    observacao TEXT,

    FOREIGN KEY (id_produto)
        REFERENCES produtos(id_produto),

    FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id_usuario)
);