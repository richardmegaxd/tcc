<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <?php
    session_start();

    if (!isset($_SESSION["logado"]) || $_SESSION["logado"] != true) {
        header("Location: login.php");
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_usuario = $_SESSION["id_usuario"];
        $nome = $_POST['nome'];
        $idade = $_POST['idade'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $senha = $_POST['senha'];

        $conexao = mysqli_connect("localhost", "root", "", "bd_glark", "3306");

        if (mysqli_connect_errno()) {
            echo "Falha na conexão com o MySQL: " . mysqli_connect_error();
            exit;
        }

        $id_usuario = mysqli_real_escape_string($conexao, $id_usuario);
        $nome = mysqli_real_escape_string($conexao, $nome);
        $idade = mysqli_real_escape_string($conexao, $idade);
        $email = mysqli_real_escape_string($conexao, $email);
        $telefone = mysqli_real_escape_string($conexao, $telefone);
        $senha = mysqli_real_escape_string($conexao, $senha);

        $query = "UPDATE tb_usuario SET 
              nm_usuario = '$nome', 
              dt_usuario = '$idade', 
              email_usuario = '$email', 
              tel_usuario = '$telefone', 
              senha_usuario = '$senha' 
              WHERE id_usuario = '$id_usuario'";

        if (mysqli_query($conexao, $query)) {
            $_SESSION['message'] = "Perfil atualizado com sucesso!";
            header("Location: home.php");
            exit;
        } else {
            echo "Erro ao atualizar o perfil: " . mysqli_error($conexao);
        }

        mysqli_close($conexao);
    } else {
        header("Location: edit.php");
        exit;
    }
    ?>

</body>

</html>