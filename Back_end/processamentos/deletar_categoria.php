<?php
session_start();
error_reporting(0);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/database.php';


if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    http_response_code(401);
    echo json_encode(['erro' => 'Usuário não está logado.']);
    exit;
}

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== '1') {
    http_response_code(403);
    echo json_encode(['erro' => 'Usuário não tem permissão para excluir categorias.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido.']);
    exit;
}

    $id = $_POST['id_categoria'] ?? '';

    if (!empty($id) && is_string($id)) {
        $stmt = $con->prepare("DELETE FROM categorias WHERE id_categoria = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Categoria excluída com sucesso!']);
        } else {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao excluir a categoria no banco de dados.']);
        }
        $stmt->close();
    }
    else {
        http_response_code(400);
        echo json_encode(['erro' => 'ID da categoria inválido.']);
    }


?>