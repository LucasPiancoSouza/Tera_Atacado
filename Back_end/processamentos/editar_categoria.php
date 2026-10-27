<?php
error_reporting(0);
ini_set('display_errors', '0');
session_start();

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    http_response_code(401);
    echo json_encode(['erro' => 'Usuário não está logado.']);
    exit;
}

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== '1') {
    http_response_code(403);
    echo json_encode(['erro' => 'Usuário não tem permissão para editar categorias.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido.']);
    exit;
}
    $id = $_POST['id_categoria'] ?? '';
    $nome = trim($_POST['nome_categoria'] ?? '');

if (!is_string($id) || !ctype_digit($id) || (int) $id <= 0 || $nome === '') {
    http_response_code(400);
    echo json_encode(['erro' => 'Informe um ID válido e o nome da categoria.']);
    exit;
}
$sql = "select * from categorias where nome_categoria = '$nome'";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_execute($stmt);
        $resposta = mysqli_stmt_get_result($stmt);
        $linha = mysqli_num_rows($resposta);

        if($linha > 0){
            echo json_encode(['sucesso' => false, 'erro' => 'O nome da categoria já cadastrado.']);
            exit;
        }

$id = (int) $id;

        $stmt = $con->prepare("UPDATE categorias SET nome_categoria = ? WHERE id_categoria = ?");
        $stmt->bind_param("si", $nome, $id);
        
        if ($stmt->execute()) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Categoria atualizada com sucesso!']);
        } else {
            http_response_code(500);
            echo json_encode(['erro' => 'Erro ao atualizar a categoria no banco de dados.']);
        }
        $stmt->close();
   

?>