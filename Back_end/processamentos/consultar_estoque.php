
<?php

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido']);
    exit;
}

$id_produto = filter_input(INPUT_GET, 'id_produto', FILTER_VALIDATE_INT);
$id_localizacao = filter_input(INPUT_GET, 'id_localizacao', FILTER_VALIDATE_INT);

if (!$id_produto || !$id_localizacao || $id_produto <= 0 || $id_localizacao <= 0) {
    http_response_code(400);
    echo json_encode(['erro' => 'Produto ou localização inválidos']);
    exit;
}

try {
    $sql = "SELECT quantidade_produto
            FROM localizacao_produto
            WHERE id_produto = ?
              AND id_localizacao = ?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param('ii', $id_produto, $id_localizacao);
    $stmt->execute();

    $resultado = $stmt->get_result()->fetch_assoc();

    echo json_encode([
        'estoque_atual' => (int) ($resultado['quantidade_produto'] ?? 0)
    ]);

    $stmt->close();
} catch (Throwable $erro) {
    error_log($erro->getMessage());

    http_response_code(500);
    echo json_encode(['erro' => 'Não foi possível consultar o estoque']);
}
