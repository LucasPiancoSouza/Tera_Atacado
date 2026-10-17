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
                        <a href="tela_principal.php" class="menu-item active" aria-current="page">
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

    <form
        class="product-form"
        action="produto_cadastrar.php"
        method="post"
    >

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

                <select
                    id="id_categoria"
                    name="id_categoria"
                    required
                >
                    <option value="" selected disabled>
                        Selecione uma categoria
                    </option>
                </select>
            </div>

            <div class="product-form__field">
                <label for="id_fornecedor">Fornecedor</label>

                <select
                    id="id_fornecedor"
                    name="id_fornecedor"
                    required
                >
                    <option value="" selected disabled>
                        Selecione um fornecedor
                    </option>
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
                    <strong class="metric-card__value" id="metric-total">9</strong>
                    <span class="metric-card__foot">No catálogo demonstrativo</span>
                    <span class="metric-card__icon metric-card__icon--green" aria-hidden="true">▦</span>
                </article>
                <article class="metric-card">
                    <span class="metric-card__label">Estoque baixo</span>
                    <strong class="metric-card__value" id="metric-low">3</strong>
                    <span class="metric-card__foot">Entre 1 e 39 unidades</span>
                    <span class="metric-card__icon metric-card__icon--orange" aria-hidden="true">!</span>
                </article>
                <article class="metric-card">
                    <span class="metric-card__label">Sem estoque</span>
                    <strong class="metric-card__value" id="metric-out">2</strong>
                    <span class="metric-card__foot">Precisam de reposição</span>
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
                            <option value="">Todas as categorias</option>
                            <option value="Alimentos">Alimentos</option>
                            <option value="Bebidas">Bebidas</option>
                            <option value="Laticínios">Laticínios</option>
                            <option value="Limpeza">Limpeza</option>
                        </select>
                    </label>
                    <label class="filter-field" for="stock-filter">
                        <span>Estoque</span>
                        <select id="stock-filter">
                            <option value="">Todas as situações</option>
                            <option value="ok">Disponível</option>
                            <option value="low">Estoque baixo</option>
                            <option value="out">Sem estoque</option>
                        </select>
                    </label>
                    <button class="text-button" id="clear-product-filters" type="button">Limpar filtros</button>
                </div>
                <div class="table-wrap">
                    <table class="data-table" id="products-table">
                        <thead>
                            <tr>
                                <th scope="col">Código</th>
                                <th scope="col">Produto</th>
                                <th scope="col">Categoria</th>
                                <th scope="col">Fornecedor</th>
                                <th scope="col">Estoque</th>
                                <th scope="col">Situação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr data-category="Alimentos" data-stock="ok">
                                <td><span class="code-label">ALI-001</span></td><td><strong>Arroz 5kg</strong></td><td>Alimentos</td><td>Distribuidora Central</td><td class="numeric-cell">120 un.</td><td><span class="status-pill status-pill--ok">Disponível</span></td>
                            </tr>
                            <tr data-category="Alimentos" data-stock="low">
                                <td><span class="code-label">ALI-002</span></td><td><strong>Feijão 1kg</strong></td><td>Alimentos</td><td>Distribuidora Central</td><td class="numeric-cell">34 un.</td><td><span class="status-pill status-pill--low">Estoque baixo</span></td>
                            </tr>
                            <tr data-category="Bebidas" data-stock="low">
                                <td><span class="code-label">BEB-005</span></td><td><strong>Café 500g</strong></td><td>Bebidas</td><td>Café do Vale</td><td class="numeric-cell">18 un.</td><td><span class="status-pill status-pill--low">Estoque baixo</span></td>
                            </tr>
                            <tr data-category="Bebidas" data-stock="ok">
                                <td><span class="code-label">BEB-001</span></td><td><strong>Água mineral 1,5L</strong></td><td>Bebidas</td><td>Águas Paulista</td><td class="numeric-cell">310 un.</td><td><span class="status-pill status-pill--ok">Disponível</span></td>
                            </tr>
                            <tr data-category="Alimentos" data-stock="ok">
                                <td><span class="code-label">ALI-004</span></td><td><strong>Macarrão 500g</strong></td><td>Alimentos</td><td>Distribuidora Central</td><td class="numeric-cell">245 un.</td><td><span class="status-pill status-pill--ok">Disponível</span></td>
                            </tr>
                            <tr data-category="Laticínios" data-stock="out">
                                <td><span class="code-label">LAT-001</span></td><td><strong>Leite 1L</strong></td><td>Laticínios</td><td>Laticínios Primavera</td><td class="numeric-cell">0 un.</td><td><span class="status-pill status-pill--out">Sem estoque</span></td>
                            </tr>
                            <tr data-category="Alimentos" data-stock="ok">
                                <td><span class="code-label">ALI-005</span></td><td><strong>Farinha de trigo</strong></td><td>Alimentos</td><td>Moinho Brasil</td><td class="numeric-cell">92 un.</td><td><span class="status-pill status-pill--ok">Disponível</span></td>
                            </tr>
                            <tr data-category="Limpeza" data-stock="low">
                                <td><span class="code-label">LIM-002</span></td><td><strong>Detergente 500ml</strong></td><td>Limpeza</td><td>Higiene Max</td><td class="numeric-cell">27 un.</td><td><span class="status-pill status-pill--low">Estoque baixo</span></td>
                            </tr>
                            <tr data-category="Limpeza" data-stock="out">
                                <td><span class="code-label">LIM-010</span></td><td><strong>Papel toalha</strong></td><td>Limpeza</td><td>Higiene Max</td><td class="numeric-cell">0 un.</td><td><span class="status-pill status-pill--out">Sem estoque</span></td>
                            </tr>
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
<script src="JAVASCRIPT/produtos.js" defer></script>
</body>
</html>
