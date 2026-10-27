<?php
error_reporting(0);
ini_set('display_errors', '0');
session_start();
header('Content-Type: application/json; charset=utf-8');

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
        $estoqueMinimo = trim($_POST['estoque_minimo']) ?? null;
            
        if (empty($nome) || empty($codigo) || empty($preco) || empty($idCategoria) || empty($idFornecedor) || $estoqueMinimo === false) {
            echo json_encode(['sucesso' => false, 'erro' => 'Por favor, preencha todos os campos obrigatórios.']);
            exit;
        }

        $stmt = $conexao->prepare("INSERT INTO produtos (nome_produto, codigo_produto, preco, codigo_de_barras, id_categoria, id_fornecedor, estoque_minimo) VALUES (?, ?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            echo json_encode(['sucesso' => false, 'erro' => 'Erro na preparação da query: ' . $conexao->error]);
            exit;
        }
       
        $stmtVerificacao = $conexao->prepare(
            "SELECT 1 FROM produtos
            WHERE codigo_produto = ? OR codigo_de_barras = ?
            LIMIT 1"
        );

        if (!$stmtVerificacao) {
            echo json_encode([
                'sucesso' => false,
                'erro' => 'Erro ao preparar a verificação de duplicidade.'
            ]);
            exit;
        }

        $stmtVerificacao->bind_param("ss", $codigo, $codigoBarras);

        if (!$stmtVerificacao->execute()) {
            echo json_encode([
                'sucesso' => false,
                'erro' => 'Erro ao verificar os códigos do produto.'
            ]);
            exit;
        }

        $verificacao = $stmtVerificacao->get_result();

        if ($verificacao->num_rows > 0) {
            $stmtVerificacao->close();
            echo json_encode([
                'sucesso' => false,
                'erro' => 'O código do produto ou o código de barras já está cadastrado.'
            ]);
            exit;
        }

        $stmtVerificacao->close();

        $stmt->bind_param("ssdsiii", $nome, $codigo, $preco, $codigoBarras, $idCategoria, $idFornecedor, $estoqueMinimo);
        
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