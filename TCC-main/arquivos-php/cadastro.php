<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro The Glarck</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styleCadastro.css">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
</head>

<body>
    <div class="login-container">
        <img src="../assets/images/log5.png" width="50%" height="auto" loading="lazy" alt="" class="card-icon">
        <h1>Crie sua Conta</h1>
        <?php
        if (isset($_GET['erro'])) {
            echo "<p class='erro' style='color: red; font: size 20px; '>" . htmlspecialchars($_GET['erro']) . "</p>";
        }
        ?>
        <form id="formulario-senha" action="validarinsert.php" method="post">

            <div class="input-container">
                <img width="24" height="24" src="https://img.icons8.com/material-rounded/24/person-male.png" alt="person-male"/>
                    <input type="text" id="username" required name="nome" placeholder=" Nome"/>
            </div>

            <div class="input-container">
                <img width="24" height="24" src="https://img.icons8.com/?size=100&id=12623&format=png&color=000000" alt="e-mail-icon"/>
                    <input type="text" id="user-email" required name="login" placeholder=" E-Mail"/>
            </div>

            <div class="input-container">
                <img width="24" height="24" src="https://img.icons8.com/ios-filled/50/key.png" alt="key"/>
                <input type="password" id="senha" required name="senha" placeholder=" Senha"/>
            </div>

            <div class="input-container">
                <img width="24" height="24" src="https://img.icons8.com/ios-filled/50/key.png" alt="key"/>
                <input type="password" id="confirma-senha" required name="confirma-senha" placeholder=" Confirmar senha"/>
            </div>
            
            

            <input type="submit" value="Entrar">

            

            <div class="text-tag">
                <span>
                    conecte-se através de suas redes
                </span>
            </div>

            <div class="card-meta-list">
                <ul class="social-list">
                    <li>
                        <img width="40" height="40" src="https://img.icons8.com/fluency/48/mac-os.png" alt="mac-os"/>
                    </li>

                    <li>
                        <img width="40" height="40" src="https://img.icons8.com/color/48/google-logo.png" alt="google-logo"/>
                    </li>

                    <li>
                        <img width="40" height="40" src="https://img.icons8.com/fluency/48/facebook-new.png" alt="facebook-new"/>
                    </li>

                </ul>
            </div>

        </form>

        <script>
        document.getElementById('formulario-senha').addEventListener('submit', function(event) {
        const senha = document.getElementById('senha').value;
        const confirmaSenha = document.getElementById('confirma-senha').value;
        
        if (senha !== confirmaSenha) {
        event.preventDefault();  // Impede o envio do formulário
        alert('As senhas não coincidem!');  // Exibe alerta
        }
        });
        </script>

    </div>

</body>

</html>