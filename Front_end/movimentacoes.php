<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Movimentações — Tera Atacado">
    <title>Movimentações | Tera Atacado</title>
    <link rel="icon" href="Img/tera_logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/tela_principal.css">
    <link rel="stylesheet" href="CSS/movimentacoes.css">
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
                <nav aria-label="Menu principal">
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
                        <a href="#" class="menu-item">
                            <span>▤</span>
                            <span>Categorias</span>
                        </a>
                        <a href="#" class="menu-item">
                            <span>▥</span>
                            <span>Fornecedores</span>
                        </a>
                    </div>
                    <div class="menu-section">
                        <h3>ESTOQUE</h3>
                        <a href="#" class="menu-item">
                            <span>＋</span>
                            <span>Entradas</span>
                        </a>
                        <a href="#" class="menu-item">
                            <span>−</span>
                            <span>Saídas</span>
                        </a>
                        <a href="movimentacoes.html" class="menu-item active" aria-current="page">
                            <span>↕</span>
                            <span>Movimentações</span>
                        </a>
                    </div>
                    <div class="menu-section">
                        <h3>GESTÃO</h3>
                        <a href="#" class="menu-item">
                            <span>▥</span>
                            <span>Relatórios</span>
                        </a>
                    </div>
                    <div class="menu-section">
                        <h3>CONTA</h3>
                        <a href="#" class="menu-item">
                            <span>◎</span>
                            <span>Meu perfil</span>
                        </a>
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
                    <h1>Movimentações</h1>
                    <p class="page-subtitle">Consulte o histórico de entradas e saídas do estoque.</p>
                </div>
            </div>
        

            <section class="metric-grid metric-grid--four" aria-label="Resumo das movimentações">
                <article class="metric-card">
                    <span class="metric-card__label">Movimentações listadas</span>
                    <strong class="metric-card__value" id="metric-mov-total">8</strong>
                    <span class="metric-card__foot">Registros demonstrativos</span>
                    <span class="metric-card__icon metric-card__icon--green" aria-hidden="true">↕</span>
                </article>
                <article class="metric-card">
                    <span class="metric-card__label">Entradas</span>
                    <strong class="metric-card__value" id="metric-mov-entries">5</strong>
                    <span class="metric-card__foot">Unidades recebidas: 290</span>
                    <span class="metric-card__icon metric-card__icon--green" aria-hidden="true">＋</span>
                </article>
                <article class="metric-card">
                    <span class="metric-card__label">Saídas</span>
                    <strong class="metric-card__value" id="metric-mov-exits">3</strong>
                    <span class="metric-card__foot">Unidades retiradas: 34</span>
                    <span class="metric-card__icon metric-card__icon--orange" aria-hidden="true">−</span>
                </article>
            </section>

            <section class="data-panel" aria-labelledby="movement-list-title">
                <div class="data-panel__heading">
                    <div>
                        <h2 id="movement-list-title">Histórico de movimentações</h2>
                        <p>Acompanhe entradas e saídas recentes do estoque.</p>
                    </div>
                    <button class="button-secondary" id="export-movements" type="button">
                        <span aria-hidden="true">↓</span> Exportar CSV
                    </button>
                </div>
                <div class="filters-row">
                    <label class="search-field" for="movement-search">
                        <span aria-hidden="true">⌕</span>
                        <input type="search" id="movement-search" placeholder="Buscar produto, usuário ou observação..." autocomplete="off">
                    </label>
                    <label class="filter-field" for="movement-type-filter">
                        <span>Tipo</span>
                        <select id="movement-type-filter">
                            <option value="">Todas as movimentações</option>
                            <option value="entrada">Somente entradas</option>
                            <option value="saida">Somente saídas</option>
                        </select>
                    </label>
                    <button class="text-button" id="clear-movement-filters" type="button">Limpar filtros</button>
                </div>
                <div class="table-wrap">
                    <table class="data-table movement-table" id="movements-table">
                        <thead>
                            <tr>
                                <th scope="col">Data e hora</th>
                                <th scope="col">Produto</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Quantidade</th>
                                <th scope="col">Usuário</th>
                                <th scope="col">Observação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr data-type="entrada"><td><time datetime="2026-10-15T14:32">15/10/2026 · 14:32</time></td><td><strong>Arroz 5kg</strong></td><td><span class="status-pill status-pill--ok">Entrada</span></td><td class="numeric-cell quantity-entry">+50 un.</td><td>Operador demo</td><td>Reposição de estoque</td></tr>
                            <tr data-type="saida"><td><time datetime="2026-10-15T13:18">15/10/2026 · 13:18</time></td><td><strong>Feijão 1kg</strong></td><td><span class="status-pill status-pill--low">Saída</span></td><td class="numeric-cell quantity-exit">−10 un.</td><td>Operador demo</td><td>Separação de pedido</td></tr>
                            <tr data-type="entrada"><td><time datetime="2026-10-14T16:44">14/10/2026 · 16:44</time></td><td><strong>Macarrão 500g</strong></td><td><span class="status-pill status-pill--ok">Entrada</span></td><td class="numeric-cell quantity-entry">+100 un.</td><td>Operador demo</td><td>Recebimento de mercadoria</td></tr>
                            <tr data-type="saida"><td><time datetime="2026-10-14T10:20">14/10/2026 · 10:20</time></td><td><strong>Café 500g</strong></td><td><span class="status-pill status-pill--low">Saída</span></td><td class="numeric-cell quantity-exit">−12 un.</td><td>Operador demo</td><td>Separação de pedido</td></tr>
                            <tr data-type="entrada"><td><time datetime="2026-10-13T15:05">13/10/2026 · 15:05</time></td><td><strong>Leite 1L</strong></td><td><span class="status-pill status-pill--ok">Entrada</span></td><td class="numeric-cell quantity-entry">+80 un.</td><td>Operador demo</td><td>Reposição de estoque</td></tr>
                            <tr data-type="entrada"><td><time datetime="2026-10-13T11:40">13/10/2026 · 11:40</time></td><td><strong>Água mineral 1,5L</strong></td><td><span class="status-pill status-pill--ok">Entrada</span></td><td class="numeric-cell quantity-entry">+30 un.</td><td>Operador demo</td><td>Recebimento de mercadoria</td></tr>
                            <tr data-type="saida"><td><time datetime="2026-10-12T09:25">12/10/2026 · 09:25</time></td><td><strong>Detergente 500ml</strong></td><td><span class="status-pill status-pill--low">Saída</span></td><td class="numeric-cell quantity-exit">−12 un.</td><td>Operador demo</td><td>Separação de pedido</td></tr>
                            <tr data-type="entrada"><td><time datetime="2026-10-11T17:03">11/10/2026 · 17:03</time></td><td><strong>Farinha de trigo</strong></td><td><span class="status-pill status-pill--ok">Entrada</span></td><td class="numeric-cell quantity-entry">+30 un.</td><td>Operador demo</td><td>Reposição de estoque</td></tr>
                        </tbody>
                    </table>
                    <p class="empty-state" id="movements-empty" hidden>Nenhuma movimentação corresponde aos filtros selecionados.</p>
                </div>
                <div class="table-footer"><span id="movements-visible-count">Exibindo 8 movimentações</span><span>Mais recentes primeiro</span></div>
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
<script src="JAVASCRIPT/movimentacoes.js" defer></script>
</body>
</html>
