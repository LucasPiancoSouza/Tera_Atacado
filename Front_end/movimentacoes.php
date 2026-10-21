<?php
session_start();
require_once __DIR__ . '/../Back_end/config/database.php';

?>
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
                    <h1>Movimentações</h1>
                    <p class="page-subtitle">Consulte o histórico de entradas e saídas do estoque.</p>
                </div>
            </div>
        

            <section class="metric-grid metric-grid--four" aria-label="Resumo das movimentações">
                <article class="metric-card">
                    <span class="metric-card__label">Movimentações listadas</span>
                    <strong class="metric-card__value" id="metric-mov-total">
                        <?php  
                            $sql ="Select * from movimentacoes";
                            $stmt = mysqli_prepare($con, $sql);
                            mysqli_stmt_execute($stmt);
                            $resultado_movimentacoes = mysqli_stmt_get_result($stmt);
                            $num_linhas = mysqli_num_rows($resultado_movimentacoes);
                            echo $num_linhas;
                        ?>
                    </strong>
                    <span class="metric-card__icon metric-card__icon--green" aria-hidden="true">↕</span>
                </article>
                <article class="metric-card">
                    <span class="metric-card__label">Entradas</span>
                    <strong class="metric-card__value" id="metric-mov-entries">
                        <?php  
                            $sql ="Select * from movimentacoes where tipo_modificacao = 1";
                            $stmt = mysqli_prepare($con, $sql);
                            mysqli_stmt_execute($stmt);
                            $resultado_movimentacoes = mysqli_stmt_get_result($stmt);
                            $num_linhas = mysqli_num_rows($resultado_movimentacoes);
                            echo $num_linhas;
                        ?>
                    </strong>
                    <span class="metric-card__foot">
                        <?php  
                            $sql ="Select SUM(quantidade) as Total from movimentacoes where tipo_modificacao = 1";
                            $stmt = mysqli_prepare($con, $sql);
                            mysqli_stmt_execute($stmt); // retorna um objeto de resultado
                            $resultado_movimentacoes = mysqli_stmt_get_result($stmt);
                            $linha = mysqli_fetch_assoc($resultado_movimentacoes);
                            $resultado = $linha["Total"];
                            echo "Unidades Entradas: ".$resultado;
                        ?>
                    </span>
                    <span class="metric-card__icon metric-card__icon--green" aria-hidden="true">＋</span>
                </article>
                <article class="metric-card">
                    <span class="metric-card__label">Saídas</span>
                    <strong class="metric-card__value" id="metric-mov-exits">
                        <?php  
                            $sql ="Select * from movimentacoes where tipo_modificacao = 0";
                            $stmt = mysqli_prepare($con, $sql);
                            mysqli_stmt_execute($stmt);
                            $resultado_movimentacoes = mysqli_stmt_get_result($stmt);
                            $num_linhas = mysqli_num_rows($resultado_movimentacoes);
                            echo $num_linhas;
                        ?>
                    </strong>
                    <span class="metric-card__foot">
                         <?php  
                            $sql ="Select SUM(quantidade) as Total from movimentacoes where tipo_modificacao = 0";
                            $stmt = mysqli_prepare($con, $sql);
                            mysqli_stmt_execute($stmt); // retorna um objeto de resultado
                            $resultado_movimentacoes = mysqli_stmt_get_result($stmt);
                            $linha = mysqli_fetch_assoc($resultado_movimentacoes);
                            $resultado = $linha["Total"];
                            echo "Unidades Retiradas: ".$resultado;
                        ?>
                    </span>
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
                            <?php
                            $sql = "SELECT
                                        m.data_hora_movimentacao,
                                        p.nome_produto,
                                        m.tipo_modificacao,
                                        m.quantidade,
                                        u.nome_usuario,
                                        m.observacao
                                    FROM movimentacoes m
                                    INNER JOIN localizacao_produto lp
                                        ON m.id_localizacao_produto = lp.id_localizacao_produto
                                    INNER JOIN produtos p
                                        ON lp.id_produto = p.id_produto
                                    INNER JOIN usuarios u
                                        ON m.id_usuario = u.id_usuario
                                    ORDER BY m.data_hora_movimentacao DESC";                             $stmt = mysqli_prepare($con, $sql);
                             mysqli_stmt_execute($stmt);
                             $resultado = mysqli_stmt_get_result($stmt);
                          ?>
                            <?php while($linha = mysqli_fetch_assoc($resultado)): ?>
                            <?php
                                $Verificando_Entrada = (int) $linha['tipo_modificacao'] === 1;

                                $dataBanco = new DateTimeImmutable(
                                $linha['data_hora_movimentacao'],
                                new DateTimeZone('UTC')
                            );

                            $dataLocal = $dataBanco->setTimezone(
                                new DateTimeZone('America/Sao_Paulo')
                            );

                            $dataFormatada = $dataLocal->format('d/m/Y')
                                . ' · '
                                . $dataLocal->format('H:i');

                            $dataIso = $dataLocal->format('Y-m-d\TH:i:sP');
                                ?>

                                <tr data-type="<?php echo $Verificando_Entrada ? 'entrada' : 'saida' ?>">

                                    <!-- Data e hora -->
                                    <td>
                                        <time datetime="<?php echo htmlspecialchars($dataIso, ENT_QUOTES, 'UTF-8') ?>">
                                            <?php echo htmlspecialchars($dataFormatada, ENT_QUOTES, 'UTF-8') ?>
                                        </time>
                                    </td>

                                    <!-- Produto -->
                                    <td>
                                        <strong>
                                            <?php echo htmlspecialchars($linha['nome_produto'], ENT_QUOTES, 'UTF-8') ?>
                                        </strong>
                                    </td>

                                    <!-- Tipo de movimentação -->
                                    <td>
                                        <span class="status-pill <?php echo $Verificando_Entrada ? 'status-pill--ok' : 'status-pill--low' ?>">
                                            <?php echo $Verificando_Entrada ? 'Entrada' : 'Saída' ?>
                                        </span>
                                    </td>

                                    <!-- Quantidade -->
                                    <td class="numeric-cell <?php echo $Verificando_Entrada ? 'quantity-entry' : 'quantity-exit' ?>">
                                        <?php echo $Verificando_Entrada ? '+' : '−' ?><?php echo (int) $linha['quantidade'] ?> un.
                                    </td>

                                    <!-- Usuário -->
                                    <td>
                                        <?php echo htmlspecialchars($linha['nome_usuario'], ENT_QUOTES, 'UTF-8') ?>
                                    </td>

                                    <!-- Observação -->
                                    <td>
                                        <?php echo htmlspecialchars($linha['observacao'] ?? '—', ENT_QUOTES, 'UTF-8') ?>
                                    </td>

                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <p class="empty-state" id="movements-empty" hidden>Nenhuma movimentação corresponde aos filtros selecionados.</p>
                </div>
                <div class="table-footer"><span id="movements-visible-count">Exibindo 8 movimentações</span></div>
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
