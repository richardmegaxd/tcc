<?php
session_start();

$_SESSION['logado'] = false; // variável global de sessão logado

$conexao = mysqli_connect("localhost", "root", "", "bd_glark"); // host, usuário, senha e banco

if (mysqli_connect_errno()) // verifica se ocorreu um erro na conexão com o banco de dados
{
    echo "A conexão MYSQL apresentou erro: " . mysqli_connect_error(); // descreve o erro que ocorreu
}

if (isset($_POST["login"]) && isset($_POST["senha"])) {
    $loginUsuario = $_POST["login"]; // converte o que inserido no post em uma variável comum
    $senhaUsuario = $_POST["senha"];

    // Sanitizar os dados
    $loginUsuario = mysqli_real_escape_string($conexao, $loginUsuario);
    $senhaUsuario = mysqli_real_escape_string($conexao, $senhaUsuario);

    $seleciona_usuario = "SELECT * FROM tb_usuario WHERE ds_email = '$loginUsuario' AND ds_senha = '$senhaUsuario'"; // efetua a seleção no banco de dados e atribui a uma variável
    
    $procura = mysqli_query($conexao, $seleciona_usuario); // Realiza uma consulta no banco de dados

    $checar_usuario = mysqli_num_rows($procura); // verifica quantas linhas correspondente a busca feita no banco de dados

    if ($checar_usuario > 0) {
        // Se o usuário existe, recupere o ID do usuário
        $usuario = mysqli_fetch_assoc($procura);
        $idUsuario = $usuario['id_usuario'];

        // Defina o ID do usuário na sessão
        $_SESSION['logado'] = true;
        $_SESSION['usuario'] = $loginUsuario;
        $_SESSION['id_usuario'] = $idUsuario;
        
        header("Location: home.php");
    } else {
        $erro = "Login ou senha incorretos. Por favor, tente novamente.";
        header("Location: login.php?erro=" . urlencode($erro));
    }
} else {
    $erro = "Por favor, preencha todos os campos.";
    header("Location: login.php?erro=" . urlencode($erro));
}

mysqli_close($conexao);
?>
