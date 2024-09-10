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
    <div class="star">
        <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
    </div>

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
    if (isset($_POST['login'])){

        $nome = $_POST['nome'];
        $senha = $_POST['senha'];
        $endereco = $_POST['login'];

        $conexao = mysqli_connect("localhost","root","","bd_glark");

        

        $operacao = "INSERT INTO tb_usuario (ds_email, ds_senha, nm_user) VALUES ('$endereco','$senha','$nome')";

        mysqli_query($conexao, $operacao);
        
    }
    if (mysqli_connect_errno()) // verifica se ocorreu um erro na conexão com o banco de dados
        {
        echo "A conexão MYSQL apresentou erro: " . mysqli_connect_error(); // descreve o erro que ocorreu
        }
    ?>

    <form id="formc" action="login.php" method="post">

 
        <button type="submit" onclick="document.location='../arquivos-php/login.php'">Voltar</button>

   
    </form>


</body>

</html>