CREATE TABLE usuarios (
    id_usuario INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome_usuario VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    cpf VARCHAR(11) NOT NULL UNIQUE,
    tipo_usuario ENUM('0','1') NOT NULL -- 0 funcionario - 1 adm 
);


CREATE TABLE fornecedores (
    id_fornecedor INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome_fornecedor VARCHAR(100) NOT NULL,
    empresa VARCHAR(100) NOT NULL,
    cnpj VARCHAR(18) NOT NULL unique,
    telefone VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL,
    cep varchar(8) not null,
    estado varchar(100) not null,
    cidade varchar(100) not null,
    rua_numero varchar(255) not null
);

CREATE TABLE categorias (
    id_categoria INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome_categoria VARCHAR(100) NOT NULL,
    data_criacao DATE NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE localizacao (
    id_localizacao INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    setor VARCHAR(50) NOT NULL,
    corredor INT NOT NULL,
	prateleira INT NOT NULL,
    andar INT NOT NULL,
    
    CONSTRAINT localizacao
    UNIQUE (setor, corredor, prateleira, andar)
);

CREATE TABLE produtos (
    id_produto INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome_produto VARCHAR(100) NOT NULL,
    codigo_produto VARCHAR(13) NOT NULL unique,
    preco DECIMAL(10,2) NOT NULL,
    id_fornecedor INT NOT NULL,
    id_categoria INT NOT NULL,
    codigo_de_barras VARCHAR(128) NOT NULL UNIQUE,
	estoque_minimo int not null,
    
    FOREIGN KEY (id_fornecedor)
        REFERENCES fornecedores(id_fornecedor),

    FOREIGN KEY (id_categoria)
        REFERENCES categorias(id_categoria),
        
	CONSTRAINT preco_negativo
		CHECK (preco >= 0)
);

CREATE TABLE localizacao_produto (
    id_localizacao_produto INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_localizacao INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade_produto INT NOT NULL,

    FOREIGN KEY (id_produto)
        REFERENCES produtos(id_produto),

    FOREIGN KEY (id_localizacao)
        REFERENCES localizacao(id_localizacao),
        
	constraint localizacao_produto unique (id_produto, id_localizacao),
    
    constraint quantidade_negativa check (quantidade_produto >= 0)
);

CREATE TABLE movimentacoes (
    id_movimentacao INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    tipo_modificacao INT NOT NULL, -- 0 saida -- 1 entrada
    quantidade INT NOT NULL,
    id_usuario INT NOT NULL,
    data_hora_movimentacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    observacao TEXT,
    id_localizacao_produto int not null,
    
    foreign key (id_localizacao_produto) 
		references localizacao_produto(id_localizacao_produto),

    FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id_usuario),
        
	constraint quantidade_movimentacao check (quantidade > 0)
);