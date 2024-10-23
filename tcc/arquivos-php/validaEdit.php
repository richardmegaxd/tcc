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
        $loginUsuario = $_SESSION['usuario'];
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $apelido = $_POST['apelido'];
        $senha =$_POST['senha'];

        $conexao = mysqli_connect("localhost", "root", "", "bd_glark", "3306");

        if (mysqli_connect_errno()) {
            echo "Falha na conexão com o MySQL: " . mysqli_connect_error();
            exit;
        }

        $nome = mysqli_real_escape_string($conexao, $nome);
        $apelido = mysqli_real_escape_string($conexao, $apelido);
        $email = mysqli_real_escape_string($conexao, $email);
        $senha = mysqli_real_escape_string($conexao, $senha);

        $query = "UPDATE tb_usuario SET 
              nm_user = '$nome',  
              ds_email = '$email', 
              nm_apelido = '$apelido', 
              ds_senha = '$senha' 
              WHERE ds_email = '$loginUsuario'";

        if (mysqli_query($conexao, $query)) {
            $_SESSION['nome'] = $nome;      
            $_SESSION['apelido'] = $apelido;
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