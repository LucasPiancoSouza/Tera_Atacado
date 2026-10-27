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
        echo json_encode(['sucesso' => false, 'erro' => 'Usuário não tem permissão para excluir fornecedores.']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['sucesso' => false, 'erro' => 'Método de requisição inválido.']);
        exit;
    }

    $conexao = null;
    if (isset($con) && $con instanceof mysqli) {
        $conexao = $con;
    } elseif (isset($conn) && $conn instanceof mysqli) {
        $conexao = $conn;
    }

    if (!$conexao) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Erro de conexão com o banco de dados.']);
        exit;
    }

    $idRecebido = $_POST['id_fornecedor'] ?? '';
    if (!is_string($idRecebido) || !ctype_digit($idRecebido) || (int) $idRecebido <= 0) {
        http_response_code(422);
        echo json_encode(['sucesso' => false, 'erro' => 'ID de fornecedor inválido.']);
        exit;
    }

    $idFornecedor = (int) $idRecebido;
    $stmt = $conexao->prepare('DELETE FROM fornecedores WHERE id_fornecedor = ?');

    if (!$stmt) {
        throw new RuntimeException('Não foi possível preparar a exclusão do fornecedor.');
    }

    $stmt->bind_param('i', $idFornecedor);

    if (!$stmt->execute()) {
        $codigoErro = $stmt->errno;
        $stmt->close();

        if ($codigoErro === 1451) {
            http_response_code(409);
            echo json_encode([
                'sucesso' => false,
                'erro' => 'Não é possível excluir este fornecedor porque há produtos vinculados a ele.'
            ]);
            exit;
        }

        throw new RuntimeException('Não foi possível excluir o fornecedor no banco de dados.');
    }

    $fornecedorExcluido = $stmt->affected_rows > 0;
    $stmt->close();

    if (!$fornecedorExcluido) {
        http_response_code(404);
        echo json_encode(['sucesso' => false, 'erro' => 'Fornecedor não encontrado.']);
        exit;
    }

    echo json_encode(['sucesso' => true, 'mensagem' => 'Fornecedor excluído com sucesso!']);
} catch (mysqli_sql_exception $e) {
    error_log('Erro ao excluir fornecedor: ' . $e->getMessage());
    if ((int) $e->getCode() === 1451) {
        http_response_code(409);
        echo json_encode([
            'sucesso' => false,
            'erro' => 'Não é possível excluir este fornecedor porque há produtos vinculados a ele.'
        ]);
        exit;
    }

    http_response_code(500);
    echo json_encode(['sucesso' => false, 'erro' => 'Erro interno ao excluir o fornecedor.']);
} catch (Throwable $e) {
    error_log('Erro ao excluir fornecedor: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'erro' => 'Erro interno ao excluir o fornecedor.']);
}
?>