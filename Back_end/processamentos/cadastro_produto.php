<?php
error_reporting(0);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

try {
    require_once __DIR__ . '/../config/database.php';

    $conexao = null;
    if (isset($con) && $con instanceof mysqli) {
        $conexao = $con;
    } elseif (isset($conn) && $conn instanceof mysqli) {
        $conexao = $conn;
    }

    if (!$conexao) {
        echo json_encode(['sucesso' => false, 'erro' => 'Erro de conexão com a base de dados.']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = trim($_POST['nome_produto'] ?? '');
        $codigo = trim($_POST['codigo_produto'] ?? '');
        $preco = trim($_POST['preco'] ?? 0);
        $codigoBarras = trim($_POST['codigo_de_barras'] ?? '');
        $idCategoria = trim($_POST['id_categoria'] ?? '');
        $idFornecedor = trim($_POST['id_fornecedor'] ?? '');

        if (empty($nome) || empty($codigo) || empty($preco) || empty($idCategoria) || empty($idFornecedor)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Por favor, preencha todos os campos obrigatórios.']);
            exit;
        }

        $stmt = $conexao->prepare("INSERT INTO produtos (nome_produto, codigo_produto, preco, codigo_de_barras, id_categoria, id_fornecedor) VALUES (?, ?, ?, ?, ?, ?)");
        
        if (!$stmt) {
            echo json_encode(['sucesso' => false, 'erro' => 'Erro na preparação da query: ' . $conexao->error]);
            exit;
        }

        $stmt->bind_param("ssdsii", $nome, $codigo, $preco, $codigoBarras, $idCategoria, $idFornecedor);
        
        if ($stmt->execute()) {
            $stmt->close();
            echo json_encode(['sucesso' => true, 'mensagem' => 'Produto cadastrado com sucesso!']);
            exit;
        } else {
            $erroExec = $stmt->error;
            $stmt->close();
            echo json_encode(['sucesso' => false, 'erro' => 'Erro ao executar o cadastro: ' . $erroExec]);
            exit;
        }
    } else {
        echo json_encode(['sucesso' => false, 'erro' => 'Método de requisição inválido.']);
        exit;
    }
} catch (Throwable $e) {
    echo json_encode(['sucesso' => false, 'erro' => 'Erro interno no servidor: ' . $e->getMessage()]);
    exit;
}
?>