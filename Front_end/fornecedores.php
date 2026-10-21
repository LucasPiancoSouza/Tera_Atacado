<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Consulte e organize os fornecedores do Tera Atacado.">
    <title>Fornecedores | Tera Atacado</title>
    <link rel="icon" href="Img/tera_logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/tela_principal.css">
    <link rel="stylesheet" href="CSS/fornecedores.css">
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
    <div><p class="module-eyebrow">TERA ATACADO / CADASTROS</p><h1>Fornecedores</h1><p class="module-subtitle">Consulte os contatos e as empresas fornecedoras cadastradas.</p></div>
    <a href="#supplier-create" class="module-button module-button--primary"><span aria-hidden="true">＋</span> Novo fornecedor</a>
</div>
<section class="module-panel" aria-labelledby="supplier-list-title">
    <div class="module-panel__header"><div><h2 id="supplier-list-title">Lista de fornecedores</h2></div><span class="module-count" id="supplier-count">4 fornecedores</span></div>
    <div class="module-toolbar"><label class="module-search" for="supplier-search"><span aria-hidden="true">⌕</span><input type="search" id="supplier-search" placeholder="Buscar por nome, empresa, CNPJ ou e-mail..." autocomplete="off"></label></div>
    <div class="module-table-wrap">
        <table class="module-table module-table--wide" id="suppliers-table">
            <thead><tr><th scope="col">Fornecedor</th><th scope="col">Empresa</th><th scope="col">CNPJ</th><th scope="col">Telefone</th><th scope="col">E-mail</th><th scope="col">Produtos</th><th scope="col">Ações</th></tr></thead>
            <tbody>
                <tr><td><strong>Distribuidora Boa Compra</strong></td><td>Boa Compra Ltda.</td><td>12.345.678/0001-90</td><td>(11) 3333-1000</td><td>contato@boacompra.com</td><td>3</td><td><div class="module-actions"><button class="icon-button" type="button" data-action="view-supplier" aria-label="Ver Distribuidora Boa Compra" title="Visualizar">ⓘ</button><button class="icon-button" type="button" data-action="edit-supplier" aria-label="Editar Distribuidora Boa Compra" title="Editar">✎</button><button class="icon-button icon-button--danger" type="button" data-action="delete-supplier" aria-label="Excluir Distribuidora Boa Compra" title="Excluir">×</button></div></td></tr>
                <tr><td><strong>Mercantil Paulista</strong></td><td>Mercantil Paulista S.A.</td><td>23.456.789/0001-10</td><td>(11) 3333-2000</td><td>vendas@mercantil.com</td><td>3</td><td><div class="module-actions"><button class="icon-button" type="button" data-action="view-supplier" aria-label="Ver Mercantil Paulista" title="Visualizar">ⓘ</button><button class="icon-button" type="button" data-action="edit-supplier" aria-label="Editar Mercantil Paulista" title="Editar">✎</button><button class="icon-button icon-button--danger" type="button" data-action="delete-supplier" aria-label="Excluir Mercantil Paulista" title="Excluir">×</button></div></td></tr>
                <tr><td><strong>Café Serra Azul</strong></td><td>Serra Azul Alimentos</td><td>34.567.890/0001-20</td><td>(11) 3333-3000</td><td>comercial@serraazul.com</td><td>1</td><td><div class="module-actions"><button class="icon-button" type="button" data-action="view-supplier" aria-label="Ver Café Serra Azul" title="Visualizar">ⓘ</button><button class="icon-button" type="button" data-action="edit-supplier" aria-label="Editar Café Serra Azul" title="Editar">✎</button><button class="icon-button icon-button--danger" type="button" data-action="delete-supplier" aria-label="Excluir Café Serra Azul" title="Excluir">×</button></div></td></tr>
                <tr><td><strong>Laticínios Vale</strong></td><td>Laticínios Vale Ltda.</td><td>45.678.901/0001-30</td><td>(11) 3333-4000</td><td>contato@laticiniosvale.com</td><td>1</td><td><div class="module-actions"><button class="icon-button" type="button" data-action="view-supplier" aria-label="Ver Laticínios Vale" title="Visualizar">ⓘ</button><button class="icon-button" type="button" data-action="edit-supplier" aria-label="Editar Laticínios Vale" title="Editar">✎</button><button class="icon-button icon-button--danger" type="button" data-action="delete-supplier" aria-label="Excluir Laticínios Vale" title="Excluir">×</button></div></td></tr>
            </tbody>
        </table>
        <p class="module-empty" id="supplier-empty" hidden>Nenhum fornecedor corresponde à pesquisa.</p>
    </div>
</section>
<section class="module-panel module-create-panel" id="supplier-create" aria-labelledby="supplier-create-title">
    <div class="module-panel__header"><div><h2 id="supplier-create-title">Novo fornecedor</h2></div></div>
    <form id="supplier-form" class="module-form">
        <div class="module-form__grid module-form__grid--two">
            <div class="module-field"><label for="supplier-name">Nome do fornecedor</label><input id="supplier-name" name="nome_fornecedor" type="text" maxlength="100" required placeholder="Nome de contato ou fornecedor"></div>
            <div class="module-field"><label for="supplier-company">Empresa</label><input id="supplier-company" name="empresa" type="text" maxlength="100" required placeholder="Razão social"></div>
            <div class="module-field"><label for="supplier-cnpj">CNPJ</label><input id="supplier-cnpj" name="cnpj" type="text" maxlength="18" required placeholder="00.000.000/0000-00"></div>
            <div class="module-field"><label for="supplier-phone">Telefone</label><input id="supplier-phone" name="telefone" type="tel" maxlength="15" required placeholder="(11) 3333-0000"></div>
            <div class="module-field module-field--full"><label for="supplier-email">E-mail</label><input id="supplier-email" name="email" type="email" maxlength="100" required placeholder="contato@empresa.com"></div>
        </div>
        <div class="module-form__footer"><div class="module-actions"><button class="module-button module-button--secondary" type="reset">Limpar</button><button class="module-button module-button--primary" type="submit">Adicionar à prévia</button></div></div>
        <p class="module-feedback" id="supplier-feedback" role="status" hidden></p>
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
<script src="JAVASCRIPT/fornecedores.js" defer></script>
</body>
</html>
