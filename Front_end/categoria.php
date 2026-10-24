<?php
require_once __DIR__ . '/../Back_end/config/database.php';

                    $sql = "Select * from categorias";                             
                    $stmt = mysqli_prepare($con, $sql);
                    mysqli_stmt_execute($stmt);
                    $resultado = mysqli_stmt_get_result($stmt);
                    
?>
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
</div>
<section class="module-panel module-create-panel" id="category-create" aria-labelledby="category-create-title">
    <div class="module-panel__header"><div><h2 id="category-create-title">Adicionar categoria</h2><p>Preencha o campo para cadastrar uma nova categoria no sistema.</p></div></div>
    <form id="category-form" class="module-form">
        <div class="module-form__grid">
            <div class="module-field" style="grid-column: 1 / -1;">
                <label for="category-name">Nome da categoria</label>
                <input id="category-name" name="nome_categoria" type="text" maxlength="100" placeholder="Ex.: Limpeza" required>
            </div>
        </div>
        <div class="module-form__footer">
            <div class="module-actions">
                <button class="module-button module-button--secondary" type="reset">Limpar</button>
                <button class="module-button module-button--primary" type="submit">Cadastrar categoria</button>
            </div>
        </div>
        <p class="module-feedback" id="category-feedback" role="status" hidden></p>
    </form>
</section>
<section class="module-panel" aria-labelledby="category-list-title">
    <div class="module-panel__header">
        <div><h2 id="category-list-title">Categorias cadastradas</h2></div>
        <span class="module-count" id="category-count"><?= mysqli_num_rows($resultado) ?> categorias</span>
    </div>
    <div class="module-toolbar">
        <label class="module-search" for="category-search"><span aria-hidden="true">⌕</span><input id="category-search" type="search" placeholder="Buscar categoria..." autocomplete="off"></label>
    </div>
    <div class="module-table-wrap">
        <table class="module-table" id="categories-table">
            <thead>
                <tr>
                    <th scope="col">Categoria</th>
                    <th scope="col">Data de criação</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while($linha = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td>
                        <strong><?= htmlspecialchars($linha["nome_categoria"]) ?></strong>
                    </td>
                    <td>
                        <?php
                            $data = DateTime::createFromFormat('Y-m-d', $linha["data_criacao"]);
                            $dataFormatada = $data ? $data->format('d/m/Y') : $linha["data_criacao"];
                        ?>
                        <?= htmlspecialchars($dataFormatada) ?>
                    </td>
                    <td><div class="module-actions"></div></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <p class="module-empty" id="category-empty" hidden>Nenhuma categoria corresponde à pesquisa.</p>
    </div>
</section>


</main>
</div>
<div class="category-modal category-modal--info" id="category-modal" hidden>
    <div class="category-modal__backdrop" data-modal-cancel aria-hidden="true"></div>
    <section class="category-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="category-modal-title" aria-describedby="category-modal-message">
        <button class="category-modal__close" type="button" data-modal-cancel aria-label="Fechar">&times;</button>
        <span class="category-modal__icon" id="category-modal-icon" aria-hidden="true">i</span>
        <p class="category-modal__eyebrow">TERA ATACADO</p>
        <h2 class="category-modal__title" id="category-modal-title"></h2>
        <p class="category-modal__message" id="category-modal-message"></p>
        <div class="category-modal__field" id="category-modal-field" hidden>
            <label for="category-modal-input">Nome da categoria</label>
            <input id="category-modal-input" type="text" maxlength="100" autocomplete="off" required>
        </div>
        <div class="category-modal__actions">
            <button class="module-button module-button--secondary" id="category-modal-cancel" type="button" hidden></button>
            <button class="module-button module-button--primary" id="category-modal-confirm" type="button">Fechar</button>
        </div>
    </section>
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
