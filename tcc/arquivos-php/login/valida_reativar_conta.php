<?php
session_start();

$conexao = mysqli_connect("localhost", "root", "", "bd_glark", "3306");

if (mysqli_connect_errno()) {
    echo "A conexão MYSQL apresentou erro: " . mysqli_connect_error();
    exit;
}

if (isset($_POST['id_usuario'])) {
    $idUsuario = $_POST['id_usuario'];

    // Atualiza a conta para ativa
    $queryAtivaConta = "UPDATE tb_usuario SET conta_ativa = 1 WHERE cd_usuario = '$idUsuario'";
    mysqli_query($conexao, $queryAtivaConta);

    // Recupera os dados do usuário para atualizar a sessão
    $queryUsuario = "SELECT * FROM tb_usuario WHERE cd_usuario = '$idUsuario'";
    $procuraUsuario = mysqli_query($conexao, $queryUsuario);

    if (mysqli_num_rows($procuraUsuario) > 0) {
        $usuario = mysqli_fetch_assoc($procuraUsuario);
        // Atualiza as variáveis de sessão
        $_SESSION['logado'] = true;
        $_SESSION['usuario'] = $usuario['ds_email'];
        $_SESSION['cd_usuario'] = $usuario['cd_usuario'];
        $_SESSION['nome'] = $usuario['nm_user'];
        $_SESSION['apelido'] = $usuario['nm_apelido'];

        // Limpa a variável temporária
        unset($_SESSION['temp_usuario']);

        // Redireciona para a página inicial ou onde você preferir
        header("Location: ../home.php");
    } else {
        echo "Erro ao recuperar os dados do usuário.";
    }
} else {
    echo "Usuário não encontrado.";
}

mysqli_close($conexao);
?>
