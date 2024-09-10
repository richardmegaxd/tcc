<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login The Glarck</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styleLogin.css">
</head>

<body>
    <div class="star">
        <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
    </div>

    <header>
        <div class="logo">
            <img src="../assets/images/log5.png" width="50%" height="auto" loading="lazy" alt="Logotipo The Glarck" class="card-icon">
        </div>
        <h1>Olá, bem-vindo de volta!</h1>
        <p>Primeira vez aqui? <span class="text-white" onclick="document.location='../arquivos-php/cadastro.php'" >Cadastre-se gratuitamente</span></p>
    </header>

    <?php
    if (isset($_GET['erro'])) {
        echo "<p class='erro' style='color: red; margin-top:20px;'>" . htmlspecialchars($_GET['erro']) . "</p>";
    }
    ?>

    <form action="validarlogin.php" method="post">

        <input type="email" id="username" required name="login" placeholder="E-mail" />

        <input type="password" id="user-password" required name="senha" placeholder="Senha" minlength="6" />

        <p class="text-senha">A senha deve conter no mínimo 6 caracteres</p>

        <button type="submit">ENTRAR</button>

        <div class="or">ou</div>

        <div class="media-options">
            <a href="#" class=" field google">
                <img width="30" height="30" src="https://img.icons8.com/color/48/google-logo.png" alt="google-logo" class="google-icon" />
                <span>Conecte-se com Google</span>
            </a>
        </div>
        <p class="termos">
            Você reconhece que leu e concorda com nossos
            <a href="#">Termos de Serviço</a> e nossa
            <a href="#">Política de Privacidade</a>.
        </p>
    </form>
</body>

</html>