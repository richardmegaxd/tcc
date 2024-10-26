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
        echo "
        <header>
            <h3>The Glark</h3>
        </header>
        <div class='logout'>
            <h1>Perfil excluido com Sucesso!</h1>
            <button onclick=\"location.href='../index.html'\" type=\"button\">Voltar a tela inicial</button>
        </div>";
    } else {
        echo "Erro ao excluir perfil: " . mysqli_error($conexao);
    }

    mysqli_close($conexao);
} else {
    header("Location: index.html");
    exit;
}
?>
