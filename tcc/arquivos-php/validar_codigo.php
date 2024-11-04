<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigoInserido = $_POST['codigo'];
    $email = $_POST['email'];

    $conexao = mysqli_connect("localhost", "root", "", "bd_glark", "3306");

    if (!$conexao) {
        die("Conexão falhou: " . mysqli_connect_error());
    }

    // Verificar se o código de confirmação corresponde ao email do usuário
    $query = "SELECT codigo_confirmacao FROM tb_usuario WHERE ds_email = ? AND codigo_confirmacao = ?";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "ss", $email, $codigoInserido);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        // Código correto - ativar a conta
        $update = "UPDATE tb_usuario SET conta_ativa = 1, codigo_confirmacao = NULL WHERE ds_email = ?";
        $stmtUpdate = mysqli_prepare($conexao, $update);
        mysqli_stmt_bind_param($stmtUpdate, "s", $email);
        mysqli_stmt_execute($stmtUpdate);
        mysqli_stmt_close($stmtUpdate);

        // Redirecionar para a página de login
        header("Location: cadastro_sucesso.php");
        exit();
    } else {
        echo "Código de confirmação inválido.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexao);
}
?>
