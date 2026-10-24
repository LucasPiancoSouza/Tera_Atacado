<?php
error_reporting(0);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/database.php';

try {
    $conexao = isset($con) && $con instanceof mysqli ? $con : (isset($conn) && $conn instanceof mysqli ? $conn : null);

    if (!$conexao) {
        echo json_encode([]);
        exit;
    }

    // Consulta apenas o ID e o Nome, sem colunas de quantidade ou data desnecessárias
    $sql = "SELECT id_categoria, nome_categoria, data_criacao
            FROM categorias
            ORDER BY nome_categoria ASC";
    $result = $conexao->query($sql);

    $categorias = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
    }

    echo json_encode($categorias);
} catch (Throwable $e) {
    echo json_encode([]);
}
?>
