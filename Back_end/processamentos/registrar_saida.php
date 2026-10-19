<?php

session_start();

require_once __DIR__ . '/../config/database.php';

// Redireciona para a tela de saída com uma mensagem de erro.
function voltarComErro(string $erro): void
{
    header(
        'Location: ../../Front_end/saida.php?erro=' . urlencode($erro)
    );
    exit;
}

// Somente requisições POST são aceitas.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método não permitido.');
}

// Verifica o usuário autenticado.
if (
    empty($_SESSION['logado']) ||
    empty($_SESSION['id_usuario'])
) {
    header('Location: ../../Front_end/index.php?erro=sessao');
    exit;
}

$idUsuario = (int) $_SESSION['id_usuario'];

$idProduto = filter_var(
    $_POST['id_produto'] ?? null,
    FILTER_VALIDATE_INT
);

$idLocalizacao = filter_var(
    $_POST['id_localizacao'] ?? null,
    FILTER_VALIDATE_INT
);

$quantidade = filter_var(
    $_POST['quantidade'] ?? null,
    FILTER_VALIDATE_INT
);

$motivoEnviado = trim((string) ($_POST['motivo'] ?? ''));
$observacaoExtra = trim((string) ($_POST['observacao'] ?? ''));

// Aceita tanto os valores antigos do formulário quanto os rótulos atuais.
$motivosPermitidos = [
    'venda'              => 'Venda',
    'Venda'              => 'Venda',
    'avaria'             => 'Avaria',
    'Avaria'             => 'Avaria',
    'consumo'             => 'Uso interno',
    'Uso interno'         => 'Uso interno',
    'ajuste'              => 'Ajuste de estoque',
    'Ajuste de estoque'   => 'Ajuste de estoque',
    'outro'               => 'Outro',
    'Outro'               => 'Outro'
];

if (
    !$idProduto ||
    !$idLocalizacao ||
    !$quantidade ||
    $idProduto < 1 ||
    $idLocalizacao < 1 ||
    $quantidade < 1 ||
    !isset($motivosPermitidos[$motivoEnviado]) ||
    mb_strlen($observacaoExtra) > 500
) {
    voltarComErro('dados_invalidos');
}

$motivo = $motivosPermitidos[$motivoEnviado];

$observacao = "Motivo: {$motivo}";

if ($observacaoExtra !== '') {
    $observacao .= "\nObservação: {$observacaoExtra}";
}

try {
    // Inicia uma transação para proteger a consistência do estoque.
    $con->begin_transaction();

    /*
     * Localiza o registro de estoque do produto naquele local.
     * FOR UPDATE bloqueia a linha durante a transação.
     */
    $sql = "SELECT id_localizacao_produto, quantidade_produto
            FROM localizacao_produto
            WHERE id_produto = ?
              AND id_localizacao = ?
            FOR UPDATE";

    $stmt = $con->prepare($sql);
    $stmt->bind_param('ii', $idProduto, $idLocalizacao);
    $stmt->execute();

    $estoque = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$estoque) {
        $con->rollback();
        voltarComErro('produto_local_nao_encontrado');
    }

    $idLocalizacaoProduto = (int) $estoque['id_localizacao_produto'];
    $estoqueAtual = (int) $estoque['quantidade_produto'];

    // Impede retirar mais unidades do que estão disponíveis.
    if ($quantidade > $estoqueAtual) {
        $con->rollback();
        voltarComErro('estoque_insuficiente');
    }

    // Desconta a quantidade do estoque daquele local.
    $sqlAtualizar = "UPDATE localizacao_produto
                     SET quantidade_produto = quantidade_produto - ?
                     WHERE id_localizacao_produto = ?
                       AND quantidade_produto >= ?";

    $stmtAtualizar = $con->prepare($sqlAtualizar);
    $stmtAtualizar->bind_param(
        'iii',
        $quantidade,
        $idLocalizacaoProduto,
        $quantidade
    );
    $stmtAtualizar->execute();

    if ($stmtAtualizar->affected_rows !== 1) {
        $stmtAtualizar->close();
        $con->rollback();
        voltarComErro('estoque_insuficiente');
    }

    $stmtAtualizar->close();

    // Registra a saída: tipo_modificacao = 0.
    $sqlMovimentacao = "INSERT INTO movimentacoes (
                            tipo_modificacao,
                            quantidade,
                            id_usuario,
                            observacao,
                            id_localizacao_produto
                        )
                        VALUES (0, ?, ?, ?, ?)";

    $stmtMovimentacao = $con->prepare($sqlMovimentacao);
    $stmtMovimentacao->bind_param(
        'iisi',
        $quantidade,
        $idUsuario,
        $observacao,
        $idLocalizacaoProduto
    );
    $stmtMovimentacao->execute();
    $stmtMovimentacao->close();

    // Confirma as duas alterações.
    $con->commit();

    header('Location: ../../Front_end/saida.php?sucesso=1');
    exit;

} catch (Throwable $erro) {
    try {
        $con->rollback();
    } catch (Throwable $erroRollback) {
        // A transação pode já ter sido desfeita.
    }

    error_log('Erro ao registrar saída: ' . $erro->getMessage());

    voltarComErro('falha');
}