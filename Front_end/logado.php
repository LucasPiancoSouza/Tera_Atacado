<?php
session_start();

if (empty($_SESSION['logado'])) {
    header('Location: index.php');
    exit;
}

if ($_SESSION['tipo_usuario'] === 'adm') {
    echo 'Bem-vindo, administrador!';
} else {
    echo 'Bem-vindo, funcionário!';
}
?>