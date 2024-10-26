<?php
session_start();

$conexao = mysqli_connect("localhost", "root", "", "bd_glark", "3306");

if (mysqli_connect_errno()) {
    echo "A conexão MYSQL apresentou erro: " . mysqli_connect_error();
    exit;
}

if (isset($_POST["login"]) && isset($_POST["senha"])) {
    $loginUsuario = $_POST["login"];
    $senhaUsuario = $_POST["senha"];

    // Sanitizar os dados
    $loginUsuario = mysqli_real_escape_string($conexao, $loginUsuario);
    $senhaUsuario = mysqli_real_escape_string($conexao, $senhaUsuario);

    // Verifica se a conta está ativa
    $queryAtivo = "SELECT * FROM tb_usuario WHERE ds_email = '$loginUsuario' AND ds_senha = '$senhaUsuario' AND conta_ativa = 1";
    $procuraAtivo = mysqli_query($conexao, $queryAtivo);

    if (mysqli_num_rows($procuraAtivo) > 0) {
        $usuario = mysqli_fetch_assoc($procuraAtivo);
        $idUsuario = $usuario['cd_usuario'];
        $nomeUsuario = $usuario['nm_user'];
        $apelidoUsuario = $usuario['nm_apelido']; // Armazena o nome completo do usuário

        // Define as variáveis de sessão
        $_SESSION['logado'] = true;
        $_SESSION['usuario'] = $loginUsuario;
        $_SESSION['cd_usuario'] = $idUsuario;
        $_SESSION['nome'] = $nomeUsuario;
        $_SESSION['apelido'] = $apelidoUsuario; // Define o nome na sessão

        header("Location: home.php");
    } else {
        // Verifica se a conta existe, mas está inativa
        $queryInativo = "SELECT * FROM tb_usuario WHERE ds_email = '$loginUsuario' AND ds_senha = '$senhaUsuario' AND conta_ativa = 0";
        $procuraInativo = mysqli_query($conexao, $queryInativo);

        if (mysqli_num_rows($procuraInativo) > 0) {
            // Salva o ID e o nome do usuário para confirmar reativação
            $usuario = mysqli_fetch_assoc($procuraInativo);
            $_SESSION['temp_usuario'] = $usuario['cd_usuario'];
            $_SESSION['nome'] = $usuario['nm_user']; // Define o nome temporariamente para exibir na confirmação

            header("Location: conf_ativacao.php");
        } else {
            $erro = "Login ou senha incorretos. Por favor, tente novamente.";
            header("Location: login.php?erro=" . urlencode($erro));
        }
    }
} else {
    $erro = "Por favor, preencha todos os campos.";
    header("Location: login.php?erro=" . urlencode($erro));
}

mysqli_close($conexao);
?>
