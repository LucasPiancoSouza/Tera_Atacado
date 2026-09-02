-- Guarda queries importantes 

-- 1. Consultar todos os registros de categorias
SELECT * FROM categorias;

-- 2. Consultar todos os registros de fornecedores
SELECT * FROM fornecedores;

-- 3. Consultar todos os registros de produtos
SELECT * FROM produtos;

-- 4. Consultar todos os registros de usuários
SELECT * FROM usuarios;

-- 5. Consultar todos os registros de movimentações (executado 2x no script)
SELECT * FROM movimentacoes;

-- 6. Consultar dados específicos do produto com ID 2
SELECT id_produto, nome_produto, quantidade_produto
FROM produtos
WHERE id_produto = 2;

-- 7. Consultar o saldo total de movimentações agrupado para o produto ID 2
SELECT 
    id_produto,
    SUM(
        CASE
            WHEN tipo_modicacao = 1 THEN quantidade
            WHEN tipo_modicacao = 0 THEN -quantidade
            ELSE quantidade
        END
    ) AS saldo_movimentacoes
FROM movimentacoes
WHERE id_produto = 2
GROUP BY id_produto;

-- 8. Consultar o estoque atualizado do produto ID 2 após a alteração na trigger
SELECT id_produto, nome_produto, quantidade_produto 
FROM produtos 
WHERE id_produto = 2;