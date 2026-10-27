<?php
require_once __DIR__ . '/../Back_end/config/database.php';

$sql_produtos = "
    SELECT
        p.id_produto,
        p.nome_produto,
        p.codigo_produto,
        p.preco,
        p.estoque_minimo,
        p.id_fornecedor,
        f.nome_fornecedor,
        p.id_categoria,
        c.nome_categoria,
        p.codigo_de_barras
    FROM produtos AS p
    INNER JOIN categorias AS c
        ON c.id_categoria = p.id_categoria
    INNER JOIN fornecedores AS f
        ON f.id_fornecedor = p.id_fornecedor
";

$stmt = mysqli_prepare($con, $sql_produtos);
if (!$stmt) {
    die('Erro ao preparar a consulta de produtos: ' . mysqli_error($con));
}

if (!mysqli_stmt_execute($stmt)) {
    die('Erro ao consultar produtos: ' . mysqli_stmt_error($stmt));
}

$resultado = mysqli_stmt_get_result($stmt);
if (!$resultado) {
    die('Erro ao obter os produtos consultados: ' . mysqli_stmt_error($stmt));
}

$produtos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

$sql_categorias = "SELECT id_categoria, nome_categoria FROM categorias"; 
$resultado_categorias = mysqli_query($con, $sql_categorias);

$sql_fornecedores = "SELECT id_fornecedor, nome_fornecedor FROM fornecedores"; 
$resultado_fornecedores = mysqli_query($con, $sql_fornecedores);


?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Produtos — Tera Atacado">
    <title>Produtos | Tera Atacado</title>
    <link rel="icon" href="Img/tera_logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/tela_principal.css">
    <link rel="stylesheet" href="CSS/produtos.css">
</head>
<body>
    <header class="header">
        <div class="logodiv">
            <img src="Img/tera_logo.png" class="logo" alt="Logo Tera Atacado">
        </div>
        <p class="texto">
            Seja bem-vindo ao sistema de gerenciamento<br>
            de estoque do <span class="destaque">Tera Atacado</span>
        </p>
    </header>
    <div class="layout">
<aside class="sidebar">
            <div class="sidebar-header">
                <nav>
                    <div class="menu-section">
                        <h3>PRINCIPAL</h3>
                        <a href="tela_principal.php" class="menu-item">
                            <span>⌂</span>
                            <span>Dashboard</span>
                        </a>
                        <a href="produtos.php" class="menu-item">
                            <span>▦</span>
                            <span>Produtos</span>
                        </a>
                        <a href="categoria.php" class="menu-item">
                            <span>▤</span>
                            <span>Categorias</span>
                        </a>
                        <a href="fornecedores.php" class="menu-item">
                            <span>▥</span>
                            <span>Fornecedores</span>
                        </a>
                    </div>
                    <div class="menu-section">
                        <h3>ESTOQUE</h3>
                        <a href="entrada.php" class="menu-item">
                            <span>＋</span>
                            <span>Entradas</span>
                        </a>
                        <a href="saida.php" class="menu-item">
                            <span>−</span>
                            <span>Saídas</span>
                        </a>
                        <a href="movimentacoes.php" class="menu-item">
                            <span>↕</span>
                            <span>Movimentações</span>
                        </a>
                    </div>
                    <div class="menu-section">
                        <h3>GESTÃO</h3>
                        <a href="relatorio.php" class="menu-item">
                            <span>▥</span>
                            <span>Relatórios</span>
                        </a>
                    </div>
                    <div class="menu-section">
                        <h3>CONTA</h3>
                        <a href="#confirmar-saida" class="menu-item" aria-haspopup="dialog">
                            <span>↪</span>
                            <span>Sair</span>
                        </a>
                    </div>
                </nav>
            </div>
        </aside>
        <main class="page-main">
            <div class="page-heading">
                <div>
                    <p class="page-eyebrow">TERA ATACADO / ESTOQUE</p>
                    <h1>Produtos</h1>
                    <p class="page-subtitle">Consulte o catálogo e acompanhe a situação do estoque.</p>
                </div>
            </div>
<!-- Cadastro de novo produto -->
<details class="product-form-panel">

    <summary class="product-form-panel__summary">

        <div class="product-form-panel__heading">
            <span class="product-form-panel__eyebrow">
                CATÁLOGO DE PRODUTOS
            </span>

            <span class="product-form-panel__title">
                Cadastrar novo produto
            </span>

            <span class="product-form-panel__description">
                Preencha os dados para adicionar um produto ao catálogo.
            </span>
        </div>

        <span class="product-form-panel__action">
            <span aria-hidden="true">＋</span>
            Novo produto
        </span>

    </summary>


<?php if (isset($_GET['sucesso'])): ?>
    <div style="background-color: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
        Produto cadastrado com sucesso!
    </div>
<?php endif; ?>

<?php if (isset($_GET['erro'])): ?>
    <div style="background-color: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
        <?php 
            if ($_GET['erro'] === 'campos_vazios') {
                echo 'Por favor, preencha todos os campos obrigatórios.';
            } else {
                echo 'Ocorreu um erro ao cadastrar o produto. Tente novamente.';
            }
        ?>
    </div>
<?php endif; ?>


<!-- Formulário com o action corrigido -->
<form class="product-form" action="/Back_end/processamentos/cadastro_produto.php" method="POST">
        <div class="product-form__grid">

            <div class="product-form__field">
                <label for="nome_produto">Nome do produto</label>

                <input
                    type="text"
                    id="nome_produto"
                    name="nome_produto"
                    maxlength="100"
                    placeholder="Ex.: Arroz 5kg"
                    required
                >
            </div>

            <div class="product-form__field">
                <label for="codigo_produto">Código do produto</label>

                <input
                    type="text"
                    id="codigo_produto"
                    name="codigo_produto"
                    maxlength="13"
                    placeholder="Ex.: ALI-001"
                    required
                >
            </div>

            <div class="product-form__field">
                <label for="preco">Preço (R$)</label>

                <input
                    type="number"
                    id="preco"
                    name="preco"
                    min="0.01"
                    step="0.01"
                    placeholder="0,00"
                    required
                >
            </div>

            <div class="product-form__field">
                <label for="estoque_minimo">Estoque mínimo (unidades)</label>

                <input
                    type="number"
                    id="estoque_minimo"
                    name="estoque_minimo"
                    min="0"
                    step="1"
                    placeholder="Ex.: 10"
                    required
                >
            </div>

            <div class="product-form__field">
                <label for="codigo_de_barras">Código de barras</label>

                <input
                    type="text"
                    id="codigo_de_barras"
                    name="codigo_de_barras"
                    maxlength="128"
                    placeholder="Informe o código de barras"
                    required
                >
            </div>

            <div class="product-form__field">
                <label for="id_categoria">Categoria</label>

                <select name="id_categoria" required>
    <option value="">Selecione uma categoria</option>
    <?php if ($resultado_categorias): ?>
        <?php while ($cat = mysqli_fetch_assoc($resultado_categorias)): ?>
            <option value="<?= $cat['id_categoria'] ?>">
                <?= htmlspecialchars($cat['nome_categoria']) ?>
            </option>
        <?php endwhile; ?>
    <?php endif; ?>
</select>
            </div>

            <div class="product-form__field">
                <label for="id_fornecedor">Fornecedor</label>

                <select name="id_fornecedor" required>
    <option value="">Selecione um fornecedor</option>
    <?php if ($resultado_fornecedores): ?>
        <?php while ($forn = mysqli_fetch_assoc($resultado_fornecedores)): ?>
            <option value="<?= $forn['id_fornecedor'] ?>">
                <?= htmlspecialchars($forn['nome_fornecedor']) ?>
            </option>
        <?php endwhile; ?>
    <?php endif; ?>
</select>
            </div>

        </div>

        <div class="product-form__footer">

            <p class="product-form__note">
                Os campos marcados como obrigatórios devem ser preenchidos.
            </p>

            <div class="product-form__buttons">

                <button type="reset" class="product-form__clear">
                    Limpar campos
                </button>

                <button type="submit" class="product-form__submit">
                    Cadastrar produto
                </button>

            </div>

        </div>

    </form>

</details>
            <section class="metric-grid" aria-label="Resumo dos produtos">
                <article class="metric-card">
                    <span class="metric-card__label">Produtos listados</span>
                    <strong class="metric-card__value" id="metric-total">
                    <?php 
                        $sql = "Select * from produtos";
                        $stmt = mysqli_prepare($con, $sql);
                        mysqli_stmt_execute($stmt);
                        $resultado = mysqli_stmt_get_result($stmt);
                        echo mysqli_num_rows($resultado);
                     ?>
                     </strong>
                    <span class="metric-card__icon metric-card__icon--green" aria-hidden="true">▦</span>
                </article>
                <article class="metric-card">
                    <span class="metric-card__label">Estoque baixo</span>
                    <strong class="metric-card__value" id="metric-low">
                        <?php  
                                
                        $sqlProdutosEstoqueBaixo = "
                            SELECT
                                p.id_produto,
                                p.nome_produto,
                                SUM(lp.quantidade_produto) AS total_estoque,
                                p.estoque_minimo
                            FROM produtos p
                            INNER JOIN localizacao_produto lp
                                ON p.id_produto = lp.id_produto
                            GROUP BY p.id_produto, p.nome_produto
                            HAVING SUM(lp.quantidade_produto) < p.estoque_minimo 
                            and SUM(lp.quantidade_produto) > 0
                        ";
                        $stmtProdutosEstoqueBaixo = mysqli_prepare(
                            $con,
                            $sqlProdutosEstoqueBaixo
                        );
                        mysqli_stmt_execute($stmtProdutosEstoqueBaixo);
                        $resultadoProdutosEstoqueBaixo = mysqli_stmt_get_result(
                            $stmtProdutosEstoqueBaixo
                        );
                        echo mysqli_num_rows(
                            $resultadoProdutosEstoqueBaixo
                        );
                        ?>
                    </strong>
                    <span class="metric-card__icon metric-card__icon--orange" aria-hidden="true">!</span>
                </article>
                <article class="metric-card">
                    <span class="metric-card__label">Sem estoque</span>
                    <strong class="metric-card__value" id="metric-out">
                        <?php  
                             $sqlProdutosSemEstoque = "
                            SELECT
                                p.id_produto,
                                p.nome_produto,
                                SUM(lp.quantidade_produto) AS total_estoque
                            FROM produtos p
                            INNER JOIN localizacao_produto lp
                                ON p.id_produto = lp.id_produto
                            GROUP BY p.id_produto, p.nome_produto
                            HAVING SUM(lp.quantidade_produto) = 0

                        ";

                        $stmtProdutosSemEstoque = mysqli_prepare(
                            $con,
                            $sqlProdutosSemEstoque
                        );
                        mysqli_stmt_execute($stmtProdutosSemEstoque);
                        $resultadoProdutosSemEstoque = mysqli_stmt_get_result(
                            $stmtProdutosSemEstoque
                        );

                        echo mysqli_num_rows(
                            $resultadoProdutosSemEstoque
                        );
                        ?>
                    </strong>
                    <span class="metric-card__icon metric-card__icon--brown" aria-hidden="true">−</span>
                </article>
            </section>

            <section class="data-panel" aria-labelledby="product-list-title">
                <div class="data-panel__heading">
                    <div>
                        <h2 id="product-list-title">Catálogo de produtos</h2>
                        <p>Pesquise e filtre os itens cadastrados.</p>
                    </div>
                    <button class="button-secondary" id="export-products" type="button">
                        <span aria-hidden="true">↓</span> Exportar CSV
                    </button>
                </div>
                <div class="filters-row">
                    <label class="search-field" for="product-search">
                        <span aria-hidden="true">⌕</span>
                        <input type="search" id="product-search" placeholder="Buscar produto, código ou fornecedor..." autocomplete="off">
                    </label>
                    <label class="filter-field" for="category-filter">
                        <span>Categoria</span>
                        <select id="category-filter">
                            <option value="">Selecione uma categoria</option>
                            <?php
                            $stmt = mysqli_prepare($con, $sql_categorias);
                            mysqli_stmt_execute($stmt);
                            $resultado_categorias = mysqli_stmt_get_result($stmt);
                            if ($resultado_categorias): ?>
                                <?php while ($cat = mysqli_fetch_assoc($resultado_categorias)): ?>
                                    <option value="<?= $cat['id_categoria'] ?>">
                                        <?= htmlspecialchars($cat['nome_categoria']) ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </label>

                    <button class="text-button" id="clear-product-filters" type="button">Limpar filtros</button>
                </div>
                <div class="table-wrap">
                    <table class="data-table" id="products-table">
    <thead>
        <tr>
            <th>Código</th>
            <th>Produto</th>
            <th>Preço</th>
            <th>Fornecedor</th>
            <th>Categoria</th>
            <th>Cód. de Barras</th>
            <th>Estoque mínimo</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($produtos)): ?>
            <?php foreach ($produtos as $linha): ?>
                <tr data-categoria-id="<?= (int) $linha['id_categoria'] ?>">
                    <td><?= htmlspecialchars($linha['codigo_produto']) ?></td>
                    <td><?= htmlspecialchars($linha['nome_produto']) ?></td>
                    <td>R$ <?= number_format($linha['preco'], 2, ',', '.') ?></td>
                    <td><?= htmlspecialchars($linha['nome_fornecedor']) ?></td>
                    <td><?= htmlspecialchars($linha['nome_categoria']) ?></td>
                    <td><?= htmlspecialchars($linha['codigo_de_barras']) ?></td>
                    <td><?= (int) $linha['estoque_minimo'] ?> un.</td>
                    <td>
                        <div class="module-actions">
                            <button
                                type="button"
                                class="icon-button"
                                data-id="<?= (int) $linha['id_produto'] ?>"
                                title="Editar produto"
                                aria-label="Editar produto <?= htmlspecialchars($linha['nome_produto']) ?>"
                            >✎ <span>Editar</span></button>
                            <button
                                type="button"
                                class="icon-button icon-button--danger"
                                data-action="delete-product"
                                data-id="<?= (int) $linha['id_produto'] ?>"
                                data-nome="<?= htmlspecialchars($linha['nome_produto'], ENT_QUOTES, 'UTF-8') ?>"
                                title="Excluir produto"
                                aria-label="Excluir produto <?= htmlspecialchars($linha['nome_produto']) ?>"
                            >× <span>Excluir</span></button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">Nenhum produto encontrado.</td>
            </tr>
        <?php endif; ?>
        
    </tbody>
</table>
                    <p class="empty-state" id="products-empty" hidden>Nenhum produto corresponde aos filtros selecionados.</p>
                </div>
            </section>
        </main>
    </div>
    <div class="logout-modal" id="confirmar-saida" role="dialog" aria-modal="true" aria-labelledby="logout-modal-title">
        <a class="logout-modal__backdrop" href="#" aria-label="Fechar confirmação de saída"></a>
        <section class="logout-modal__dialog">
            <p class="logout-modal__eyebrow">Tera Atacado</p>
            <h2 class="logout-modal__title" id="logout-modal-title">Deseja sair do sistema?</h2>
            <p class="logout-modal__text">Tem certeza de que deseja encerrar sua sessão agora?</p>
            <div class="logout-modal__actions">
                <a class="logout-modal__button logout-modal__cancel" href="#">Cancelar</a>
                <a class="logout-modal__button logout-modal__confirm" href="logout.php">Sair</a>
            </div>
        </section>
    </div>
    <div class="category-modal category-modal--warning" id="product-delete-modal" hidden>
        <div class="category-modal__backdrop" data-product-modal-cancel aria-hidden="true"></div>
        <section class="category-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="product-delete-modal-title" aria-describedby="product-delete-modal-message">
            <button class="category-modal__close" type="button" data-product-modal-cancel aria-label="Fechar">&times;</button>
            <span class="category-modal__icon" aria-hidden="true">!</span>
            <p class="category-modal__eyebrow">TERA ATACADO</p>
            <h2 class="category-modal__title" id="product-delete-modal-title">Excluir produto?</h2>
            <p class="category-modal__message" id="product-delete-modal-message"></p>
            <div class="category-modal__actions">
                <button class="module-button module-button--secondary" id="product-delete-modal-cancel" type="button">Cancelar</button>
                <button class="module-button module-button--primary category-modal__confirm--danger" id="product-delete-modal-confirm" type="button">Excluir produto</button>
            </div>
        </section>
    </div>
    <div id="popup-sucesso" class="popup-sucesso" role="status" aria-live="polite">
        <div class="popup-icone" aria-hidden="true">✓</div>
        <div>
            <strong id="popup-sucesso-titulo">Produto cadastrado!</strong>
            <p id="popup-sucesso-mensagem">O produto foi cadastrado com sucesso.</p>
        </div>
    </div>
<script src="JAVASCRIPT/menu-ativo.js"></script>
<script src="JAVASCRIPT/produtos.js" defer></script>
</body>
</html>
