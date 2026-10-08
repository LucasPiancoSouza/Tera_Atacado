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
    <title>Document</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@500;600&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">


</head>
<body>

    <header class="header">
        <div class="logodiv">
            <img src="Img/tera_logo.png" class="logo" alt="Logo Tera Atacado">
        </div>

            <p class="texto">
                Seja bem-vindo ao sistema de gerenciamento<br>
                de estoque do <span class="destaque">Tera Atacado</span>
            </p>
        
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
 
        <main>
          
            <div class="summary-cards">
            
            <div class="container">

               <span class="card-title">Total de produtos</span>
               <span class="card-value">248</span>
               <span class="card-message">Nenhum reabastecimento esse mês</span>

            </div>

            <div class="container">
               <span class="card-title">Produtos em estoque</span>
               <span class="card-value">101</span>
               <span class="card-message vermelho ">Necessário repor estoque!!!</span>
            </div>

            <div class="container">
               <span class="card-title">Estoque baixo</span>
               <span class="card-value">12</span>
               <span class="card-message laranja">Requer atenção!</span>
            </div>

            <div class="container">
               <span class="card-title">Sem estoque</span>
               <span class="card-value">3</span>
               <span class="card-message vermelho">Reposição necessária</span>
            </div>
        
            </div>




            <div class="chart-row">
             
            <div class="card-graphic">
      
            <span class="card-title2">Entradas e saídas</span>
            <span class="card-description">Movimentações ao longo do período</span>

        <select class="select">
            <option value="30">Últimos 30 dias</option>
            <option value="7">Últimos 7 dias</option>
            <option value="6">Últimos 6 meses</option>
        </select>
        </div>

     <div class="card-stock">
       
        <span class="card-title3">Produtos de estoque baixo</span>
        <span class="card-description2">Itens que precisam de reposição</span>

        <button class="button">Ver todos</button>


     </div>


</div>
               
        </main>
        

    </div>



   
</body>
</html>