<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro The Glarck</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styleLogin.css">
</head>

<body>


    <header>
        <div class="logo">
            <img src="../assets/images/log5.png" width="50%" height="auto" loading="lazy" alt="Logotipo The Glarck" class="card-icon">
        </div>
        <h1>Conta criada com sucesso!</h1>
    </header>

    <?php
    if (isset($_GET['erro'])) {
        echo "<p class='erro' style='color: red; margin-top:20px;'>" . htmlspecialchars($_GET['erro']) . "</p>";
    }
    ?>

    <?php
    session_start();

    // Função para verificar se o e-mail já está cadastrado
    function isEmailExists($email, $conexao) {
        $query = "SELECT COUNT(*) FROM tb_usuario WHERE ds_email = ?";
        $stmt = mysqli_prepare($conexao, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        return $count > 0;
    }

    if (isset($_POST['login'])) {
        $nome = $_POST['nome'];
        $senha = $_POST['senha'];
        $email = $_POST['usuario'];

        $conexao = mysqli_connect("localhost", "root", "", "bd_glark", "3306");

        if (!$conexao) {
            echo "A conexão MYSQL apresentou erro: " . mysqli_connect_error();
            exit();
        }

        // Verificar se o e-mail já está cadastrado
        if (isEmailExists($email, $conexao)) {
            mysqli_close($conexao);
            header("Location: cadastro.php?erro=Este e-mail já está cadastrado");
            exit();
        }

        // Inserir o novo usuário
        $operacao = "INSERT INTO tb_usuario (ds_email, ds_senha, nm_user) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conexao, $operacao);
        mysqli_stmt_bind_param($stmt, "sss", $endereco, $senha, $nome);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conexao);

        // Redirecionar após o cadastro bem-sucedido
 
    }
    ?>

    <a href="login.php" class="voltar">Retornar</a>

</body>

</html>