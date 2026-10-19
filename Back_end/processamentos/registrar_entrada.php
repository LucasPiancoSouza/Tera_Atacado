<?php
// Isso evita que alguém abra diretamente o 
// endereço do processamento pelo navegador
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../Front_end/entrada.php");
    exit;
}
session_start();
require_once __DIR__ . '/../config/database.php';

$id_produto = (int) $_POST['id_produto'];
$id_localizacao = (int) $_POST['id_localizacao'];
$quantidade = (int) $_POST['quantidade'];
$observacao = trim($_POST['observacao'] ?? '');
$id_usuario = (int) ($_SESSION['id'] ?? 0);
if ($id_produto <= 0 || $id_localizacao <= 0 || $quantidade <= 0 || $id_usuario <= 0) {
    header("Location: ../../Front_end/entrada.php");
    exit;
}
try {
    $con->begin_transaction();

    // 1. Verifica se o produto já está nessa localização
    $sql = "SELECT id_localizacao_produto
            FROM localizacao_produto
            WHERE id_produto = ? AND id_localizacao = ?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $id_produto, $id_localizacao);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $estoque = $resultado->fetch_assoc();
    $stmt->close();

    if ($estoque) {
        // 2. Se existir, soma a quantidade
        $id_localizacao_produto = (int) $estoque['id_localizacao_produto'];

        $sql = "UPDATE localizacao_produto
                SET quantidade_produto = quantidade_produto + ?
                WHERE id_localizacao_produto = ?";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("ii", $quantidade, $id_localizacao_produto);
        $stmt->execute();
        $stmt->close();

    } else {
        // Se não existir, cria o registro de estoque
        $sql = "INSERT INTO localizacao_produto
                    (id_localizacao, id_produto, quantidade_produto)
                VALUES (?, ?, ?)";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("iii", $id_localizacao, $id_produto, $quantidade);
        $stmt->execute();

        $id_localizacao_produto = $con->insert_id;
        $stmt->close();
    }

    // 3. Registra a movimentação de entrada
    $sql = "INSERT INTO movimentacoes
                (tipo_modificacao, quantidade, id_usuario,
                 observacao, id_localizacao_produto)
            VALUES (1, ?, ?, ?, ?)";

    $stmt = $con->prepare($sql);
    $stmt->bind_param(
        "iisi",
        $quantidade,
        $id_usuario,
        $observacao,
        $id_localizacao_produto
    );

    $stmt->execute();
    $stmt->close();

    $con->commit();

    header("Location: ../../Front_end/entrada.php?sucesso=1");
    exit;

} catch (Throwable $erro) {
    $con->rollback();
    error_log($erro->getMessage());

    header("Location: ../../Front_end/entrada.php?erro=registro");
    exit;
}
?>