-- inserir dados 

-- 1. Categorias
INSERT INTO categorias (
    nome_categoria,
    quantidade_categoria,
    data_criacao
)
VALUES (
    'Alimentos',
    0,
    CURDATE()
);

-- 2. Fornecedores
INSERT INTO fornecedores (
    nome_fornecedor,
    empresa,
    cnpj,
    telefone,
    email
)
VALUES (
    'João da Silva',
    'Camil Alimentos',
    '12.345.678/0001-90',
    '(11) 99999-9999',
    'contato@camil.com'
);

-- 3. Produtos
INSERT INTO produtos (
    nome_produto,
    codigo_produto,
    preco,
    quantidade_produto,
    id_fornecedor,
    id_categoria
)
VALUES (
    'Arroz Camil 5kg',
    '7891234567890',
    25.90,
    100,
    1,
    1
);

-- 4. Usuários
INSERT INTO usuarios (
    nome_usuario,
    email,
    senha,
    cpf,
    tipo_usuario
)
VALUES (
    'Usuario Teste',
    'teste@tera.com',
    'senha_teste',
    '000.000.000-00',
    'admin'
);

-- 5. Movimentações (Primeira entrada)
INSERT INTO movimentacoes (
    tipo_modicacao,
    quantidade,
    id_produto,
    id_usuario,
    observacao
)
VALUES (
    1,
    20,
    2,
    1,
    'Entrada de mercadoria'
);

-- 6. Movimentações (Primeira saída)
INSERT INTO movimentacoes (
    tipo_modicacao,
    quantidade,
    id_produto,
    id_usuario,
    observacao
)
VALUES (
    0,
    30,
    2,
    1,
    'Saída de mercadoria'
);

-- 7. Movimentações (Teste da trigger - Saída)
INSERT INTO movimentacoes (
    tipo_modificacao,
    quantidade,
    id_produto,
    id_usuario,
    observacao
)
VALUES (
    0,
    30,
    2,
    1,
    'Teste da trigger - saída'
);

-- 8. Movimentações (Teste da trigger - Entrada)
INSERT INTO movimentacoes (
    tipo_modificacao,
    quantidade,
    id_produto,
    id_usuario,
    observacao
)
VALUES (
    1,
    10,
    2,
    1,
    'Teste da trigger - entrada'
);