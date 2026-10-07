<?php
session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/tela_principal.css">
    <title>Tera Atacado</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@500;600&display=swap" rel="stylesheet">

</head>
<body>

    <header class="header">
        <div class="logodiv">
            <img src="Img/logo_tera.png" class="logo" alt="Logo Tera Atacado">
        </div>

        <main class="main">
            <p class="texto">
                Seja bem-vindo ao sistema de gerenciamento<br>
                de estoque do <span class="destaque">Tera Atacado</span>
            </p>
        </main>
        
    </header>

    <div class="layout">

        <aside class="sidebar">
            <div class="sidebar-header">
                 <nav>

        <div class="menu-section">
            <h3>PRINCIPAL</h3>

            <a href="#" class="menu-item active">
                <span>⌂</span>
                <span>Dashboard</span>
            </a>

            <a href="#" class="menu-item">
                <span>▦</span>
                <span>Produtos</span>
            </a>

            <a href="#" class="menu-item">
                <span>▤</span>
                <span>Categorias</span>
            </a>

            <a href="#" class="menu-item">
                <span>▥</span>
                <span>Fornecedores</span>
            </a>
        </div>


        <div class="menu-section">
            <h3>ESTOQUE</h3>

            <a href="#" class="menu-item">
                <span>＋</span>
                <span>Entradas</span>
            </a>

            <a href="#" class="menu-item">
                <span>−</span>
                <span>Saídas</span>
            </a>

            <a href="#" class="menu-item">
                <span>↕</span>
                <span>Movimentações</span>
            </a>
        </div>


        <div class="menu-section">
            <h3>GESTÃO</h3>

            <a href="#" class="menu-item">
                <span>▥</span>
                <span>Relatórios</span>
            </a>
        </div>


        <div class="menu-section">
            <h3>CONTA</h3>

            <a href="#" class="menu-item">
                <span>◎</span>
                <span>Meu perfil</span>
            </a>

            <a href="#" class="menu-item">
                <span>↪</span>
                <span>Sair</span>
            </a>
        </div>

    </nav>

            </div>
        </aside>

        

    </div>



   
</body>
</html>