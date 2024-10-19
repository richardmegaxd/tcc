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
    <a href="../index.html"><ion-icon name="return-up-back-sharp"></ion-icon></a>

    <header>
        <div class="logo">
            <img src="../assets/images/log5.png" width="50%" height="auto" loading="lazy" alt="Logotipo The Glarck" class="card-icon">
        </div>
        <h1>Olá, bem-vindo de volta!</h1>
        <p>Primeira vez aqui? <span class="text-white" onclick="document.location='../arquivos-php/cadastro.php'">Cadastre-se gratuitamente</span></p>
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
        <div id="buttonDiv"></div>
        </div>
        <p class="termos">
            Você reconhece que leu e concorda com nossos
            <a href="#">Termos de Serviço</a> e nossa
            <a href="#">Política de Privacidade</a>.
        </p>
    </form>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://accounts.google.com/gsi/client" async></script>
    <script src="https://cdn.jsdelivr.net/npm/jwt-decode@3.1.2/build/jwt-decode.js"></script>

    <script>
      function handleCredentialResponse(response) { // response = dado vem criptografado
        const data = jwt_decode(response.credential) //jwt_decode para descriptografar
        console.log(data)
      }

      window.onload = function () {
     google.accounts.id.initialize({
        client_id: "1028970424611-5g112tt1l0bqbgoe8a57clrkb7f0ks5l.apps.googleusercontent.com",
        callback: handleCredentialResponse, // A callback precisa estar dentro da inicialização
        use_fedcm_for_prompt: "true" // Esta configuração pode estar aqui também
    });

    google.accounts.id.renderButton(
        document.getElementById("buttonDiv"),
        { 
            theme: "outline", 
            size: "large", 
            type: "standard",
            shape: "pill",
            text: "signin_with",
            logo_alignment: "left"
        }  // customização dos atributos
    );

    // O prompt não precisa de função de callback
    google.accounts.id.prompt((notification) => {
        if (notification.isNotDisplayed() || notification.isSkippedMoment()) {
            console.log("One-tap sign-in não foi exibido.");
        }
    });
};

    </script>
</body>

</html>