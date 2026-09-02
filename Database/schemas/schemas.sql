-- Estrutura do Banco

CREATE TABLE usuarios (
    id_usuario INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome_usuario VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(60) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    tipo_usuario VARCHAR(20) NOT NULL
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

CREATE TABLE produtos (
    id_produto INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome_produto VARCHAR(100) NOT NULL,
    codigo_produto VARCHAR(13) NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    quantidade_produto INT NOT NULL,
    id_fornecedor INT NOT NULL,
    id_categoria INT NOT NULL,
    
    FOREIGN KEY (id_fornecedor) REFERENCES fornecedores(id_fornecedor),
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
);

CREATE TABLE movimentacoes (
    id_movimentacao INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    tipo_modicacao INT NOT NULL, -- 0-saidas 1-entradas 2-ajustes
    quantidade INT NOT NULL,
    id_produto INT NOT NULL,
    id_usuario INT NOT NULL,
    data_hora_movimentacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    observacao TEXT,
    
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);
DELIMITER //
create trigger atualizar_estoque
after insert on movimentacoes
for each row
begin

	if NEW.tipo_modificacao = 1 then
		update produtos set quantidade_produto = quantidade_produto + NEW.quantidade
        where id_produto = NEW.id_produto;
        
	elseif NEW.tipo_modificacao = 0 then 
		update produtos set quantidade_produto = quantidade_produto - New.quantidade
        where id_produto = NEW.id_produto;
        
	end if;
end //
DELIMITER ;
