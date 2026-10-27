<?php
error_reporting(0);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/database.php';

try {
    $conexao = null;
    if (isset($con) && $con instanceof mysqli) {
        $conexao = $con;
    } elseif (isset($conn) && $conn instanceof mysqli) {
        $conexao = $conn;
    }

    if (!$conexao) {
        echo json_encode(['sucesso' => false, 'erro' => 'Erro de conexão com o banco de dados.']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['sucesso' => false, 'erro' => 'Método inválido.']);
        exit;
    }

    $nome = trim($_POST['nome_fornecedor'] ?? '');
    $empresa = trim($_POST['empresa'] ?? '');
    $cnpj = trim($_POST['cnpj'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $cep = preg_replace('/\D/', '', trim($_POST['cep'] ?? ''));
    $rua_numero = trim($_POST['rua_numero'] ?? '');
    $cidade = trim($_POST['cidade'] ?? '');
    $estado = trim($_POST['estado'] ?? '');

    if (empty($nome) || empty($empresa) || empty($cnpj) || empty($email)) {
        echo json_encode(['sucesso' => false, 'erro' => 'Preencha todos os campos obrigatórios.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['sucesso' => false, 'erro' => 'E-mail inválido.']);
        exit;
    }

    if ($cep !== '' && strlen($cep) !== 8) {
        echo json_encode(['sucesso' => false, 'erro' => 'CEP inválido. Informe os 8 dígitos ou deixe o campo vazio.']);
        exit;
    }

    // Verificar se o CNPJ já existe
    $stmtCheck = $conexao->prepare("SELECT id_fornecedor FROM fornecedores WHERE cnpj = ?");
    $stmtCheck->bind_param("s", $cnpj);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows > 0) {
        echo json_encode(['sucesso' => false, 'erro' => 'Este CNPJ já está cadastrado.']);
        exit;
    }
    $stmtCheck->close();

    $sql = "INSERT INTO fornecedores (nome_fornecedor, empresa, cnpj, telefone, email, cep, rua_numero, cidade, estado) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sssssssss", $nome, $empresa, $cnpj, $telefone, $email, $cep, $rua_numero, $cidade, $estado);

    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Fornecedor cadastrado com sucesso!']);
    } else {
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao salvar o fornecedor no banco de dados.']);
    }

    $stmt->close();
} catch (Throwable $e) {
    echo json_encode(['sucesso' => false, 'erro' => 'Erro interno no servidor.']);
}
?>