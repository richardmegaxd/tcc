<!DOCTYPE html>
    <html lang="pt-BR">

    <head>
        <meta charset="UTF-8">
        <title>Editar Perfil</title>
        <link rel="stylesheet" href="../assets/css/styleLogin.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    </head>  
    
    <?php
    session_start();

    if (!isset($_SESSION["logado"]) || $_SESSION["logado"] != true) {
        header("Location: login.php");
        exit;
    } else {
        $conexao = mysqli_connect("localhost", "root", "", "bd_glark", "3306");

        if (mysqli_connect_errno()) {
            echo "Falha na conexão com o MySQL: " . mysqli_connect_error();
            exit;
        }

        $loginUsuario = $_SESSION['usuario'];
        $loginUsuario = mysqli_real_escape_string($conexao, $loginUsuario);

        $query = "SELECT * FROM tb_usuario WHERE ds_email = '$loginUsuario'";
        $result = mysqli_query($conexao, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
        } else {
            echo "Usuário não encontrado.";
            exit;
        }

        mysqli_close($conexao);
    }
    ?>

    <body>

        <div class="login-container">
            <h1>Editar Perfil</h1>
            <form action="validaEdit.php" method="POST">
                <div class="input-container">
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($user['nm_user']); ?>"><br>
                    <label for="nome">Nome:</label>
                </div>

                <div class="input-container">
                    <input type="text" id="apelido" name="apelido" value="<?php echo htmlspecialchars($user['nm_apelido']); ?>" ><br>
                    <label for="idade">Apelido:</label>
                </div>

                <div class="input-container">
                    <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($user['ds_email']); ?>" ><br>
                    <label for="email">email:</label>
                </div>



                <input type="submit" value="Atualizar">

            </form>
        </div>
        <a href="home.php">Cancelar</a>

    </body>

    </html>