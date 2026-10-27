<?php
error_reporting(0);
ini_set('display_errors', '0');
session_start();

header('Content-Type: application/json; charset=utf-8');

try {
    require_once __DIR__ . '/../config/database.php';

    if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
        http_response_code(401);
        echo json_encode(['sucesso' => false, 'erro' => 'Usuário não está logado.']);
        exit;
    }

    if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== '1') {
        http_response_code(403);
        echo json_encode(['sucesso' => false, 'erro' => 'Usuário não tem permissão para excluir produtos.']);
        exit;
    }

    $conexao = isset($con) && $con instanceof mysqli ? $con : (isset($conn) && $conn instanceof mysqli ? $conn : null);

    if (!$conexao) {
        echo json_encode(['sucesso' => false, 'erro' => 'Erro de conexão com a base de dados.']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $idRecebido = $_POST['id_produto'] ?? '';

        if (!is_string($idRecebido) || !ctype_digit($idRecebido) || (int) $idRecebido <= 0) {
            echo json_encode(['sucesso' => false, 'erro' => 'ID de produto inválido.']);
            exit;
        }
        $id = (int) $idRecebido;

        // Opcional: Se houver tabelas filhas (ex: movimentações de estoque), podes verificar aqui antes de apagar.
        // Como a tabela produtos está relacionada a entradas/saídas, se houver restrição de FK, o MySQL rejeitará ou precisará de tratamento.
        $stmt = $conexao->prepare("DELETE FROM produtos WHERE id_produto = ?");
        
        if (!$stmt) {
            echo json_encode(['sucesso' => false, 'erro' => 'Erro ao preparar exclusão: ' . $conexao->error]);
            exit;
        }

        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $stmt->close();
                echo json_encode(['sucesso' => true, 'mensagem' => 'Produto excluído com sucesso!']);
                exit;
            } else {
                $stmt->close();
                echo json_encode(['sucesso' => false, 'erro' => 'Produto não encontrado.']);
                exit;
            }
        } else {
            $erroSql = $stmt->error;
            $stmt->close();
            // Tratamento amigável para erro de chave estrangeira (relacionamento ativo)
            if (stripos($erroSql, 'foreign key constraint fails') !== false) {
                echo json_encode(['sucesso' => false, 'erro' => 'Não é possível excluir este produto pois existem registos de estoque/movimentações associados a ele.']);
            } else {
                echo json_encode(['sucesso' => false, 'erro' => 'Erro ao excluir o produto: ' . $erroSql]);
            }
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