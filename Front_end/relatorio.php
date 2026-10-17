<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Consulte e exporte relatórios demonstrativos do Tera Atacado.">
    <title>Relatórios | Tera Atacado</title>
    <link rel="icon" href="Img/tera_logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/tela_principal.css">
    <link rel="stylesheet" href="CSS/telas_adicionais.css">
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
    <div><p class="module-eyebrow">TERA ATACADO / GESTÃO</p><h1>Relatórios</h1><p class="module-subtitle">Escolha um tipo de relatório e aplique os filtros disponíveis.</p></div>
    
</div>
<section class="report-type-grid" aria-label="Tipos de relatório">
    <button type="button" class="report-type-card is-selected" data-report="produtos" aria-pressed="true"><span class="report-type-card__icon">▦</span><span class="report-type-card__title">Produtos</span><span class="report-type-card__description">Catálogo e situação de estoque.</span><span class="report-type-card__select">Selecionado</span></button>
    <button type="button" class="report-type-card" data-report="entradas" aria-pressed="false"><span class="report-type-card__icon">＋</span><span class="report-type-card__title">Entradas</span><span class="report-type-card__description">Produtos recebidos no período.</span><span class="report-type-card__select">Selecionar</span></button>
    <button type="button" class="report-type-card" data-report="saidas" aria-pressed="false"><span class="report-type-card__icon">−</span><span class="report-type-card__title">Saídas</span><span class="report-type-card__description">Produtos retirados do estoque.</span><span class="report-type-card__select">Selecionar</span></button>
    <button type="button" class="report-type-card" data-report="estoque-baixo" aria-pressed="false"><span class="report-type-card__icon">!</span><span class="report-type-card__title">Estoque baixo</span><span class="report-type-card__description">Itens que precisam de reposição.</span><span class="report-type-card__select">Selecionar</span></button>
    <button type="button" class="report-type-card" data-report="movimentacoes" aria-pressed="false"><span class="report-type-card__icon">↕</span><span class="report-type-card__title">Movimentações</span><span class="report-type-card__description">Histórico de entradas e saídas.</span><span class="report-type-card__select">Selecionar</span></button>
</section>
<section class="module-panel" aria-labelledby="report-filter-title">
    <div class="module-panel__header"><div><h2 id="report-filter-title">Filtros do relatório</h2></div></div>
    <form id="report-form" class="module-form">
        <div class="module-form__grid module-form__grid--three">
            <div class="module-field"><label for="report-start">Data inicial</label><input type="date" id="report-start" name="dataInicial" value="2026-08-01"></div>
            <div class="module-field"><label for="report-end">Data final</label><input type="date" id="report-end" name="dataFinal" value="2026-10-31"></div>
            <div class="module-field"><label for="report-category">Categoria</label><select id="report-category" name="categoria"><option value="">Todas as categorias</option><option value="Alimentos">Alimentos</option><option value="Bebidas">Bebidas</option><option value="Limpeza">Limpeza</option><option value="Laticínios">Laticínios</option></select></div>
            <div class="module-field"><label for="report-product">Produto</label><select id="report-product" name="produto"><option value="">Todos os produtos</option><option value="Arroz 5kg">Arroz 5kg</option><option value="Feijão 1kg">Feijão 1kg</option><option value="Café 500g">Café 500g</option><option value="Água mineral 1,5L">Água mineral 1,5L</option><option value="Leite 1L">Leite 1L</option></select></div>
            <div class="module-field"><label for="report-type">Tipo de movimentação</label><select id="report-type" name="tipo"><option value="">Entradas e saídas</option><option value="entrada">Entrada</option><option value="saida">Saída</option></select></div>
        </div>
        <div class="module-form__footer"><div class="module-actions"><button type="reset" class="module-button module-button--secondary">Limpar filtros</button><button type="button" id="report-preview-button" class="module-button module-button--primary">Visualizar relatório</button><button type="button" id="report-export-csv" class="module-button module-button--secondary">Exportar CSV</button><button type="button" id="report-print" class="module-button module-button--outline-orange">Imprimir / salvar PDF</button></div></div>
    </form>
    <p class="module-feedback" id="report-feedback" role="status" hidden></p>
</section>
<section class="module-panel" id="report-results" aria-labelledby="report-results-title" hidden>
    <div class="module-panel__header"><div><h2 id="report-results-title">Relatório de produtos</h2><p id="report-results-description">Resultado conforme os filtros escolhidos.</p></div><span class="module-count" id="report-results-count">0 registros</span></div>
    <div class="module-table-wrap"><table class="module-table" id="report-results-table"><thead></thead><tbody></tbody></table><p class="module-empty" id="report-empty" hidden>Nenhum registro corresponde aos filtros selecionados.</p></div>
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
<script src="JAVASCRIPT/relatorios.js" defer></script>
</body>
</html>
