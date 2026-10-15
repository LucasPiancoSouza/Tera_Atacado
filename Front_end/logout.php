<?php

session_start();

// Apaga os dados armazenados na sessão
$_SESSION = [];

// Remove o cookie da sessão do navegador
if (ini_get('session.use_cookies')) {
    $parametros = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $parametros['path'],
        $parametros['domain'],
        $parametros['secure'],
        $parametros['httponly']
    );
}

// Destrói a sessão
session_destroy();

// Volta para a tela de login
header('Location: index.php');
exit;