insert into usuarios(nome_usuario,email,senha,cpf,tipo_usuario) values ("Luis", "luis@gamil.com","2906","27875441609","1");
insert into usuarios(nome_usuario,email,senha,cpf,tipo_usuario) values ("Lucas", "lucas@gamil.com","1603","98773647809","1");
insert into usuarios(nome_usuario,email,senha,cpf,tipo_usuario) values ("Carlos", "carlos@gamil.com","120213","75639490809","0");

-- =====================================================
-- 2. FORNECEDORES
-- =====================================================

INSERT INTO fornecedores
(id_fornecedor, nome_fornecedor, empresa, cnpj, telefone, email)
VALUES
(1, 'Fornecedor Alimentos', 'Alimentos Brasil Ltda',
 '12.345.678/0001-90', '(11) 3333-1000',
 'contato@alimentosbrasil.com'),
(2, 'Fornecedor Bebidas', 'Distribuidora de Bebidas Ltda',
 '23.456.789/0001-01', '(11) 3333-2000',
 'contato@distribuidorabebidas.com'),
(3, 'Fornecedor Limpeza', 'Produtos de Limpeza Ltda',
 '34.567.890/0001-12', '(11) 3333-3000',
 'contato@limpeza.com'),
(4, 'Fornecedor Geral', 'Distribuidora Geral Ltda',
 '45.678.901/0001-23', '(11) 3333-4000',
 'contato@distribuidorageral.com');


-- =====================================================
-- 3. CATEGORIAS
-- =====================================================

INSERT INTO categorias
(id_categoria, nome_categoria, data_criacao)
VALUES
(1, 'Mercearia', '2026-10-18'),
(2, 'Bebidas', '2026-10-18'),
(3, 'Limpeza', '2026-10-18'),
(4, 'Higiene Pessoal', '2026-10-18'),
(5, 'Hortifruti', '2026-10-18'),
(6, 'Açougue e Frios', '2026-10-18'),
(7, 'Laticínios e Refrigerados', '2026-10-18'),
(8, 'Padaria e Confeitaria', '2026-10-18'),
(9, 'Congelados', '2026-10-18'),
(10, 'Pet Shop', '2026-10-18'),
(11, 'Descartáveis', '2026-10-18'),
(12, 'Utilidades Domésticas', '2026-10-18');


-- =====================================================
-- 4. LOCALIZAÇÕES
-- Uma localização é definida por setor, corredor,
-- prateleira e andar.
-- =====================================================

INSERT INTO localizacao
(id_localizacao, setor, corredor, prateleira, andar)
VALUES
(1, 'Mercearia', 1, 1, 1),
(2, 'Bebidas', 1, 1, 1),
(3, 'Limpeza', 1, 1, 1),
(4, 'Higiene Pessoal', 1, 1, 1),
(5, 'Hortifruti', 1, 1, 1),
(6, 'Açougue e Frios', 1, 1, 1),
(7, 'Laticínios e Refrigerados', 1, 1, 1),
(8, 'Padaria e Confeitaria', 1, 1, 1),
(9, 'Congelados', 1, 1, 1),
(10, 'Pet Shop', 1, 1, 1),
(11, 'Descartáveis', 1, 1, 1),
(12, 'Utilidades Domésticas', 1, 1, 1),
(13, 'Depósito Geral', 1, 1, 1);


-- =====================================================
-- 5. PRODUTOS
-- =====================================================

INSERT INTO produtos
(id_produto, nome_produto, codigo_produto, preco,
 id_fornecedor, id_categoria, codigo_de_barras)
VALUES
(1, 'Arroz 5kg', 'PROD000000001', 25.90,
 1, 1, '7891000000001'),
(2, 'Feijao 1kg', 'PROD000000002', 7.50,
 1, 1, '7891000000002'),
(3, 'Refrigerante 2L', 'PROD000000003', 9.99,
 2, 2, '7891000000003'),
(4, 'Detergente 500ml', 'PROD000000004', 2.49,
 3, 3, '7891000000004'),
(5, 'Sabonete', 'PROD000000005', 3.25,
 4, 4, '7891000000005'),
(6, 'Maca 1kg', 'PROD000000006', 6.90,
 4, 5, '7891000000006'),
(7, 'Queijo 500g', 'PROD000000007', 18.90,
 1, 6, '7891000000007'),
(8, 'Leite 1L', 'PROD000000008', 5.49,
 1, 7, '7891000000008'),
(9, 'Pao de Forma', 'PROD000000009', 8.50,
 1, 8, '7891000000009'),
(10, 'Pizza Congelada', 'PROD000000010', 16.90,
 1, 9, '7891000000010'),
(11, 'Racao para Caes 1kg', 'PROD000000011', 14.90,
 4, 10, '7891000000011'),
(12, 'Copo Descartavel', 'PROD000000012', 6.00,
 4, 11, '7891000000012');


-- =====================================================
-- 6. ESTOQUE POR LOCALIZAÇÃO
-- =====================================================

INSERT INTO localizacao_produto
(id_localizacao_produto, id_localizacao, id_produto,
 quantidade_produto)
VALUES
(1, 1, 1, 50),
(2, 1, 2, 80),
(3, 2, 3, 40),
(4, 3, 4, 100),
(5, 4, 5, 60),
(6, 5, 6, 30),
(7, 6, 7, 25),
(8, 7, 8, 70),
(9, 8, 9, 35),
(10, 9, 10, 20),
(11, 10, 11, 45),
(12, 11, 12, 90);


-- =====================================================
-- 7. MOVIMENTAÇÕES INICIAIS
-- Cada registro representa uma entrada de estoque.
-- As quantidades correspondem ao estoque inicial acima.
-- =====================================================

INSERT INTO movimentacoes
(id_movimentacao, tipo_modificacao, quantidade,
 id_usuario, data_hora_movimentacao, observacao,
 id_localizacao_produto)
VALUES
(1, 1, 50, 1, CURRENT_TIMESTAMP, 'Estoque inicial',
 1),
(2, 1, 80, 1, CURRENT_TIMESTAMP, 'Estoque inicial',
 2),
(3, 1, 40, 1, CURRENT_TIMESTAMP, 'Estoque inicial',
 3),
(4, 1, 100, 1, CURRENT_TIMESTAMP, 'Estoque inicial',
 4),
(5, 1, 60, 1, CURRENT_TIMESTAMP, 'Estoque inicial',
 5),
(6, 1, 30, 1, CURRENT_TIMESTAMP, 'Estoque inicial',
 6),
(7, 1, 25, 1, CURRENT_TIMESTAMP, 'Estoque inicial',
 7),
(8, 1, 70, 1, CURRENT_TIMESTAMP, 'Estoque inicial',
 8),
(9, 1, 35, 1, CURRENT_TIMESTAMP, 'Estoque inicial',
 9),
(10, 1, 20, 1, CURRENT_TIMESTAMP, 'Estoque inicial',
 10),
(11, 1, 45, 1, CURRENT_TIMESTAMP, 'Estoque inicial',
 11),
(12, 1, 90, 1, CURRENT_TIMESTAMP, 'Estoque inicial',
 12);