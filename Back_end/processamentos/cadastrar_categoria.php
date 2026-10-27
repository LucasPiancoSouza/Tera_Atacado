<?php
session_start();
error_reporting(0);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

try {
    require_once __DIR__ . '/../config/database.php';

    $conexao = isset($con) && $con instanceof mysqli ? $con : (isset($conn) && $conn instanceof mysqli ? $conn : null);

    if (!$conexao) {
        echo json_encode(['sucesso' => false, 'erro' => 'Erro de conexão com a base de dados.']);
        exit;
    }
      if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    http_response_code(401);
    echo json_encode(['erro' => 'Usuário não está logado.']);
    exit;
    }

    if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== '1') {
        http_response_code(403);
        echo json_encode(['sucesso' => false, 'erro' => 'Usuário não tem permissão para cadastrar categorias.']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nomeCategoria = trim($_POST['nome_categoria'] ?? '');

        $sql = "select * from categorias where nome_categoria = '$nomeCategoria'";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_execute($stmt);
        $resposta = mysqli_stmt_get_result($stmt);
        $linha = mysqli_num_rows($resposta);

        if($linha > 0){
            echo json_encode(['sucesso' => false, 'erro' => 'O nome da categoria já cadastrado.']);
            exit;
        }


        if (empty($nomeCategoria)) {
            echo json_encode(['sucesso' => false, 'erro' => 'O nome da categoria é obrigatório.']);
            exit;
        }
    

        // INSERT simples exigindo apenas o nome_categoria
        $stmt = $conexao->prepare("INSERT INTO categorias (nome_categoria) VALUES (?)");
        
        if (!$stmt) {
            echo json_encode(['sucesso' => false, 'erro' => 'Erro na preparação da query: ' . $conexao->error]);
            exit;
        }

        $stmt->bind_param("s", $nomeCategoria);

        if ($stmt->execute()) {
            $stmt->close();
            echo json_encode(['sucesso' => true, 'mensagem' => 'Categoria cadastrada com sucesso!']);
            exit;
        } else {
            $erroSql = $stmt->error;
            $stmt->close();
            echo json_encode(['sucesso' => false, 'erro' => 'Erro ao cadastrar categoria: ' . $erroSql]);
            exit;
        }
    } else {
        http_response_code(405);
        echo json_encode(['sucesso' => false, 'erro' => 'Método inválido.']);
        exit;
    }
} catch (Throwable $e) {
    echo json_encode(['sucesso' => false, 'erro' => 'Erro interno: ' . $e->getMessage()]);
    exit;
}
?>