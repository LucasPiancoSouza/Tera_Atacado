<?php
session_start();
require_once __DIR__ . '/../Back_end/config/database.php';

$cpf = trim($_POST['CPF'] ?? '');
$cpf = str_replace([".","-"],"",$cpf); // o que devo procurar "- e ." pelo oque subistituo "" aonde devo procurar $cpf
$senha = $_POST['senha'] ?? '';

$stmt = mysqli_prepare(
    $con,
    'SELECT senha, tipo_usuario FROM usuarios WHERE cpf = ? LIMIT 1'
);

mysqli_stmt_bind_param($stmt, 's', $cpf);
mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($resultado); # tansforma em um array associativo

if (!$usuario || $senha !== $usuario['senha']) {
    $_SESSION['logado'] = false;
    $_SESSION['erro_login'] = true ;
    header('Location: index.php');
    exit;
    }
    
    $_SESSION['logado'] = true;
    $_SESSION['erro_login'] = false ;

if ($usuario['tipo_usuario'] === '1') {
    $_SESSION['tipo_usuario'] = 'adm';
} else {
    $_SESSION['tipo_usuario'] = 'funcionario';
}

header('Location: tela_principal.php');
exit;
?>