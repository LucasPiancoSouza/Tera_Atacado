<?php
require_once __DIR__ . '/../Back_end/config/database.php';

session_start();

require_once __DIR__ . '/../Back_end/config/database.php';

$sql = "SELECT
    p.nome_produto,
    p.codigo_produto,
    p.estoque_minimo, (
    SELECT COALESCE(SUM(lp2.quantidade_produto), 0)
    FROM localizacao_produto lp2
    WHERE lp2.id_produto = p.id_produto
) AS estoque_total,
    lp.quantidade_produto,
    l.setor,
    l.corredor,
    l.prateleira,
    l.andar
FROM produtos p
INNER JOIN localizacao_produto lp
    ON p.id_produto = lp.id_produto
INNER JOIN localizacao l
    ON lp.id_localizacao = l.id_localizacao";

$resultado = $con->query($sql);

$sqlResumo = "SELECT
    COUNT(DISTINCT lp.id_produto) AS produtos_localizados,
    COUNT(DISTINCT l.setor) AS total_setores
FROM localizacao_produto lp
INNER JOIN localizacao l
    ON lp.id_localizacao = l.id_localizacao";

$resultadoResumo = $con->query($sqlResumo);
$resumo = $resultadoResumo->fetch_assoc();

$sqlSemLocalizacao = "SELECT
    COUNT(*) AS produtos_sem_localizacao
FROM produtos p
WHERE NOT EXISTS (
    SELECT 1
    FROM localizacao_produto lp
    WHERE lp.id_produto = p.id_produto
)";

$resultadoSemLocalizacao = $con->query($sqlSemLocalizacao);
$semLocalizacao = $resultadoSemLocalizacao->fetch_assoc();

$sqlTotalUnidadesEstoque = "
    SELECT
        COALESCE(SUM(quantidade_produto), 0) AS total_estoque
    FROM localizacao_produto
";

$resultadoTotalUnidadesEstoque = $con->query($sqlTotalUnidadesEstoque);

$registroTotalUnidadesEstoque = $resultadoTotalUnidadesEstoque->fetch_assoc();

$quantidadeTotalEstoque = (int) $registroTotalUnidadesEstoque['total_estoque'];

?>

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
            </div>

            <section class="location-summary" aria-label="Resumo demonstrativo do estoque">
                <article class="location-summary__card">
                    <span class="location-summary__icon location-summary__icon--green" aria-hidden="true">▦</span>
                    <div>
                        <p>Produtos localizados</p>
                        <strong><?= (int) $resumo['produtos_localizados'] ?></strong>
                        <span>em <?= (int) $resumo['total_setores'] ?> setores</span>
                    </div>
                </article>
                <article class="location-summary__card">
                    <span class="location-summary__icon location-summary__icon--orange" aria-hidden="true">⌖</span>
                    <div>
                        <p>Produtos sem localização</p>
                        <strong><?= (int) $semLocalizacao['produtos_sem_localizacao'] ?></strong>
                        <span>Aguardando localização</span>
                    </div>
                </article>
                <article class="location-summary__card">
                    <span class="location-summary__icon location-summary__icon--blue" aria-hidden="true">□</span>
                    <div>
                        <p>Unidades armazenadas</p>
                        <strong>
                            <?= number_format($quantidadeTotalEstoque, 0, ',', '.') ?>
                        </strong>
                        <span>somando as posições</span>
                    </div>
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
                            <?php while ($produto = $resultado->fetch_assoc()): ?>
                                <?php
                                $estoqueTotal = $produto['estoque_total'];
                                $estoqueMinimo = $produto['estoque_minimo'];

                                if ($estoqueTotal == 0) {
                                    $status = "Sem estoque";
                                    $classeStatus = "location-status--empty";
                                } elseif ($estoqueTotal <= $estoqueMinimo) {
                                    $status = "Estoque baixo";
                                    $classeStatus = "location-status--low";
                                } else {
                                    $status = "Disponível";
                                    $classeStatus = "location-status--available";
                                }
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($produto['nome_produto']) ?></td>
                                    <td><?= htmlspecialchars($produto['codigo_produto']) ?></td>
                                    <td>
                                        <span class="location-sector">
                                            <?= htmlspecialchars($produto['setor']) ?>
                                        </span>
                                    </td>

                                    <td><?= htmlspecialchars($produto['corredor']) ?></td>
                                    <td><?= htmlspecialchars($produto['prateleira']) ?></td>
                                    <td><?= htmlspecialchars($produto['andar']) ?></td>
                                    <td><?= htmlspecialchars($produto['quantidade_produto']) ?> un.</td>
                                    <td>
                                        <span class="location-status <?= $classeStatus ?>">
                                            <?= $status ?>
                                        </span>
                                    </td>
                                </tr>

                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
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