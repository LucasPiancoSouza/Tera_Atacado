<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Registre a interface de entrada de produtos do Tera Atacado.">
    <title>Entrada de estoque | Tera Atacado</title>
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
    <div><p class="module-eyebrow">TERA ATACADO / ESTOQUE</p><h1>Entrada de estoque</h1><p class="module-subtitle">Informe o produto recebido e confira a prévia da quantidade atualizada.</p></div>
</div>
<div class="module-two-column">
    <section class="module-panel" aria-labelledby="entry-form-title">
        <div class="module-panel__header"><div><h2 id="entry-form-title">Dados da entrada</h2><p>Preencha os campos obrigatórios para simular o registro.</p></div></div>
        <form id="entry-form" class="module-form">
            <div class="module-form__grid">
                <div class="module-field module-field--full"><label for="entry-product">Produto</label><select id="entry-product" name="produto" required><option value="arroz" data-stock="120">Arroz 5kg</option><option value="feijao" data-stock="34">Feijão 1kg</option><option value="cafe" data-stock="18">Café 500g</option><option value="agua" data-stock="310">Água mineral 1,5L</option><option value="leite" data-stock="0">Leite 1L</option></select></div>
                <div class="module-field module-field--full"><label for="entry-location">Local de armazenamento</label><select id="entry-location" name="id_localizacao"><option value="" selected disabled>Selecione o local onde o produto será armazenado</option></select></div>
                <div class="module-field"><label for="entry-quantity">Quantidade recebida</label><input type="number" id="entry-quantity" name="quantidade" min="1" step="1" value="20" required></div>
                <div class="module-field"><label for="entry-date">Data da entrada</label><input type="date" id="entry-date" name="data" required></div>
                <div class="module-field module-field--full"><label for="entry-observation">Observação <span class="field-optional">(opcional)</span></label><textarea id="entry-observation" name="observacao" rows="3" maxlength="500" placeholder="Ex.: Reposição do fornecedor"></textarea></div>
            </div>
            <div class="module-form__footer">
                <div class="module-actions">
                    <button class="module-button module-button--secondary" id="entry-clear" type="button">Limpar campos</button>
                    <button class="module-button module-button--primary" type="submit">Confirmar entrada</button>
                </div>
            </div>
            <p class="module-feedback" id="entry-feedback" role="status" hidden></p>
        </form>
    </section>
    <aside class="module-panel module-preview" aria-labelledby="entry-preview-title">
        <div class="module-panel__header"><div><h2 id="entry-preview-title">Resumo da operação</h2><p>Confira os valores antes de integrar o salvamento.</p></div></div>
        <div class="preview-list">
            <div class="preview-row"><span>Produto</span><strong id="entry-preview-product">Arroz 5kg</strong></div>
            <div class="preview-row"><span>Estoque atual</span><strong id="entry-preview-current">120 unidades</strong></div>
            <div class="preview-row"><span>Entrada prevista</span><strong class="text-positive" id="entry-preview-change">+20 unidades</strong></div>
            <div class="preview-divider"></div>
            <div class="preview-row preview-row--total"><span>Novo estoque previsto</span><strong id="entry-preview-new">140 unidades</strong></div>
        </div>
    </aside>
</div>

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
<script src="JAVASCRIPT/entradas.js" defer></script>
</body>
</html>
