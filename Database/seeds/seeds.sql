insert into usuarios(nome_usuario,email,senha,cpf,tipo_usuario) values ("Luis", "luis@gamil.com","2906","27875441609","1");
insert into usuarios(nome_usuario,email,senha,cpf,tipo_usuario) values ("Lucas", "lucas@gamil.com","1603","98773647809","1");
insert into usuarios(nome_usuario,email,senha,cpf,tipo_usuario) values ("Carlos", "carlos@gamil.com","120213","75639490809","0");

INSERT INTO fornecedores
(nome_fornecedor, empresa, cnpj, telefone, email, cep, estado, cidade, rua_numero)
VALUES
('Carlos Mendes', 'Distribuidora Alimentos SP', '12.345.678/0001-90', '(11) 3333-1000', 'contato@alimentos.com.br', '01001000', 'SP', 'São Paulo', 'Rua A, 100'),
('Mariana Costa', 'Higiene Total Ltda', '23.456.789/0001-01', '(11) 3333-2000', 'vendas@higienetotal.com.br', '02002000', 'SP', 'São Paulo', 'Rua B, 200'),
('Roberto Lima', 'Bebidas Brasil Ltda', '34.567.890/0001-12', '(11) 3333-3000', 'contato@bebidasbrasil.com.br', '03003000', 'SP', 'São Paulo', 'Rua C, 300'),
('Fernanda Alves', 'Limpeza Fácil', '45.678.901/0001-23', '(11) 3333-4000', 'vendas@limpezafacil.com.br', '04004000', 'SP', 'São Paulo', 'Rua D, 400');
INSERT INTO categorias (nome_categoria, data_criacao)
VALUES
('Alimentos', '2026-01-10'),
('Higiene Pessoal', '2026-01-10'),
('Bebidas', '2026-01-10'),
('Limpeza', '2026-01-10');
INSERT INTO localizacao (setor, corredor, prateleira, andar)
VALUES
('Alimentos', 1, 1, 1),
('Alimentos', 1, 2, 1),
('Higiene', 2, 1, 1),
('Bebidas', 3, 1, 1),
('Limpeza', 4, 1, 1);
INSERT INTO produtos
(nome_produto, codigo_produto, preco, id_fornecedor, id_categoria,
 codigo_de_barras, estoque_minimo)
VALUES
('Arroz 5kg', 'PROD000000001', 29.90, 1, 1, '7891000000001', 40),
('Feijão 1kg', 'PROD000000002', 8.49, 1, 1, '7891000000002', 35),
('Macarrão 500g', 'PROD000000003', 4.99, 1, 1, '7891000000003', 25),
('Óleo de soja 900ml', 'PROD000000004', 7.99, 1, 1, '7891000000004', 30),
('Açúcar 5kg', 'PROD000000005', 19.90, 1, 1, '7891000000005', 20),

('Sabonete 90g', 'PROD000000006', 2.99, 2, 2, '7891000000006', 50),
('Shampoo 350ml', 'PROD000000007', 14.90, 2, 2, '7891000000007', 15),
('Creme dental 90g', 'PROD000000008', 5.49, 2, 2, '7891000000008', 30),

('Água mineral 1,5L', 'PROD000000009', 3.49, 3, 3, '7891000000009', 60),
('Refrigerante 2L', 'PROD000000010', 8.99, 3, 3, '7891000000010', 45),
('Suco 1L', 'PROD000000011', 6.99, 3, 3, '7891000000011', 20),

('Detergente 500ml', 'PROD000000012', 2.49, 4, 4, '7891000000012', 40),
('Água sanitária 1L', 'PROD000000013', 3.99, 4, 4, '7891000000013', 25),
('Desinfetante 2L', 'PROD000000014', 7.49, 4, 4, '7891000000014', 20);

INSERT INTO localizacao_produto
(id_localizacao, id_produto, quantidade_produto)
VALUES
(1, 1, 120),
(2, 2, 80),
(1, 3, 18),
(2, 4, 70),
(1, 5, 12),
(3, 6, 100),
(3, 7, 10),
(3, 8, 45),
(4, 9, 150),
(4, 10, 90),
(4, 11, 15),
(5, 12, 100),
(5, 13, 20),
(5, 14, 8);

