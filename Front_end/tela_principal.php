<?php
session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: index.php');
    exit;
}
require_once __DIR__ . '/../Back_end/config/database.php';
$periodoSelecionado = $_GET['periodo'] ?? '30d';

if (!in_array($periodoSelecionado, ['7d', '30d', '6m'], true)) {
    $periodoSelecionado = '30d';
}

if ($periodoSelecionado === '6m') {
    // Últimos 6 meses, agrupados por mês
    $expressaoAgrupamentoData = "DATE_FORMAT(data_hora_movimentacao, '%Y-%m-01')";
    $dataLimiteMovimentacoes = date('Y-m-01', strtotime('-5 months'));
    $formatoRotuloGrafico = 'm/Y';
    $intervaloPeriodo = 'P1M';
} else {
    // Últimos 7 ou 30 dias
    $quantidadeDiasPeriodo = ($periodoSelecionado === '7d') ? 6 : 29;
    $expressaoAgrupamentoData = "DATE(data_hora_movimentacao)";
    $dataLimiteMovimentacoes = date('Y-m-d', strtotime("-{$quantidadeDiasPeriodo} days"));
    $formatoRotuloGrafico = 'd/m';
    $intervaloPeriodo = 'P1D';
}
// Inicializa os períodos com zero
$dataInicioPeriodo = new DateTimeImmutable($dataLimiteMovimentacoes);
$dataAtual = new DateTimeImmutable(date('Y-m-d'));
$movimentacoesPorPeriodo = [];
for (

    $dataIteracaoPeriodo = $dataInicioPeriodo;

    $dataIteracaoPeriodo <= $dataAtual;

    $dataIteracaoPeriodo = $dataIteracaoPeriodo->add(

        new DateInterval($intervaloPeriodo)

    )

) {

    $chavePeriodo = ($periodoSelecionado === '6m')

        ? $dataIteracaoPeriodo->format('Y-m-01')

        : $dataIteracaoPeriodo->format('Y-m-d');

    $movimentacoesPorPeriodo[$chavePeriodo] = [

        'entradas' => 0,

        'saidas' => 0

    ];
}

// Consulta as movimentações no banco

$sqlConsultaMovimentacoes = "

    SELECT
        $expressaoAgrupamentoData AS data_movimentacao,
        SUM(
            CASE
                WHEN tipo_modificacao = 1 THEN quantidade
                ELSE 0
            END
        ) AS entradas,
        SUM(
            CASE
                WHEN tipo_modificacao = 0 THEN quantidade
                ELSE 0
            END
        ) AS saidas
    FROM movimentacoes
    WHERE data_hora_movimentacao >= ?
    GROUP BY data_movimentacao
    ORDER BY data_movimentacao
";
$stmtConsultaMovimentacoes = mysqli_prepare($con, $sqlConsultaMovimentacoes);
mysqli_stmt_bind_param(
    $stmtConsultaMovimentacoes,
    's',
    $dataLimiteMovimentacoes
);
mysqli_stmt_execute($stmtConsultaMovimentacoes);
$resultadoConsultaMovimentacoes = mysqli_stmt_get_result(
    $stmtConsultaMovimentacoes
);

// Substitui os zeros pelos valores encontrados no banco

while ($registroMovimentacao = mysqli_fetch_assoc($resultadoConsultaMovimentacoes)) {

    $movimentacoesPorPeriodo[$registroMovimentacao['data_movimentacao']] = [
        'entradas' => (int) $registroMovimentacao['entradas'],
        'saidas' => (int) $registroMovimentacao['saidas']

    ];
}

mysqli_stmt_close($stmtConsultaMovimentacoes);

// Prepara os dados para o JavaScript

$dadosGraficoMovimentacoes = [

    'labels' => [],
    'entradas' => [],
    'saidas' => []

];



foreach (

    $movimentacoesPorPeriodo as $dataReferencia => $valoresMovimentacaoPeriodo

) {

    $dadosGraficoMovimentacoes['labels'][] = date(

        $formatoRotuloGrafico,

        strtotime($dataReferencia)

    );

    $dadosGraficoMovimentacoes['entradas'][] =

        $valoresMovimentacaoPeriodo['entradas'];

    $dadosGraficoMovimentacoes['saidas'][] =

        $valoresMovimentacaoPeriodo['saidas'];
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/tela_principal.css">
    <title>Dashboard</title>
    <link rel="icon" href="Img/tera_logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@500;600&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
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
                        <a href="movimentacoes.php" class="menu-item">
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

        <main>
            <div class="summary-cards">

                <div class="container">

                    <span class="card-title">Total de produtos</span>

                    <span class="card-value">

                        <?php

                        $sqlContagemProdutos = "
                            SELECT COUNT(*) AS total_produtos
                            FROM produtos

                        ";
                        $stmtContagemProdutos = mysqli_prepare(
                            $con,
                            $sqlContagemProdutos

                        );
                        mysqli_stmt_execute($stmtContagemProdutos);
                        $resultadoContagemProdutos = mysqli_stmt_get_result(

                            $stmtContagemProdutos

                        );
                        $registroContagemProdutos = mysqli_fetch_assoc(

                            $resultadoContagemProdutos

                        );
                        $totalProdutosCadastrados = (int) (

                            $registroContagemProdutos['total_produtos'] ?? 0

                        );
                        mysqli_stmt_close($stmtContagemProdutos);
                        echo $totalProdutosCadastrados;
                        ?>
                    </span>
                    <span class="card-message">

                        <?php

                        if ($totalProdutosCadastrados === 0) {

                            echo 'Nenhum reabastecimento esse mês';
                        }

                        ?>

                    </span>

                </div>
                <div class="container">
                    <span class="card-title">Quantidade total de produtos em estoque</span>
                    <span class="card-value">
                        <?php

                        $sqlTotalUnidadesEstoque = "

                            SELECT COALESCE(
                                SUM(quantidade_produto),
                                0
                            ) AS total_estoque
                            FROM localizacao_produto
                        ";
                        $stmtTotalUnidadesEstoque = mysqli_prepare(
                            $con,
                            $sqlTotalUnidadesEstoque

                        );
                        mysqli_stmt_execute($stmtTotalUnidadesEstoque);
                        $resultadoTotalUnidadesEstoque = mysqli_stmt_get_result(
                            $stmtTotalUnidadesEstoque
                        );
                        $registroTotalUnidadesEstoque = mysqli_fetch_assoc(
                            $resultadoTotalUnidadesEstoque
                        );
                        $quantidadeTotalEstoque = (int) (
                            $registroTotalUnidadesEstoque['total_estoque'] ?? 0
                        );
                        mysqli_stmt_close($stmtTotalUnidadesEstoque);
                        echo $quantidadeTotalEstoque;
                        ?>
                    </span>
                    <?php if ($quantidadeTotalEstoque <= 50): ?>
                        <span class="card-message vermelho">Estoque baixo</span>

                    <?php elseif ($quantidadeTotalEstoque < 100): ?>

                        <span class="card-message laranja">Estoque em Boa quantidade</span>

                    <?php else: ?>

                        <span class="card-message verde">Estoque em ótima quantidade</span>

                    <?php endif; ?>

                </div>
                <div class="container">

                    <span class="card-title">Produtos com estoque baixo</span>

                    <span class="card-value">

                        <?php

                        $sqlProdutosEstoqueBaixo = "
                            SELECT
                                p.id_produto,
                                p.nome_produto,
                                SUM(lp.quantidade_produto) AS total_estoque
                            FROM produtos p
                            INNER JOIN localizacao_produto lp
                                ON p.id_produto = lp.id_produto
                            GROUP BY p.id_produto, p.nome_produto
                            HAVING SUM(lp.quantidade_produto) < 40 and SUM(lp.quantidade_produto) > 0
                        ";
                        $stmtProdutosEstoqueBaixo = mysqli_prepare(
                            $con,
                            $sqlProdutosEstoqueBaixo
                        );
                        mysqli_stmt_execute($stmtProdutosEstoqueBaixo);
                        $resultadoProdutosEstoqueBaixo = mysqli_stmt_get_result(
                            $stmtProdutosEstoqueBaixo
                        );
                        $quantidadeProdutosEstoqueBaixo = mysqli_num_rows(
                            $resultadoProdutosEstoqueBaixo
                        );
                        mysqli_stmt_close($stmtProdutosEstoqueBaixo);
                        echo $quantidadeProdutosEstoqueBaixo;

                        ?>

                    </span>
                    <?php

                    if (
                        $totalProdutosCadastrados > 0
                        && ($quantidadeProdutosEstoqueBaixo / $totalProdutosCadastrados) * 100 >= 50
                    ):

                    ?>
                        <span class="card-message vermelho">Requer atenção!</span>
                    <?php else: ?>

                        <span class="card-message verde">Estoque em boa quantidade</span>

                    <?php endif; ?>

                </div>
                <div class="container">
                    <span class="card-title">Sem estoque</span>
                    <span class="card-value">
                        <?php
                        $sqlProdutosSemEstoque = "
                            SELECT
                                p.id_produto,
                                p.nome_produto,
                                SUM(lp.quantidade_produto) AS total_estoque
                            FROM produtos p
                            INNER JOIN localizacao_produto lp
                                ON p.id_produto = lp.id_produto
                            GROUP BY p.id_produto, p.nome_produto
                            HAVING SUM(lp.quantidade_produto) = 0

                        ";

                        $stmtProdutosSemEstoque = mysqli_prepare(
                            $con,
                            $sqlProdutosSemEstoque
                        );
                        mysqli_stmt_execute($stmtProdutosSemEstoque);
                        $resultadoProdutosSemEstoque = mysqli_stmt_get_result(
                            $stmtProdutosSemEstoque
                        );

                        $quantidadeProdutosSemEstoque = mysqli_num_rows(
                            $resultadoProdutosSemEstoque
                        );
                        mysqli_stmt_close($stmtProdutosSemEstoque);
                        echo $quantidadeProdutosSemEstoque;

                        ?>

                    </span>

                    <?php

                    if (

                        $totalProdutosCadastrados > 0

                        && ($quantidadeProdutosSemEstoque / $totalProdutosCadastrados) * 100 >= 40

                    ):

                    ?>

                        <span class="card-message vermelho">Requer atenção!</span>

                    <?php endif; ?>

                </div>
            </div>

            <div class="chart-row">

                <div class="card-graphic">
                    <span class="card-title2">Entradas e saídas</span>
                    <span class="card-description">Movimentações ao longo do período</span>

                    <select class="select" id="periodoMovimentacoes">
                        <option value="30d" >Últimos 30 dias</option>
                        <option value="7d" >Últimos 7 dias</option>
                        <option value="6m" >Últimos 6 meses</option>
                    </select>

                    <div class="chart-container">
                        <canvas id="movimentacoesChart" width="700" height="260"></canvas>
                    </div>

                </div>

                <div class="card-stock">

                    <span class="card-title3">Produtos de estoque baixo</span>

                    <span class="card-description2">Itens que precisam de reposição</span>

                    <button class="button">Ver todos</button>



        <div class="stock-list">

            <?php

            $sqlProdutosEstoqueBaixo = "
                SELECT
                    p.id_produto,
                    p.nome_produto,
                    c.nome_categoria,
                    SUM(lp.quantidade_produto) AS total_estoque
                FROM produtos p
                INNER JOIN categorias c
                    ON p.id_categoria = c.id_categoria
                INNER JOIN localizacao_produto lp
                    ON p.id_produto = lp.id_produto
                GROUP BY
                    p.id_produto,
                    p.nome_produto,
                    c.nome_categoria
                HAVING SUM(lp.quantidade_produto) < 40
                ORDER BY total_estoque ASC
                LIMIT 5
            ";

            $stmtProdutosEstoqueBaixo = mysqli_prepare(
                $con,
                $sqlProdutosEstoqueBaixo
            );

            mysqli_stmt_execute($stmtProdutosEstoqueBaixo);

            $resultadoProdutosEstoqueBaixo = mysqli_stmt_get_result(
                $stmtProdutosEstoqueBaixo
            );

            if (mysqli_num_rows($resultadoProdutosEstoqueBaixo) > 0):

                while (
                    $produto = mysqli_fetch_assoc(
                        $resultadoProdutosEstoqueBaixo
                    )
                ):

            ?>

        <div class="stock-item">

            <div>
                <span class="product-name">
                    <?= htmlspecialchars( // htmlspecialchars serve para o retorno do banco não ser interpretado como html pelo navegador 
                        $produto['nome_produto'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </span>

                <span class="product-category">
                    <?= htmlspecialchars(
                        $produto['nome_categoria'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </span>
            </div>

            <div class="stock-info">

                <span class="stock-quantity">
                    <?= (int) $produto['total_estoque'] ?>
                </span>

            </div>

        </div>

    <?php

        endwhile;

    else:

    ?>

        <p>Nenhum produto com estoque baixo.</p>

    <?php

    endif;

    mysqli_stmt_close($stmtProdutosEstoqueBaixo);

    ?>

</div>
                </div>

            </div>

            <div class="summary-of-stock">

                <div class="card-product">

                    <span class="card-product-title">

                        Produtos com maior quantidade

                    </span>

                    <span class="card-product-description">

                        Itens com maior disponibilidade

                    </span>

                    <div class="product-list">

    <?php

    $sqlProdutosMaiorEstoque = "
        SELECT
            p.nome_produto,
            c.nome_categoria,
            SUM(lp.quantidade_produto) AS total_estoque
        FROM produtos p
        INNER JOIN categorias c
            ON p.id_categoria = c.id_categoria
        INNER JOIN localizacao_produto lp
            ON p.id_produto = lp.id_produto
        GROUP BY
            p.id_produto,
            p.nome_produto,
            c.nome_categoria
        ORDER BY total_estoque DESC
        LIMIT 5
    ";

    $stmtProdutosMaiorEstoque = mysqli_prepare(
        $con,
        $sqlProdutosMaiorEstoque
    );

    mysqli_stmt_execute($stmtProdutosMaiorEstoque);

    $resultadoProdutosMaiorEstoque = mysqli_stmt_get_result(
        $stmtProdutosMaiorEstoque
    );

    while ($produto = mysqli_fetch_assoc($resultadoProdutosMaiorEstoque)) {

    ?>

        <div class="product-item">

            <div class="product-details">

                <span class="product-name">
                    <?php
                    echo htmlspecialchars($produto['nome_produto']);
                    ?>
                </span>

                <span class="product-category">
                    <?php
                    echo htmlspecialchars($produto['nome_categoria']);
                    ?>
                </span>

            </div>

            <span class="product-stock">
                <?php echo (int) $produto['total_estoque']; ?> un.
            </span>

        </div>

    <?php
    }

    mysqli_stmt_close($stmtProdutosMaiorEstoque);
    ?>

</div>

                    </div>

                <div class="card-moviment">

                    <div class="moviment-header">

                        <div>

                            <span class="moviment-title">

                                Últimas movimentações

                            </span>
                            <span class="moviment-description">
                                Atividades recentes
                            </span>
                        </div>
                        <button class="moviment-button">

                            Ver histórico
                        </button>
                    </div>
                    <div class="moviment-list">
    <?php

    $sqlUltimasMovimentacoes = "
        SELECT
            p.nome_produto,
            m.tipo_modificacao,
            m.quantidade,
            m.data_hora_movimentacao
        FROM movimentacoes m
        INNER JOIN produtos p
            ON m.id_produto = p.id_produto
        ORDER BY m.data_hora_movimentacao DESC
        LIMIT 5
    ";

    $stmtUltimasMovimentacoes = mysqli_prepare(
        $con,
        $sqlUltimasMovimentacoes
    );

    mysqli_stmt_execute($stmtUltimasMovimentacoes);

    $resultadoUltimasMovimentacoes = mysqli_stmt_get_result(
        $stmtUltimasMovimentacoes
    );

    while (
        $movimentacao = mysqli_fetch_assoc(
            $resultadoUltimasMovimentacoes
        )
    ) {

        if ($movimentacao['tipo_modificacao'] == 1) {
            $classeMovimentacao = 'entrada';
            $sinalMovimentacao = '+';
        } else {
            $classeMovimentacao = 'saida';
            $sinalMovimentacao = '−';
        }

    ?>

        <div class="moviment-item">

            <div class="moviment-details">

                <span class="moviment-product">
                    <?php
                    echo htmlspecialchars($movimentacao['nome_produto']);
                    ?>
                </span>

                <span class="moviment-date">
                    <?php
                    echo date(
                        'd/m/Y \• H:i',
                        strtotime($movimentacao['data_hora_movimentacao'])
                    );
                    ?>
                </span>

            </div>

            <span class="moviment-quantity <?php echo $classeMovimentacao; ?>">
                <?php
                echo $sinalMovimentacao . (int) $movimentacao['quantidade'];
                ?> un.
            </span>

        </div>

    <?php
    }

    mysqli_stmt_close($stmtUltimasMovimentacoes);
    ?>

</div>
                </div>

            </div>
        </main>
    </div>

    <!-- Confirmação de saída: abre/fecha com âncora e CSS, sem JavaScript. -->
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.dadosMovimentacoes = <?php
                                    echo json_encode(
                                        $dadosGraficoMovimentacoes,
                                        JSON_HEX_TAG |
                                            JSON_HEX_AMP |
                                            JSON_HEX_APOS |
                                            JSON_HEX_QUOT
                                    );
                                    ?>;
        console.log(
            'Dados recebidos do PHP:',
            window.dadosMovimentacoes
        );
    </script>
    <script src="JAVASCRIPT/tela_principal.js"></script>

</body>

</html>