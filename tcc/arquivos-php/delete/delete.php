<?php
session_start();

if (isset($_SESSION['logado']) && $_SESSION['logado'] == true) {

    $cdUsuario = $_SESSION['cd_usuario'];

    $conexao = mysqli_connect("localhost", "root", "", "bd_glark");

    if (!$conexao) {
        die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
    }

    // Altera o status do usuário para 0 (desabilitado) em vez de deletar
    $operacao = "UPDATE tb_usuario SET conta_ativa = 0 WHERE cd_usuario = '$cdUsuario'";

    if (mysqli_query($conexao, $operacao)) {
        session_destroy();
        echo '
        <header>
            <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
            <link rel="stylesheet" href="../../assets/css/styleLogin.css">
            <div id="section-delete" class="logo">
            <img src="../../assets/images/log5.png" width="50%" height="auto" loading="lazy" alt="Logotipo The Glarck" class="card-icon">
            <h1>The Glark</h1><br>
     </div> 
        </header>
        <div class="logout">
            <h1>Perfil excluído com Sucesso!</h1><br><br>
            <a href="../../index.html" class="button-link">Voltar à tela inicial</a>
        </div>';
    } else {
        echo "Erro ao excluir perfil: " . mysqli_error($conexao);
    }

    mysqli_close($conexao);
} else {
    header("Location: index.html");
    exit;
}
?>

