<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../home-assets/css/style.css">
    <link rel="stylesheet" href="../home-assets/css/lightslider.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

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

        $id_usuario = $_SESSION["id_usuario"];
        $id_usuario = mysqli_real_escape_string($conexao, $id_usuario);

        $query = "SELECT * FROM tb_usuario WHERE cd_usuario = '$id_usuario'";
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

    <!DOCTYPE html>
    <html lang="pt-BR">

    <head>
        <meta charset="UTF-8">
        <title>Editar Perfil</title>
        <link rel="stylesheet" href="style-php-html/styleEditarConta.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    </head>

    <body>

        <div class="login-container">
            <h1>Editar Perfil</h1>
            <form action="validaEdit.php" method="POST">
                <div class="input-container">
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($user['nm_usuario']); ?>" required><br>
                    <label for="nome">Nome:</label>
                </div>

                <div class="input-container">
                    <input type="number" id="idade" name="idade" value="<?php echo htmlspecialchars($user['dt_usuario']); ?>" required><br>
                    <label for="idade">Idade:</label>
                </div>

                <div class="input-container">
                    <input type="text" id="endereco" name="endereco" value="<?php echo htmlspecialchars($user['end_usuario']); ?>" required><br>
                    <label for="email">email:</label>
                </div>



                <input type="submit" value="Atualizar">

            </form>
        </div>
        <a href="home.php">Cancelar</a>

    </body>

    </html>