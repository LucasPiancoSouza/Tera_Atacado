<?php
error_reporting(0);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/database.php';

try {
    $sql = "SELECT id_produto AS id, nome_produto AS nome, preco, quantidade FROM produtos ORDER BY nome_produto ASC";
    $result = $conn->query($sql);

    $produtos = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $produtos[] = $row;
        }
    }

    echo json_encode($produtos);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['erro' => 'Falha ao carregar produtos']);
}
?>