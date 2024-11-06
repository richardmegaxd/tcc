<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigoDigitado = $_POST['codigo'];
    $email = $_POST['email']; // Email do usuário, pode ser salvo na sessão ao cadastrar

    $conexao = mysqli_connect("localhost", "root", "", "bd_glark", "3306");

    if (!$conexao) {
        die("Conexão falhou: " . mysqli_connect_error());
    }

    // Buscar o código de confirmação e o status da conta
    $query = "SELECT codigo_confirmacao, conta_ativa FROM tb_usuario WHERE ds_email = ?";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $codigoBanco, $contaAtiva);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($contaAtiva == 1) {
        echo "Conta já está ativada.";
    } elseif ($codigoBanco == $codigoDigitado) {
        // Atualiza o status da conta para ativa
        $query = "UPDATE tb_usuario SET conta_ativa = 1, codigo_confirmacao = NULL WHERE ds_email = ?";
        $stmt = mysqli_prepare($conexao, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: cadastro_sucesso.php");
            exit();
        } else {
            echo "Erro ao ativar a conta.";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Código de confirmação incorreto.";
    }

    mysqli_close($conexao);
}
?>
