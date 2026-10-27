<?php

require_once __DIR__ . '/../Back_end/config/database.php';

$sql = "SELECT id_produto, nome_produto
        FROM produtos
        ORDER BY nome_produto";

$resultadoProdutos = $con->query($sql);

$sqlLocalizacoes = "SELECT
                        id_localizacao,
                        setor,
                        corredor,
                        prateleira,
                        andar
                    FROM localizacao
                    ORDER BY setor, corredor, prateleira, andar";

$resultadoLocalizacoes = $con->query($sqlLocalizacoes);
?>
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
    <link rel="stylesheet" href="CSS/entrada.css">
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
                        <a href="localizacoes.php" class="menu-item">
                            <span>⌖</span>
                            <span>Localizações</span>
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
                    <p class="module-eyebrow">TERA ATACADO / ESTOQUE</p>
                    <h1>Entrada de estoque</h1>
                    <p class="module-subtitle">Informe o produto recebido e confira a prévia da quantidade atualizada.</p>
                </div>
            </div>
            <div class="module-two-column">
                <section class="module-panel" aria-labelledby="entry-form-title">
                    <div class="module-panel__header">
                        <div>
                            <h2 id="entry-form-title">Dados da entrada</h2>
                        </div>
                    </div>
                   <form id="entry-form"
      class="module-form"
      method="POST"
      action="../Back_end/processamentos/registrar_entrada.php">

    <div class="module-form__grid">

        <!-- Produto -->
        <div class="module-field module-field--full">
            <label for="entry-product">Produto</label>

            <select id="entry-product"
                    name="id_produto"
                    required>

                <option value="" selected disabled>
                    Selecione um produto
                </option>

                <?php while ($produto = $resultadoProdutos->fetch_assoc()): ?>
                    <option value="<?php echo (int) $produto['id_produto'] ?>">
                        <?php echo htmlspecialchars(
                            $produto['nome_produto'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </option>
                <?php endwhile; ?>

            </select>
        </div>

        <!-- Localização -->
        <div class="module-field module-field--full">
            <label for="entry-location">Local de armazenamento</label>

            <select id="entry-location"
                    name="id_localizacao"
                    required>

                <option value="" selected disabled>
                    Selecione onde o produto será armazenado
                </option>

                <?php while ($local = $resultadoLocalizacoes->fetch_assoc()): ?>
                    <option value="<?php echo (int) $local['id_localizacao'] ?>">
                        <?php echo htmlspecialchars(
                            $local['setor'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                        — Corredor <?php echo (int) $local['corredor'] ?>
                        — Prateleira <?php echo (int) $local['prateleira'] ?>
                        — Andar <?php echo (int) $local['andar'] ?>
                    </option>
                <?php endwhile; ?>

            </select>
        </div>

        <!-- Quantidade -->
        <div class="module-field">
            <label for="entry-quantity">Quantidade recebida</label>

            <input type="number"
                   id="entry-quantity"
                   name="quantidade"
                   min="1"
                   step="1"
                   required>
        </div>
        <!-- Observação -->
        <div class="module-field module-field--full">
            <label for="entry-observation">
                Observação
                <span class="field-optional">(opcional)</span>
            </label>

            <textarea id="entry-observation"
                      name="observacao"
                      rows="3"
                      maxlength="500"
                      placeholder="Ex.: Reposição do fornecedor"></textarea>
        </div>

    </div>

    <div class="module-form__footer">
        <div class="module-actions">

            <button class="module-button module-button--secondary"
                    id="entry-clear"
                    type="button">
                Limpar campos
            </button>

            <button class="module-button module-button--primary"
                    type="submit">
                Confirmar entrada
            </button>

        </div>
    </div>

    <p class="module-feedback"
       id="entry-feedback"
       role="status"
       hidden></p>

</form>
                </section>
                <aside class="module-panel module-preview" aria-labelledby="entry-preview-title">
                    <div class="module-panel__header">
                        <div>
                            <h2 id="entry-preview-title">Resumo da operação</h2>
                            <p>Confira os valores antes de integrar o salvamento.</p>
                        </div>
                    </div>
                    <div class="preview-list">
    <div class="preview-row">
        <span>Produto</span>
        <strong id="entry-preview-product">Selecione um produto</strong>
    </div>

    <div class="preview-row">
        <span>Estoque atual</span>
        <strong id="entry-preview-current">—</strong>
    </div>

    <div class="preview-row">
        <span>Entrada prevista</span>
        <strong class="text-positive" id="entry-preview-change">+0 unidades</strong>
    </div>

    <div class="preview-divider"></div>

    <div class="preview-row preview-row--total">
        <span>Novo estoque previsto</span>
        <strong id="entry-preview-new">—</strong>
    </div>
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
    <div id="popup-sucesso" class="popup-sucesso" role="status" aria-live="polite">
    <div class="popup-icone">✓</div>

    <div>
        <strong>Entrada realizada!</strong>
        <p>O estoque foi atualizado com sucesso.</p>
    </div>
</div>
    <script src="JAVASCRIPT/menu-ativo.js"></script>
    <script src="/Front_end/JAVASCRIPT/entradas.js" defer></script>
</body>

</html>