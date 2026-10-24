<?php
require_once __DIR__ . '/../Back_end/config/database.php';

                    $sql = "Select * from fornecedores";                             
                    $stmt = mysqli_prepare($con, $sql);
                    mysqli_stmt_execute($stmt);
                    $resultado = mysqli_stmt_get_result($stmt);
?>
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
            <thead><tr>
                <th scope="col">Fornecedor</th>
                <th scope="col">Empresa</th>
                <th scope="col">CNPJ</th>
                <th scope="col">Telefone</th>
                <th scope="col">E-mail</th>
                <th scope="col">CEP</th>
                <th scope="col">Estado</th>
                <th scope="col">Cidade</th>
                <th scope="col">Logradouro</th>
            </tr>
        </thead>
            <tbody>
                
                <?php while($linha = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($linha["nome_fornecedor"]) ?></strong></td>
                        <td><?= htmlspecialchars($linha["empresa"]) ?></td>
                        <td><?= htmlspecialchars($linha["cnpj"]) ?></td>
                        <td><?= htmlspecialchars($linha["telefone"]) ?></td>
                        <td><?= htmlspecialchars($linha["email"]) ?></td>
                        <td><?= htmlspecialchars($linha["cep"]) ?></td>
                        <td><?= htmlspecialchars($linha["estado"]) ?></td>
                        <td><?= htmlspecialchars($linha["cidade"]) ?></td>
                        <td><?= htmlspecialchars($linha["rua_numero"]) ?></td>
                    </tr>
                   <?php endwhile; ?>     
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
            <div class="module-field"><label for="supplier-cep">CEP</label><input id="supplier-cep" name="cep" type="text" inputmode="numeric" maxlength="9" required placeholder="00000-000"></div>
            <div class="module-field"><label for="supplier-state">Estado</label><input id="supplier-state" name="estado" type="text" maxlength="100" required placeholder="Ex.: São Paulo"></div>
            <div class="module-field"><label for="supplier-city">Cidade</label><input id="supplier-city" name="cidade" type="text" maxlength="100" required placeholder="Ex.: São Paulo"></div>
            <div class="module-field"><label for="supplier-address">Rua e número</label><input id="supplier-address" name="rua_numero" type="text" maxlength="255" required placeholder="Ex.: Rua das Flores, 123"></div>
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
