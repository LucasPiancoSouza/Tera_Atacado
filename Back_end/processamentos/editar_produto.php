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

    $conexao = isset($con) && $con instanceof mysqli ? $con : (isset($conn) && $conn instanceof mysqli ? $conn : null);

    if (!$conexao) {
        echo json_encode(['sucesso' => false, 'erro' => 'Erro de conexão com a base de dados.']);
        exit;
    }
    

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = intval($_POST['id_produto'] ?? 0);
        $nome = trim($_POST['nome_produto'] ?? '');
        $codigo = trim($_POST['codigo_produto'] ?? '');
        $preco = trim($_POST['preco'] ?? 0);
        $codigoBarras = trim($_POST['codigo_de_barras'] ?? '');
        $idCategoria = trim($_POST['id_categoria'] ?? '');
        $idFornecedor = trim($_POST['id_fornecedor'] ?? '');
        $estoqueMinimo = trim($_POST['estoque_minimo'] ?? '');

        if ($id <= 0 || empty($nome) || empty($codigo) || empty($preco) || empty($idCategoria) || empty($idFornecedor) || $estoqueMinimo === '') {
            echo json_encode(['sucesso' => false, 'erro' => 'Preencha todos os campos obrigatórios para editar.']);
            exit;
        }

        $stmt = $conexao->prepare("UPDATE produtos SET nome_produto = ?, codigo_produto = ?, preco = ?, codigo_de_barras = ?, id_categoria = ?, id_fornecedor = ?, estoque_minimo = ? WHERE id_produto = ?");
        
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

        if (!$stmt) {
            echo json_encode(['sucesso' => false, 'erro' => 'Erro ao preparar edição: ' . $conexao->error]);
            exit;
        }

        $stmt->bind_param("ssdsiiii", $nome, $codigo, $preco, $codigoBarras, $idCategoria, $idFornecedor, $estoqueMinimo, $id);

        if ($stmt->execute()) {
            $stmt->close();
            echo json_encode(['sucesso' => true, 'mensagem' => 'Produto atualizado com sucesso!']);
            exit;
        } else {
            $erroSql = $stmt->error;
            $stmt->close();
            echo json_encode(['sucesso' => false, 'erro' => 'Erro ao atualizar o produto: ' . $erroSql]);
            exit;
        }
    } else {
        echo json_encode(['sucesso' => false, 'erro' => 'Método inválido.']);
        exit;
    }
} catch (Throwable $e) {
    echo json_encode(['sucesso' => false, 'erro' => 'Erro interno: ' . $e->getMessage()]);
    exit;
}
?>