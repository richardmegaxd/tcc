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
        <?php
        if (isset($_GET['erro'])) {
            echo "<p class='erro' style='color: red; font: size 20px; '>" . htmlspecialchars($_GET['erro']) . "</p>";
        }
        ?>
        <?php
    session_start();
    if (isset($_POST['login'])){

        $nome = $_POST['nome'];
        $senha = $_POST['senha'];
        $endereco = $_POST['login'];

        $conexao = mysqli_connect("localhost","root","","bd_glark");

        

        $operacao = "INSERT INTO tb_usuario (ds_email, ds_senha, nm_user) VALUES ('$endereco','$senha','$nome')";

        mysqli_query($conexao, $operacao);
        "Conta cadastrada com Sucesso";
    }
    if (mysqli_connect_errno()) // verifica se ocorreu um erro na conexão com o banco de dados
        {
        echo "A conexão MYSQL apresentou erro: " . mysqli_connect_error(); // descreve o erro que ocorreu
        }
    ?>
    </div>

</body>

</html>



