<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Acesso ao sistema Tera Atacado">
    <title>Entrar | Tera Atacado</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href="Img/tera_logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <main class="login-page">
        <section class="login-shell" aria-labelledby="login-title">
            <div class="brand-panel">
                <img src="Img/tera_logo.png" alt="Tera Atacado" class="logo">
                <p class="brand-kicker">Gestão comercial</p>
                <h1>Controle seu negócio com clareza.</h1>
                <p class="brand-description">
                    Acesse o sistema para acompanhar estoque, movimentações e as informações do seu atacado.
                </p>
                
            </div>

            <div class="login-panel">
                <div class="login-header">
                    <span class="section-label">Acesso ao sistema</span>
                    <h2 id="login-title">Bem-vindo de volta</h2>
                    <?php  
                 
                    if(isset($_SESSION['erro_login']) && $_SESSION['erro_login'] == true){
                        ?>
                        <p style="color: red;"><b>CPF ou senha errados, tente novamente</b></p>
                    <?php
                    unset($_SESSION['erro_login']);
                    }else{
                        ?>
                        <p>Digite seu CPF e Senha</p>
                    <?php
                    }
                    unset($_SESSION['erro_login']);
                    ?>
                    
                </div>

                <form class="login-form" action="login.php" method="POST">
                    <div class="field-group">
                        <label for="usuario">CPF</label>
                        <input
                            id="usuario"
                            type="text"
                            placeholder="Digite seu CPF"
                            required
                            name="CPF"
                            maxlength="14"
                        >
                    </div>

                    <div class="field-group">
                        <label for="senha">Senha</label>
                        <div class="password-field">
                            <input
                                id="senha"
                                type="password"
                                autocomplete="current-password"
                                placeholder="Digite sua senha"
                                required
                                name="senha"
                            >
                            <button class="password-toggle" type="button" aria-label="Mostrar senha" aria-pressed="false">
                                Mostrar
                            </button>
                        </div>
                    </div>

                    <button class="button button-primary" type="submit">Entrar</button>
                </form>

            </div>
        </section>
    </main>

    <script src="JAVASCRIPT/script.js"></script>
</body>
</html>
