<?php
declare(strict_types=1);

require_once __DIR__ . '/../Back_end/config/database.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($con) || !($con instanceof mysqli)) {
    http_response_code(500);
    exit('A conexão MySQLi ($con) não foi encontrada em database.php.');
}
$con->set_charset('utf8mb4');

$limiteEstoqueBaixo = 10; // Ajuste conforme a regra de estoque da empresa.

$tiposPermitidos = [
    'produtos',
    'entradas',
    'saidas',
    'estoque-baixo',
    'movimentacoes'
];

$titulosRelatorios = [
    'produtos' => 'Relatório de produtos',
    'entradas' => 'Relatório de entradas',
    'saidas' => 'Relatório de saídas',
    'estoque-baixo' => 'Relatório de estoque baixo',
    'movimentacoes' => 'Relatório de movimentações'
];

$relatorio = $_GET['relatorio'] ?? 'produtos';
if (!in_array($relatorio, $tiposPermitidos, true)) {
    $relatorio = 'produtos';
}

$dataInicial = trim((string)($_GET['dataInicial'] ?? ''));
$dataFinal = trim((string)($_GET['dataFinal'] ?? ''));
$categoria = trim((string)($_GET['categoria'] ?? ''));
$produto = trim((string)($_GET['produto'] ?? ''));
$tipo = trim((string)($_GET['tipo'] ?? ''));

$consultaRealizada = isset($_GET['relatorio']);
$colunas = [];
$registros = [];
$categorias = [];
$produtosFiltro = [];
$erro = '';

function h($valor): string
{
    return htmlspecialchars((string)($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

function dataValida(string $data): bool
{
    if ($data === '') {
        return true;
    }

    $objeto = DateTimeImmutable::createFromFormat('!Y-m-d', $data);
    return $objeto !== false && $objeto->format('Y-m-d') === $data;
}

/**
 * Executa uma consulta preparada MySQLi e devolve todas as linhas.
 * Os parâmetros são passados por referência conforme exige bind_param.
 */
function consultar(mysqli $con, string $sql, string $tipos = '', array $valores = []): array
{
    $stmt = $con->prepare($sql);

    if ($valores !== []) {
        $args = [$tipos];
        foreach ($valores as $i => &$valor) {
            $args[] = &$valor;
        }
        unset($valor);
        call_user_func_array([$stmt, 'bind_param'], $args);
    }

    $stmt->execute();
    $resultado = $stmt->get_result();
    $linhas = $resultado->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $linhas;
}

try {
    $categorias = consultar(
        $con,
        'SELECT nome_categoria FROM categorias ORDER BY nome_categoria'
    );

    $produtosFiltro = consultar(
        $con,
        'SELECT nome_produto FROM produtos ORDER BY nome_produto'
    );

    if (!dataValida($dataInicial) || !dataValida($dataFinal)) {
        throw new InvalidArgumentException('Informe datas válidas.');
    }

    if ($dataInicial !== '' && $dataFinal !== '' && $dataInicial > $dataFinal) {
        throw new InvalidArgumentException(
            'A data inicial não pode ser posterior à data final.'
        );
    }

    if (!in_array($tipo, ['', 'entrada', 'saida'], true)) {
        throw new InvalidArgumentException('Tipo de movimentação inválido.');
    }

    if ($consultaRealizada) {
        $filtrosProduto = '';
        $valoresProduto = [];
        $tiposProduto = '';

        if ($categoria !== '') {
            $filtrosProduto .= ' AND c.nome_categoria = ?';
            $valoresProduto[] = $categoria;
            $tiposProduto .= 's';
        }

        if ($produto !== '') {
            $filtrosProduto .= ' AND p.nome_produto = ?';
            $valoresProduto[] = $produto;
            $tiposProduto .= 's';
        }

        switch ($relatorio) {
            case 'produtos':
                $colunas = [
                    'codigo' => 'Código',
                    'produto' => 'Produto',
                    'categoria' => 'Categoria',
                    'codigo_produto' => 'Código do produto',
                    'preco' => 'Preço',
                    'quantidade' => 'Quantidade em estoque'
                ];

                $sql = "
                    SELECT
                        p.id_produto AS codigo,
                        p.nome_produto AS produto,
                        c.nome_categoria AS categoria,
                        p.codigo_produto AS codigo_produto,
                        p.preco AS preco,
                        COALESCE(SUM(lp.quantidade_produto), 0) AS quantidade
                    FROM produtos p
                    INNER JOIN categorias c ON c.id_categoria = p.id_categoria
                    LEFT JOIN localizacao_produto lp ON lp.id_produto = p.id_produto
                    WHERE 1 = 1
                    $filtrosProduto
                    GROUP BY
                        p.id_produto, p.nome_produto, c.nome_categoria,
                        p.codigo_produto, p.preco
                    ORDER BY p.nome_produto
                ";

                $registros = consultar($con, $sql, $tiposProduto, $valoresProduto);
                break;

            case 'estoque-baixo':
                $colunas = [
                    'codigo' => 'Código',
                    'produto' => 'Produto',
                    'categoria' => 'Categoria',
                    'quantidade' => 'Quantidade em estoque',
                    'limite' => 'Limite de estoque'
                ];

                // O limite é configurado no início deste arquivo porque
                // o banco atual não possui coluna de estoque mínimo.
                $sql = "
                    SELECT
                        p.id_produto AS codigo,
                        p.nome_produto AS produto,
                        c.nome_categoria AS categoria,
                        COALESCE(SUM(lp.quantidade_produto), 0) AS quantidade,
                        ? AS limite
                    FROM produtos p
                    INNER JOIN categorias c ON c.id_categoria = p.id_categoria
                    LEFT JOIN localizacao_produto lp ON lp.id_produto = p.id_produto
                    WHERE 1 = 1
                    $filtrosProduto
                    GROUP BY p.id_produto, p.nome_produto, c.nome_categoria
                    HAVING quantidade <= ?
                    ORDER BY quantidade ASC, p.nome_produto
                ";

                $valores = array_merge([$limiteEstoqueBaixo], $valoresProduto, [$limiteEstoqueBaixo]);
                $tipos = 'i' . $tiposProduto . 'i';
                $registros = consultar($con, $sql, $tipos, $valores);
                break;

            case 'entradas':
            case 'saidas':
            case 'movimentacoes':
                $colunas = [
                    'codigo' => 'Código da movimentação',
                    'tipo' => 'Tipo',
                    'produto' => 'Produto',
                    'categoria' => 'Categoria',
                    'quantidade' => 'Quantidade movimentada',
                    'usuario' => 'Usuário',
                    'localizacao' => 'Localização',
                    'data_hora' => 'Data e hora',
                    'observacao' => 'Observação'
                ];

                $sql = "
                    SELECT
                        m.id_movimentacao AS codigo,
                        CASE WHEN m.tipo_modificacao = 1 THEN 'Entrada' ELSE 'Saída' END AS tipo,
                        p.nome_produto AS produto,
                        c.nome_categoria AS categoria,
                        m.quantidade AS quantidade,
                        u.nome_usuario AS usuario,
                        CONCAT(
                            l.setor, ' - Corredor ', l.corredor,
                            ' - Prateleira ', l.prateleira,
                            ' - Andar ', l.andar
                        ) AS localizacao,
                        DATE_FORMAT(m.data_hora_movimentacao, '%d/%m/%Y %H:%i:%s') AS data_hora,
                        COALESCE(m.observacao, '') AS observacao
                    FROM movimentacoes m
                    INNER JOIN usuarios u ON u.id_usuario = m.id_usuario
                    INNER JOIN localizacao_produto lp
                        ON lp.id_localizacao_produto = m.id_localizacao_produto
                    INNER JOIN produtos p ON p.id_produto = lp.id_produto
                    INNER JOIN categorias c ON c.id_categoria = p.id_categoria
                    INNER JOIN localizacao l ON l.id_localizacao = lp.id_localizacao
                    WHERE 1 = 1
                    $filtrosProduto
                ";

                $valores = $valoresProduto;
                $tipos = $tiposProduto;

                if ($relatorio === 'entradas') {
                    $sql .= ' AND m.tipo_modificacao = 1';
                } elseif ($relatorio === 'saidas') {
                    $sql .= ' AND m.tipo_modificacao = 0';
                } elseif ($tipo !== '') {
                    $sql .= ' AND m.tipo_modificacao = ?';
                    $valores[] = ($tipo === 'entrada') ? 1 : 0;
                    $tipos .= 'i';
                }

                if ($dataInicial !== '') {
                    $sql .= ' AND m.data_hora_movimentacao >= ?';
                    $valores[] = $dataInicial . ' 00:00:00';
                    $tipos .= 's';
                }

                if ($dataFinal !== '') {
                    $dataFinalExclusiva = (new DateTimeImmutable($dataFinal))
                        ->modify('+1 day')
                        ->format('Y-m-d') . ' 00:00:00';

                    $sql .= ' AND m.data_hora_movimentacao < ?';
                    $valores[] = $dataFinalExclusiva;
                    $tipos .= 's';
                }

                $sql .= ' ORDER BY m.data_hora_movimentacao DESC';
                $registros = consultar($con, $sql, $tipos, $valores);
                break;
        }
    }
} catch (InvalidArgumentException $e) {
    $erro = $e->getMessage();
} catch (Throwable $e) {
    error_log('Erro no relatório Tera Atacado: ' . $e->getMessage());
    $erro = 'Não foi possível gerar o relatório. Confira a conexão com o banco e os dados cadastrados.';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Consulte e exporte relatórios demonstrativos do Tera Atacado.">
    <title>Relatórios | Tera Atacado</title>
    <link rel="icon" href="Img/tera_logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/tela_principal.css">
    <link rel="stylesheet" href="CSS/relatorio.css">
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
    <div><p class="module-eyebrow">TERA ATACADO / GESTÃO</p><h1>Relatórios</h1><p class="module-subtitle">Escolha um tipo de relatório e aplique os filtros disponíveis.</p></div>
    
</div>
<section class="report-type-grid" aria-label="Tipos de relatório">
    <button type="button" class="report-type-card <?= $relatorio === "produtos" ? "is-selected" : "" ?>" data-report="produtos" aria-pressed="<?= $relatorio === "produtos" ? "true" : "false" ?>"><span class="report-type-card__icon">▦</span><span class="report-type-card__title">Produtos</span><span class="report-type-card__description">Catálogo e situação de estoque.</span><span class="report-type-card__select"><?= $relatorio === "produtos" ? "Selecionado" : "Selecionar" ?></span></button>
    <button type="button" class="report-type-card <?= $relatorio === "entradas" ? "is-selected" : "" ?>" data-report="entradas" aria-pressed="<?= $relatorio === "entradas" ? "true" : "false" ?>"><span class="report-type-card__icon">＋</span><span class="report-type-card__title">Entradas</span><span class="report-type-card__description">Produtos recebidos no período.</span><span class="report-type-card__select"><?= $relatorio === "entradas" ? "Selecionado" : "Selecionar" ?></span></button>
    <button type="button" class="report-type-card <?= $relatorio === "saidas" ? "is-selected" : "" ?>" data-report="saidas" aria-pressed="<?= $relatorio === "saidas" ? "true" : "false" ?>"><span class="report-type-card__icon">−</span><span class="report-type-card__title">Saídas</span><span class="report-type-card__description">Produtos retirados do estoque.</span><span class="report-type-card__select"><?= $relatorio === "saidas" ? "Selecionado" : "Selecionar" ?></span></button>
    <button type="button" class="report-type-card <?= $relatorio === "estoque-baixo" ? "is-selected" : "" ?>" data-report="estoque-baixo" aria-pressed="<?= $relatorio === "estoque-baixo" ? "true" : "false" ?>"><span class="report-type-card__icon">!</span><span class="report-type-card__title">Estoque baixo</span><span class="report-type-card__description">Itens que precisam de reposição.</span><span class="report-type-card__select"><?= $relatorio === "estoque-baixo" ? "Selecionado" : "Selecionar" ?></span></button>
    <button type="button" class="report-type-card <?= $relatorio === "movimentacoes" ? "is-selected" : "" ?>" data-report="movimentacoes" aria-pressed="<?= $relatorio === "movimentacoes" ? "true" : "false" ?>"><span class="report-type-card__icon">↕</span><span class="report-type-card__title">Movimentações</span><span class="report-type-card__description">Histórico de entradas e saídas.</span><span class="report-type-card__select"><?= $relatorio === "movimentacoes" ? "Selecionado" : "Selecionar" ?></span></button>
</section>
<section class="module-panel" aria-labelledby="report-filter-title">
    <div class="module-panel__header"><div><h2 id="report-filter-title">Filtros do relatório</h2></div></div>
    <form id="report-form" class="module-form" method="GET" action="relatorio.php">
        <input type="hidden" name="relatorio" id="report-type-selection" value="<?= h($relatorio) ?>">
        <div class="module-form__grid module-form__grid--three">
            <div class="module-field"><label for="report-start">Data inicial</label><input type="date" id="report-start" name="dataInicial" value="<?= h($dataInicial !== "" ? $dataInicial : "2026-08-01") ?>"></div>
            <div class="module-field"><label for="report-end">Data final</label><input type="date" id="report-end" name="dataFinal" value="<?= h($dataFinal !== "" ? $dataFinal : "2026-10-31") ?>"></div>
            <div class="module-field"><label for="report-category">Categoria</label><select id="report-category" name="categoria"><option value="">Todas as categorias</option><?php foreach ($categorias as $item): ?><option value="<?= h($item["nome_categoria"]) ?>" <?= $categoria === $item["nome_categoria"] ? "selected" : "" ?>><?= h($item["nome_categoria"]) ?></option><?php endforeach; ?></select></div>
            <div class="module-field"><label for="report-product">Produto</label><select id="report-product" name="produto"><option value="">Todos os produtos</option><?php foreach ($produtosFiltro as $item): ?><option value="<?= h($item["nome_produto"]) ?>" <?= $produto === $item["nome_produto"] ? "selected" : "" ?>><?= h($item["nome_produto"]) ?></option><?php endforeach; ?></select></div>
            <div class="module-field"><label for="report-type">Tipo de movimentação</label><select id="report-type" name="tipo"><option value="">Entradas e saídas</option><option value="entrada" <?= $tipo === "entrada" ? "selected" : "" ?>>Entrada</option><option value="saida" <?= $tipo === "saida" ? "selected" : "" ?>>Saída</option></select></div>
        </div>
        <div class="module-form__footer"><div class="module-actions"><button type="reset" class="module-button module-button--secondary">Limpar filtros</button><button type="button" id="report-preview-button" class="module-button module-button--primary">Visualizar relatório</button><button type="button" id="report-export-csv" class="module-button module-button--secondary">Exportar CSV</button><button type="button" id="report-print" class="module-button module-button--outline-orange">Imprimir / salvar PDF</button></div></div>
    </form>
    <p class="module-feedback" id="report-feedback" role="status" <?= $erro !== "" ? "" : "hidden" ?>><?= h($erro) ?></p>
</section>
<section class="module-panel" id="report-results" aria-labelledby="report-results-title" <?= $consultaRealizada ? "" : "hidden" ?>>
    <div class="module-panel__header"><div><h2 id="report-results-title"><?= h($titulosRelatorios[$relatorio]) ?></h2><p id="report-results-description">Resultado conforme os filtros escolhidos.</p></div><span class="module-count" id="report-results-count"><?= count($registros) ?> <?= count($registros) === 1 ? "registro" : "registros" ?></span></div>
    <div class="module-table-wrap"><table class="module-table" id="report-results-table"><thead><tr><?php foreach ($colunas as $chave => $rotulo): ?><th scope="col"><?= h($rotulo) ?></th><?php endforeach; ?></tr></thead><tbody><?php foreach ($registros as $registro): ?><tr><?php foreach ($colunas as $chave => $rotulo): ?><td><?= h($registro[$chave] ?? "") ?></td><?php endforeach; ?></tr><?php endforeach; ?></tbody></table><p class="module-empty" id="report-empty" <?= count($registros) > 0 ? "hidden" : "" ?>>Nenhum registro corresponde aos filtros selecionados.</p></div>
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
<script src="JAVASCRIPT/relatorios.js" defer></script>
</body>
</html>
