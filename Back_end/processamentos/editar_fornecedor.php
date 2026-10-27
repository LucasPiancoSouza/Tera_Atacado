<?php
error_reporting(0);
ini_set('display_errors', '0');
header('Content-Type: application/json; charset=utf-8');
session_start();

try {
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

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['sucesso' => false, 'erro' => 'Método de requisição inválido.']);
        exit;
    }

    $idFornecedor = filter_var(
        $_POST['id_fornecedor'] ?? null,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    );

    if ($idFornecedor === false || $idFornecedor === null) {
        http_response_code(422);
        echo json_encode(['sucesso' => false, 'erro' => 'ID do fornecedor inválido.']);
        exit;
    }

    $campos = [
        'nome_fornecedor' => trim($_POST['nome_fornecedor'] ?? ''),
        'empresa' => trim($_POST['empresa'] ?? ''),
        'cnpj' => trim($_POST['cnpj'] ?? ''),
        'telefone' => trim($_POST['telefone'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'cep' => preg_replace('/\D/', '', trim($_POST['cep'] ?? '')),
        'rua_numero' => trim($_POST['rua_numero'] ?? ''),
        'cidade' => trim($_POST['cidade'] ?? ''),
        'estado' => trim($_POST['estado'] ?? ''),
    ];

    foreach ($campos as $valor) {
        if ($valor === '') {
            http_response_code(422);
            echo json_encode(['sucesso' => false, 'erro' => 'Preencha todos os campos do fornecedor.']);
            exit;
        }
    }

    if (!filter_var($campos['email'], FILTER_VALIDATE_EMAIL)) {
        http_response_code(422);
        echo json_encode(['sucesso' => false, 'erro' => 'Informe um endereço de e-mail válido.']);
        exit;
    }

    if (strlen($campos['cep']) !== 8) {
        http_response_code(422);
        echo json_encode(['sucesso' => false, 'erro' => 'O CEP deve conter 8 dígitos.']);
        exit;
    }

    $limites = [
        'nome_fornecedor' => 100,
        'empresa' => 100,
        'cnpj' => 18,
        'telefone' => 15,
        'email' => 100,
        'rua_numero' => 255,
        'cidade' => 100,
        'estado' => 100,
    ];

    foreach ($limites as $campo => $limite) {
        $tamanho = preg_match_all('/./us', $campos[$campo], $caracteres);
        if ($tamanho === false || $tamanho > $limite) {
            http_response_code(422);
            echo json_encode(['sucesso' => false, 'erro' => 'Um ou mais campos excedem o tamanho permitido.']);
            exit;
        }
    }

    $stmtExiste = $conexao->prepare(
        'SELECT id_fornecedor FROM fornecedores WHERE id_fornecedor = ?'
    );
    if (!$stmtExiste) {
        throw new RuntimeException('Não foi possível verificar o fornecedor.');
    }
    $stmtExiste->bind_param('i', $idFornecedor);
    if (!$stmtExiste->execute()) {
        throw new RuntimeException('Não foi possível verificar o fornecedor.');
    }
    $stmtExiste->store_result();
    $fornecedorExiste = $stmtExiste->num_rows > 0;
    $stmtExiste->close();

    if (!$fornecedorExiste) {
        http_response_code(404);
        echo json_encode(['sucesso' => false, 'erro' => 'Fornecedor não encontrado.']);
        exit;
    }

    $stmtCnpj = $conexao->prepare(
        'SELECT id_fornecedor
         FROM fornecedores
         WHERE cnpj = ? AND id_fornecedor <> ?
         LIMIT 1'
    );
    if (!$stmtCnpj) {
        throw new RuntimeException('Não foi possível verificar o CNPJ.');
    }
    $stmtCnpj->bind_param('si', $campos['cnpj'], $idFornecedor);
    if (!$stmtCnpj->execute()) {
        throw new RuntimeException('Não foi possível verificar o CNPJ.');
    }
    $stmtCnpj->store_result();
    $cnpjDuplicado = $stmtCnpj->num_rows > 0;
    $stmtCnpj->close();

    if ($cnpjDuplicado) {
        http_response_code(409);
        echo json_encode(['sucesso' => false, 'erro' => 'Este CNPJ já está cadastrado para outro fornecedor.']);
        exit;
    }

    $stmtAtualizar = $conexao->prepare(
        'UPDATE fornecedores
         SET nome_fornecedor = ?, empresa = ?, cnpj = ?, telefone = ?, email = ?,
             cep = ?, rua_numero = ?, cidade = ?, estado = ?
         WHERE id_fornecedor = ?'
    );
    if (!$stmtAtualizar) {
        throw new RuntimeException('Não foi possível preparar a atualização do fornecedor.');
    }

    $stmtAtualizar->bind_param(
        'sssssssssi',
        $campos['nome_fornecedor'],
        $campos['empresa'],
        $campos['cnpj'],
        $campos['telefone'],
        $campos['email'],
        $campos['cep'],
        $campos['rua_numero'],
        $campos['cidade'],
        $campos['estado'],
        $idFornecedor
    );
    if (!$stmtAtualizar->execute()) {
        $codigoErro = $stmtAtualizar->errno;
        $stmtAtualizar->close();

        if ($codigoErro === 1062) {
            http_response_code(409);
            echo json_encode(['sucesso' => false, 'erro' => 'Este CNPJ já está cadastrado para outro fornecedor.']);
            exit;
        }

        throw new RuntimeException('Não foi possível atualizar o fornecedor no banco de dados.');
    }
    $stmtAtualizar->close();

    echo json_encode(['sucesso' => true, 'mensagem' => 'Fornecedor atualizado com sucesso.']);
} catch (mysqli_sql_exception $e) {
    error_log('Erro ao editar fornecedor: ' . $e->getMessage());
    if ((int) $e->getCode() === 1062) {
        http_response_code(409);
        echo json_encode(['sucesso' => false, 'erro' => 'Este CNPJ já está cadastrado para outro fornecedor.']);
        exit;
    }

    http_response_code(500);
    echo json_encode(['sucesso' => false, 'erro' => 'Não foi possível atualizar o fornecedor no banco de dados.']);
} catch (Throwable $e) {
    error_log('Erro ao editar fornecedor: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'erro' => 'Erro interno ao atualizar o fornecedor.']);
}
?>