<?php
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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = intval($_POST['id_produto'] ?? 0);
        $nome = trim($_POST['nome_produto'] ?? '');
        $codigo = trim($_POST['codigo_produto'] ?? '');
        $preco = trim($_POST['preco'] ?? 0);
        $codigoBarras = trim($_POST['codigo_de_barras'] ?? '');
        $idCategoria = trim($_POST['id_categoria'] ?? '');
        $idFornecedor = trim($_POST['id_fornecedor'] ?? '');

        if ($id <= 0 || empty($nome) || empty($codigo) || empty($preco) || empty($idCategoria) || empty($idFornecedor)) {
            echo json_encode(['sucesso' => false, 'erro' => 'Preencha todos os campos obrigatórios para editar.']);
            exit;
        }

        $stmt = $conexao->prepare("UPDATE produtos SET nome_produto = ?, codigo_produto = ?, preco = ?, codigo_de_barras = ?, id_categoria = ?, id_fornecedor = ? WHERE id_produto = ?");
        
        if (!$stmt) {
            echo json_encode(['sucesso' => false, 'erro' => 'Erro ao preparar edição: ' . $conexao->error]);
            exit;
        }

        $stmt->bind_param("ssdsiii", $nome, $codigo, $preco, $codigoBarras, $idCategoria, $idFornecedor, $id);

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