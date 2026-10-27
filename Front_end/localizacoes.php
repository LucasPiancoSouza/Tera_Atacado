<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Visualize onde os produtos estão armazenados no estoque do Tera Atacado.">
    <title>Localização de produtos | Tera Atacado</title>
    <link rel="icon" href="Img/tera_logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/tela_principal.css">
    <link rel="stylesheet" href="CSS/localizacoes.css">
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
                        <a href="tela_principal.php" class="menu-item"><span>⌂</span><span>Dashboard</span></a>
                        <a href="produtos.php" class="menu-item"><span>▦</span><span>Produtos</span></a>
                        <a href="categoria.php" class="menu-item"><span>▤</span><span>Categorias</span></a>
                        <a href="fornecedores.php" class="menu-item"><span>▥</span><span>Fornecedores</span></a>
                    </div>
                    <div class="menu-section">
                        <h3>ESTOQUE</h3>
                        <a href="entrada.php" class="menu-item"><span>＋</span><span>Entradas</span></a>
                        <a href="saida.php" class="menu-item"><span>−</span><span>Saídas</span></a>
                        <a href="movimentacoes.php" class="menu-item"><span>↕</span><span>Movimentações</span></a>
                        <a href="localizacoes.php" class="menu-item"><span>⌖</span><span>Localizações</span></a>
                    </div>
                    <div class="menu-section">
                        <h3>GESTÃO</h3>
                        <a href="relatorio.php" class="menu-item"><span>▥</span><span>Relatórios</span></a>
                    </div>
                    <div class="menu-section">
                        <h3>CONTA</h3>
                        <a href="#confirmar-saida" class="menu-item" aria-haspopup="dialog"><span>↪</span><span>Sair</span></a>
                    </div>
                </nav>
            </div>
        </aside>

        <main class="module-main">
            <div class="module-heading">
                <div>
                    <p class="module-eyebrow">TERA ATACADO / ESTOQUE</p>
                    <h1>Localização de produtos</h1>
                    <p class="module-subtitle">Encontre rapidamente o setor, corredor e posição de cada produto no estoque.</p>
                </div>
                <span class="location-demo-badge"><span aria-hidden="true">●</span> Dados demonstrativos</span>
            </div>

            <section class="location-summary" aria-label="Resumo demonstrativo do estoque">
                <article class="location-summary__card">
                    <span class="location-summary__icon location-summary__icon--green" aria-hidden="true">▦</span>
                    <div><p>Produtos localizados</p><strong>128</strong><span>em 4 setores</span></div>
                </article>
                <article class="location-summary__card">
                    <span class="location-summary__icon location-summary__icon--orange" aria-hidden="true">⌖</span>
                    <div><p>Posições ocupadas</p><strong>86</strong><span>de 120 posições</span></div>
                </article>
                <article class="location-summary__card">
                    <span class="location-summary__icon location-summary__icon--blue" aria-hidden="true">□</span>
                    <div><p>Unidades armazenadas</p><strong>2.460</strong><span>somando as posições</span></div>
                </article>
            </section>

            <section class="module-panel location-feature" aria-labelledby="location-feature-title">
                <div class="module-panel__header">
                    <div>
                        <p class="module-eyebrow">EXEMPLO DE LOCALIZAÇÃO</p>
                        <h2 id="location-feature-title">Onde encontrar um produto</h2>
                    </div>
                    <span class="module-panel__badge">Produto em estoque</span>
                </div>
                <div class="location-feature__content">
                    <div class="location-product">
                        <span class="location-product__icon" aria-hidden="true">▤</span>
                        <div>
                            <span class="location-product__label">Produto</span>
                            <h3>Arroz Tipo 1 5 kg</h3>
                            <p>Código <strong>ALI-001</strong> <span aria-hidden="true">·</span> Alimentos</p>
                        </div>
                    </div>
                    <div class="location-route" aria-label="Localização: setor A, corredor 02, prateleira 03, andar 2">
                        <div><span>Setor</span><strong>A</strong></div>
                        <span class="location-route__separator" aria-hidden="true">›</span>
                        <div><span>Corredor</span><strong>02</strong></div>
                        <span class="location-route__separator" aria-hidden="true">›</span>
                        <div><span>Prateleira</span><strong>03</strong></div>
                        <span class="location-route__separator" aria-hidden="true">›</span>
                        <div><span>Andar</span><strong>2</strong></div>
                    </div>
                    <div class="location-quantity">
                        <span>Quantidade nesta posição</span>
                        <strong>48 <small>unidades</small></strong>
                    </div>
                </div>
            </section>

            <section class="module-panel" aria-labelledby="location-table-title">
                <div class="module-panel__header">
                    <div>
                        <h2 id="location-table-title">Produtos por posição</h2>
                        <p>Exemplos fictícios para demonstrar a organização do estoque.</p>
                    </div>
                    <span class="module-count">6 exemplos</span>
                </div>
                <div class="module-table-wrap">
                    <table class="module-table module-table--wide location-table">
                        <thead>
                            <tr>
                                <th scope="col">Produto</th>
                                <th scope="col">Código</th>
                                <th scope="col">Setor</th>
                                <th scope="col">Corredor</th>
                                <th scope="col">Prateleira</th>
                                <th scope="col">Andar</th>
                                <th scope="col">Quantidade</th>
                                <th scope="col">Disponibilidade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Arroz Tipo 1 5 kg</strong><span class="location-table__category">Alimentos</span></td>
                                <td>ALI-001</td><td><span class="location-sector">A</span></td><td>02</td><td>03</td><td>2</td><td>48 un.</td>
                                <td><span class="location-status location-status--available">Disponível</span></td>
                            </tr>
                            <tr>
                                <td><strong>Feijão Carioca 1 kg</strong><span class="location-table__category">Alimentos</span></td>
                                <td>ALI-014</td><td><span class="location-sector">A</span></td><td>02</td><td>03</td><td>1</td><td>32 un.</td>
                                <td><span class="location-status location-status--available">Disponível</span></td>
                            </tr>
                            <tr>
                                <td><strong>Óleo de Soja 900 ml</strong><span class="location-table__category">Mercearia</span></td>
                                <td>MER-008</td><td><span class="location-sector">A</span></td><td>04</td><td>01</td><td>2</td><td>6 un.</td>
                                <td><span class="location-status location-status--low">Estoque baixo</span></td>
                            </tr>
                            <tr>
                                <td><strong>Detergente Neutro 500 ml</strong><span class="location-table__category">Limpeza</span></td>
                                <td>LIM-022</td><td><span class="location-sector location-sector--orange">B</span></td><td>01</td><td>04</td><td>1</td><td>75 un.</td>
                                <td><span class="location-status location-status--available">Disponível</span></td>
                            </tr>
                            <tr>
                                <td><strong>Leite Integral 1 L</strong><span class="location-table__category">Laticínios</span></td>
                                <td>LAT-003</td><td><span class="location-sector location-sector--orange">B</span></td><td>03</td><td>02</td><td>1</td><td>24 un.</td>
                                <td><span class="location-status location-status--available">Disponível</span></td>
                            </tr>
                            <tr>
                                <td><strong>Café Tradicional 500 g</strong><span class="location-table__category">Alimentos</span></td>
                                <td>ALI-031</td><td><span class="location-sector location-sector--blue">C</span></td><td>01</td><td>02</td><td>3</td><td>0 un.</td>
                                <td><span class="location-status location-status--empty">Sem estoque</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="location-table__note"><span aria-hidden="true">i</span> Os dados exibidos são fictícios e servem somente para visualizar a proposta da tela.</p>
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
    <script src="JAVASCRIPT/menu-ativo.js"></script>
</body>
</html>
