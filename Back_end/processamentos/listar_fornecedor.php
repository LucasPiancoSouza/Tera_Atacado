<?php
error_reporting(0);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/database.php';

try {
    $con = isset($con) && $con instanceof mysqli ? $con : (isset($con) && $con instanceof mysqli ? $con : null);

    if (!$con) {
        echo json_encode([]);
        exit;
    }

    $sql = "SELECT id_fornecedor, nome_fornecedor, empresa, cnpj, telefone, email,
                   cep, estado, cidade, rua_numero
            FROM fornecedores
            ORDER BY nome_fornecedor ASC";

    $result = $con->query($sql);

    $fornecedores = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $fornecedores[] = $row;
        }
    }

    echo json_encode($fornecedores);
} catch (Throwable $e) {
    echo json_encode([]);
}
?>
