<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Gerencie as categorias de produtos do Tera Atacado.">
    <title>Categorias | Tera Atacado</title>
    <link rel="icon" href="Img/tera_logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/tela_principal.css">
    <link rel="stylesheet" href="CSS/categoria.css">
</head>
<body>
<header class="header">
    <div class="logodiv"><img src="Img/tera_logo.png" class="logo" alt="Logo Tera Atacado"></div>
    <p class="texto">Seja bem-vindo ao sistema de gerenciamento<br>de estoque do <span class="destaque">Tera Atacado</span></p>
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
<main class="module-main">

<div class="module-heading">
    <div>
        <p class="module-eyebrow">TERA ATACADO / CADASTROS</p>
        <h1>Categorias</h1>
        <p class="module-subtitle">Organize os produtos por categoria para facilitar a consulta e os relatórios.</p>
    </div>
    <a href="#category-create" class="module-button module-button--primary"><span aria-hidden="true">＋</span> Nova categoria</a>
</div>
<section class="module-panel" aria-labelledby="category-list-title">
    <div class="module-panel__header">
        <div><h2 id="category-list-title">Categorias cadastradas</h2></div>
        <span class="module-count" id="category-count">3 categorias</span>
    </div>
    <div class="module-toolbar">
        <label class="module-search" for="category-search"><span aria-hidden="true">⌕</span><input id="category-search" type="search" placeholder="Buscar categoria..." autocomplete="off"></label>
    </div>
    <div class="module-table-wrap">
        <table class="module-table" id="categories-table">
            <thead><tr><th scope="col">Categoria</th><th scope="col">Quantidade de produtos</th><th scope="col">Data de criação</th><th scope="col">Ações</th></tr></thead>
            <tbody>
                <tr><td><strong>Alimentos</strong></td><td>6</td><td>12/01/2026</td><td><div class="module-actions"><button type="button" class="icon-button" data-action="edit-category" aria-label="Editar Alimentos" title="Editar">✎</button><button type="button" class="icon-button icon-button--danger" data-action="delete-category" aria-label="Excluir Alimentos" title="Excluir">×</button></div></td></tr>
                <tr><td><strong>Bebidas</strong></td><td>3</td><td>15/01/2026</td><td><div class="module-actions"><button type="button" class="icon-button" data-action="edit-category" aria-label="Editar Bebidas" title="Editar">✎</button><button type="button" class="icon-button icon-button--danger" data-action="delete-category" aria-label="Excluir Bebidas" title="Excluir">×</button></div></td></tr>
                <tr><td><strong>Laticínios</strong></td><td>1</td><td>18/01/2026</td><td><div class="module-actions"><button type="button" class="icon-button" data-action="edit-category" aria-label="Editar Laticínios" title="Editar">✎</button><button type="button" class="icon-button icon-button--danger" data-action="delete-category" aria-label="Excluir Laticínios" title="Excluir">×</button></div></td></tr>
            </tbody>
        </table>
        <p class="module-empty" id="category-empty" hidden>Nenhuma categoria corresponde à pesquisa.</p>
    </div>
</section>
<section class="module-panel module-create-panel" id="category-create" aria-labelledby="category-create-title">
    <div class="module-panel__header"><div><h2 id="category-create-title">Adicionar categoria</h2><p>Preencha os campos para visualizar a nova categoria na tabela.</p></div></div>
    <form id="category-form" class="module-form">
        <div class="module-form__grid module-form__grid--three">
            <div class="module-field"><label for="category-name">Nome da categoria</label><input id="category-name" name="nome_categoria" type="text" maxlength="100" placeholder="Ex.: Limpeza" required></div>
            <div class="module-field"><label for="category-quantity">Quantidade de produtos</label><input id="category-quantity" name="quantidade_categoria" type="number" min="0" value="0" required></div>
            <div class="module-field"><label for="category-date">Data de criação</label><input id="category-date" name="data_criacao" type="date" required></div>
        </div>
        <div class="module-form__footer"><div class="module-actions"><button class="module-button module-button--secondary" type="reset">Limpar</button><button class="module-button module-button--primary" type="submit">Adicionar à prévia</button></div></div>
        <p class="module-feedback" id="category-feedback" role="status" hidden></p>
    </form>
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
<script src="JAVASCRIPT/categorias.js" defer></script>
</body>
</html>
